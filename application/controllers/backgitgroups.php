<?php
/**
 * git用户组管理
 * @author minbbp
 *
 */
class Gitgroups extends CI_Controller
{
	 private  $user_id;
	public function __construct()
	{
		parent::__construct();
		//初始化相关使用到的类，form，auth，model ，email 等相关的类
		$this->load->helper(array('form','url','message'));
		$this->load->library(array('email','form_validation','dx_auth','session','pagination'));
		//$this->load->model('dx_auth/Users','users',TRUE);//加载授权用户的模型
		$this->load->model('Users_model','myusers',TRUE);//加载授权用户的模型
		$this->load->model('Gitsgroup_model','groups',TRUE);//加载git账号库的模型
		$this->load->model('Gits_model','git',TRUE);
		$this->load->model('Gitsgroupuser_model','gpuser',TRUE);
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->dx_auth->get_user_id();
	}
	public function index()
	{
		$this->edit();
	}
	/**
	 * 对用户组进行添加和修改，如果存在group_id则是修改，否则则是添加
	 * @param int $group_id  用户组的id
	 *  @return  没有返回值，直接渲染视图
	 */
	public function edit()
	{
		$data['all_users']=$this->myusers->get_all_users();
		$data['title']="git组申请";
		$data['show_msg']=0;
		$this->load->view('gitp/edit',$data);
		
	}
	/**
	 * 对group进行保存，若果灿仔group_id 则属于更新，否则属于添加
	 * @param int $group_id
	 */
	public function save()
	{
		$account=$this->input->post('user_id');
		//print_r($account);exit;
		if($this->form_validation->run('gitgroups')!=false)
		{
			$data['group_name']=$this->input->post('group_name');
			$data['group_description']=$this->input->post('group_description');
			$data['is_lock']=0;//不锁定
			$data['group_creator']=$this->user_id;
			$data['addtime']=time();
			$data['group_state']=0;
			$insertgroup_id=$this->groups->save($data);
			//循环插入数据表给相对应的用户信息
			$add_user_group=array();
			$tmp=array();
			foreach($account as $key=>$val)
			{
				$tmp['group_id']=$insertgroup_id;
				$tmp['user_id']=$val;
				$tmp['addtime']=time();
				$add_user_group[]=$tmp;
			}
			$this->gpuser->insert_more($add_user_group);
			$this->create_check_andsendmail($insertgroup_id);
			$mdata['show_msg']=1;
			$mdata['title']='添加用户组信息成功！';
			$mdata['all_users']=$this->myusers->get_all_users();
			$this->load->view('gitp/edit',$mdata);
			//redirect('gitgroups/index');
		}
		else
		{
			 $data['show_msg']=0;
			$data['all_users']=$this->myusers->get_all_users();
			$data['title']="用户组信息填写不合格";
			$this->load->view('gitp/edit',$data);
		}
		
	}
	/**
	 * git组管理，分状态管理，每个对应三个状态
	 * 1为可用，-1 已删除，2为禁用，0 未启用
	 */
	public function groups($state=1)
	{
		$this->load->library('pagination');
		$config['base_url']=base_url('index.php/gitgroups/groups/'.$state.'/');
		$config['total_rows']=$this->groups->m_list_count($state);
		$config['per_page']=5;
		$config['uri_segment']=4;
		$offset=intval($this->uri->segment(4));
		$rs=$this->groups->m_list($offset,$config['per_page'],$state);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$allgroups['page']=$page;
		$allgroups['groups']=$rs;
		$allgroups['title']='git组管理';
		$this->load->view('gitp/group_m_list',$allgroups);
	}
	/**
	 *我的git用户组表
	 */
	public function alllist()
	{
		$this->load->library('pagination');
		$config['base_url']=base_url('index.php/gitgroups/alllist');
		$config['total_rows']=$this->groups->alllist_count();
		$config['per_page']=5;
		$offset=intval($this->uri->segment(3));
		$rs=$this->groups->alllist($config['per_page'],$offset,$this->user_id);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$allgroups['page']=$page;
		$allgroups['groups']=$rs;
		$allgroups['title']='我的git组';
		$this->load->view('gitp/group_alllist',$allgroups);
	}
	/**
	 * 查看用户信息通过组的id
	 * @param int $group_id
	 */
	public function showuser($group_id)
	{
		$data['users']=$this->gpuser->get_users_by_group_id($group_id);
		$this->load->view('gitp/showuser',$data);
	}
	/**
	 * 这里简化了发送邮件以及审批的流程
	 *  这里取消了其他用户对当前用户组的修改，用户组一旦创建就不允许创建者进行修改了，其他用户要加入当前的用户组，
	 *  只需要在申请git账号，进行操作。只需要判断当前用户是否是主管，如果当前用户为主管或者当前用户为没有主管的用户，则直接发邮件给
	 *  op以及发送邮件给申请者。
	 *  如果当前申请用户不是主管，则发送请求给主管要主管进行审批。
	 * @param int $group_id
	 */
	public function create_check_andsendmail($group_id)
	{
		$groups=$this->groups->find_one($group_id);
		$this->load->model('Group_ops_model','gop',TRUE);// op操作模型
		$this->load->model('Group_level_model','gle',TRUE);//主管操作模型
		// 如果说这个人为主管或者没有主管的用户
			if($this->session->userdata('DX_pid')==0)
			{
				//主管或者没有主管的用户。直接让op对该申请进行操作
				$data['group_id']=$group_id;
				$data['gop_state']=0;
				$data['addtime']=time();
				$data['change_id']=$this->user_id;
				$this->gop->save($data);
				//echo "我们已经发送邮件给op了";
				$subject="请尽快处理 {$this->session->userdata('DX_realname')}提交的git组申请";
				$data['name']=$this->session->userdata('DX_realname');
				$data['msg']="您的git组申请，我们已经发送邮件通知相关人员了";
				$data_op['msg']="请尽快操作 {$this->session->userdata('DX_realname')}提交的git组";
				$op_email=$this->load->view('mail/mail_common',$data_op,TRUE);
				$app_email=$this->load->view('mail/mail_common',$data,TRUE);
				//$this->_sendmail(ADRD_EMAIL_ONE, $subject, $op_email);
				 sendcloud(ADRD_EMAIL_ONE, $subject, $op_email);
				//$this->_sendmail($userinfo['email'], $data['msg'], $app_email);
				 sendcloud($this->session->userdata('DX_email'), $data['msg'], $app_email);
			}
			else
			{
				$ldata['group_id']=$group_id;
				$ldata['gle_level']=$this->session->userdata('DX_pid');
				$ldata['gle_state']=0;//0  为未审核
				$ldata['change_id']=$this->user_id;
				$this->gle->save($ldata);
				$level_info=$this->session->userdata('level_info');
				$usersubject="已通知您的主管对您的git组申请进行审批";
				$levelsubject="请尽快审批您的下属员工{$this->session->userdata('DX_realname')}的git组信息变更申请";
				$userdata['name']=$this->session->userdata('DX_realname');
				$userdata['msg']="已通知您的主管对您提交的git账号组变更信息进行审批！";
				$leveldata['name']=$levelinfo['realname'];
				$leveldata['msg']="请尽快审批您的下属员工：{$this->session->userdata('DX_realname')}的git组申请";
				$user_message=$this->load->view('mail/mail_common',$userdata,TRUE);
				$level_message=$this->load->view('mail/mail_common',$leveldata,TRUE);
				sendcloud($this->session->userdata('DX_email'), $usersubject, $user_message);
				sendcloud($levelinfo['email'], $levelsubject, $level_message);
			}
	}
	/**
	 * 禁用用户组操作
	 * @param int $group_id
	 */
	public function disable($group_id)
	{
		$data['group_state']=-1;
		$data['last_changetime']=time();
		if($this->groups->save($data,$group_id)){echo 1;}else{echo 0;}
	}
	/**
	 * 删除用户操作。删除也是一个为2的用户状态
	 */
	public function delete($group_id)
	{
		$data['group_state']=2;
		$data['last_changetime']=time();
		if($this->groups->save($data,$group_id)){echo 1;}else{echo 0;}
	}
	/**
	 * 还原用户操作。删除也是一个为1的用户状态
	 */
	public function restart($group_id)
	{
		$data['group_state']=1;
		$data['last_changetime']=time();
		if($this->groups->save($data,$group_id)){echo 1;}else{echo 0;}
	}
	/**
	 * 用户组搜索
	 */
	public function search()
	{
		//跨站点脚本过滤
		$group_name=$this->input->post('group_name',TRUE);
		$this->load->library('pagination');
		$config['base_url']=base_url('index.php/gitgroups/search');
		$config['total_rows']=$this->groups->t_search($group_name);
		$config['per_page']=5;
		$offset=intval($this->uri->segment(3));
		$rs=$this->groups->search($offset,$config['per_page'],$group_name);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$allgroups['page']=$page;
		$allgroups['groups']=$rs;
		$allgroups['title']='git组管理';
		$allgroups['showinfo']=1;
		$allgroups['keywords']=$group_name;
		$this->load->view('gitp/group_m_list',$allgroups);
	}
}