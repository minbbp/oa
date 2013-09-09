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
		$this->load->helper(array('form','url'));
		$this->load->library(array('email','form_validation','dx_auth'));
		$this->load->model('dx_auth/Users','users',TRUE);//加载授权用户的模型
		$this->load->model('Users_model','myusers',TRUE);//加载授权用户的模型
		$this->load->model('Gitsgroup_model','groups',TRUE);//加载git账号库的模型
		$this->load->model('Gits_model','git',TRUE);
		$this->load->model('Gitsgroupuser_model','gpuser',TRUE);
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->dx_auth->get_user_id();
	}
	public function index($group_id)
	{
		$this->edit($group_id);
	}
	/**
	 * 对用户组进行添加和修改，如果存在group_id则是修改，否则则是添加
	 * @param int $group_id  用户组的id
	 *  @return  没有返回值，直接渲染视图
	 */
	public function edit($group_id)
	{
		$data['gits']=$this->git->get_key_account();
		if($group_id)
		{//修改的时候只要给对应的表单一个默认值就好
			 $data['title']="用户组修改";
			 $data['group_id']=$group_id;
			 //查找已经加入的账号和没有加入的账号然后分别输出
			 $data['in']=$this->gpuser->get_gits_in_groups_id($group_id);
			 $data['gits']=$this->gpuser->get_gits_not_groups_id($group_id);
			 $data['group']=$this->groups->find_one($group_id);
			 $this->load->view('gitp/edit',$data);
		}
		else
		{
			$data['title']="用户组申请";
			$this->load->view('gitp/edit',$data);
		}
	}
	/**
	 * 对group进行保存，若果灿仔group_id 则属于更新，否则属于添加
	 * @param int $group_id
	 */
	public function save($group_id)
	{
		$this->output->enable_profiler(TRUE);
		$account=$this->input->post('git_account');
		if($this->form_validation->run('gitgroups')!=false)
		{
			$data['group_name']=$this->input->post('group_name');
			$data['group_description']=$this->input->post('group_description');
			$data['is_lock']=0;//不锁定
			if($group_id)
			{
				$data['last_changetime']=time();
				$rs=$this->groups->save($data,$group_id);
			}
			else
			{
				$data['group_creator']=$this->user_id;
				$data['addtime']=time();
				$data['group_state']=0;
				$insertgroup_id=$this->groups->save($data);
			}
			
			if(FALSE!=$rs || $insertgroup_id!=FALSE)
			{
				//循环插入数据表给相对应的用户信息
			$add_user_group=array();
			$tmp=array();
			
			if($insertgroup_id)
			{
				foreach($account as $key=>$val)
				{
					$tmp['group_id']=$insertgroup_id;
					$tmp['git_account']=$val;
					$tmp['addtime']=time();
					$add_user_group[]=$tmp;
				}
				
			}
			else
			{
				//先删除，在插入
				
				$this->gpuser->delete_more($group_id);
				foreach($account as $key=>$val)
				{
					$tmp['group_id']=$group_id;
					$tmp['git_account']=$val;
					$tmp['addtime']=time();
					$add_user_group[]=$tmp;
				}
			}
			$this->gpuser->insert_more($add_user_group);
			if($group_id)
			{
				$this->create_check_andsendmail($group_id);
				exit;
			}
			else
			{
				$this->create_check_andsendmail($insertgroup_id);
			}
			redirect('gitgroups/alllist');
			}
			else
			{
				echo  " 数据写入失败！";
				exit;
			}
		}
		else
		{
			
			$data['gits']=$this->git->get_key_account();
			$data['group_id']=$group_id;
			$data['title']="用户组信息填写不合格";
			$this->load->view('gitp/edit',$data);
		}
		
	}
	/**
	 * 组显示列表
	 */
	public function alllist()
	{
		$this->load->library('pagination');
		$config['base_url']=base_url('index.php/gitgroups/alllist');
		$config['total_rows']=$this->groups->alllist_count();
		$config['per_page']=5;
		$offset=intval($this->uri->segment(3));
		$rs=$this->groups->alllist($config['per_page'],$offset);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$allgroups['page']=$page;
		$allgroups['groups']=$rs;
		$allgroups['title']='用户组人员管理';
		$this->load->view('gitp/group_alllist',$allgroups);
	}
	/**
	 * 1，无论添加和修改都会发送申请请求，如果修改这个组的人和创建组的人不一致的话，
	 * 应该向创建者发申请和自己的主管提交申请 ，同时向主管和和创建者发送信息
	 * 2，如果修改信息的人为主管，则直接提交信息给op人员发邮件
	 * 3，修改者为主管则不通知创建者，直接通知op。如果是创建者而且创建者不为主管，则需要主管进行审核
	 */
	public function create_check_andsendmail($group_id)
	{
		$groups=$this->groups->find_one($group_id);
		$this->load->model('Group_ops_model','gop',TRUE);// op操作模型
		$this->load->model('Group_level_model','gle',TRUE);//主管操作模型
		$this->load->model('Group_creators_model','gcre',TRUE);//主管操作模型
		$userinfo=$this->myusers->get_useremail_by_id($this->user_id);// 当前用户的信息
		if($this->user_id==$groups['group_creator'])
		{
			
			// 如果说这个人为主管或者没有主管的用户
			if($userinfo['pid']==0)
			{
				//echo "主管大神或者神兵天降！直接同住op要操作，op";
				$data['group_id']=$group_id;
				$data['gop_state']=0;
				$data['addtime']=time();
				$data['change_id']=$this->user_id;
				$this->gop->save($data);
				//echo "我们已经发送邮件给op了";
				$subject="请尽快操作 {$userinfo['realname']}提交的git组";
				$data['name']=$userinfo['realname'];
				$data['msg']="您的git组变更操作，我们已经提交到op进行操作了";
				$data_op['msg']="请尽快操作 {$userinfo['realname']}提交的git组";
				$op_email=$this->load->view('mail/mail_common',$data_op,TRUE);
				$app_email=$this->load->view('mail/mail_common',$data,TRUE);
				$this->_sendmail(ADRD_EMAIL_ONE, $subject, $op_email);
				$this->_sendmail($userinfo['email'], $data['msg'], $app_email);
				
			}
			else
			{
				$ldata['group_id']=$group_id;
				$ldata['gle_level']=$userinfo['pid'];
				$ldata['gle_state']=0;//0  为未审核
				$ldata['change_id']=$this->user_id;
				$this->gle->save($ldata);
				$levelinfo=$this->myusers->get_useremail_by_id($userinfo['pid']);
				//echo "不是主管 要发给主管信息要主管审核,发邮件通知主管";
				$usersubject="已通知您的主管对您的git组变更信息进行审批";
				$levelsubject="请尽快申请您的下属员工的git组信息变更申请";
				$userdata['name']=$userinfo['realname'];
				$userdata['msg']="已通知您的主管对您提交的git账号组变更信息进行审批！";
				$leveldata['name']=$levelinfo['realname'];
				$leveldata['msg']="请尽快审批您的下属员工：{$userdata['name']}的git组变更信息";
				$user_message=$this->load->view('mail/mail_common',$userdata,TRUE);
				$level_message=$this->load->view('mail/mail_common',$leveldata,TRUE);
				$this->_sendmail($userinfo['email'], $usersubject, $user_message);
				$this->_sendmail($levelinfo['email'], $levelsubject, $level_message);
			}
		}
		else
		{
			echo "不是同一个人 要发邮件给创建者创建者审核通过之后，然后发邮件给主管";
			//如果不是一个人，如果为主管或者单兵怎直接提交op进行操作
			if($userinfo['pid']==0)
			{
				echo "主管大神或者神兵天降！直接同住op要操作，op";
				$data['group_id']=$group_id;
				$data['gop_state']=0;
				$data['addtime']=time();
				$data['change_id']=$this->user_id;
				$this->gop->save($data);
				echo " 我们已经发送邮件给op了";
				$subject="请尽快操作 {$userinfo['realname']}提交的git组";
				$data['name']=$userinfo['realname'];
				$data['msg']="您的git组变更操作，我们已经提交到op进行操作了";
				$data_op['msg']="请尽快操作 {$userinfo['realname']}提交的git组";
				$op_email=$this->load->view('mail/mail_common',$data_op,TRUE);
				$app_email=$this->load->view('mail/mail_common',$data,TRUE);
				$this->_sendmail(ADRD_EMAIL_ONE, $subject, $op_email);
				$this->_sendmail($userinfo['email'], $data['msg'], $app_email);
			}
			else
			{
				$ldata['group_id']=$group_id;
				$ldata['gle_level']=$userinfo['pid'];
				$ldata['gle_state']=0;//0  为未审核
				$ldata['change_id']=$this->user_id;
				$gle_id=$this->gle->save($ldata);//因为为保存，所以插入创建者的时候，把对应的主管审核的主键id也插入。
				//保存信息给创建者，让创建者审核信息
				$cdata['group_id']=$group_id;
				$cdata['gcre_creator']=$groups['group_creator'];
				$cdata['change_id']=$this->user_id;
				$cdata['gcre_state']=0;// 未审核
				$cdata['gle_id']=$gle_id;
				$this->gcre->save($cdata);
				echo "不是主管   发送邮件给创建者和主管 这个地方是并行的";
				//发送邮件给主管，信息创建者，以及申请者本人
				$levelinfo=$this->myusers->get_useremail_by_id($userinfo['pid']);
				//echo "不是主管 要发给主管信息要主管审核,发邮件通知主管";
				$usersubject="已通知您的主管对您的git组变更信息进行审批";
				$levelsubject="请尽快申请您的下属员工的git组信息变更申请";
				$userdata['name']=$userinfo['realname'];
				$userdata['msg']="已通知您的主管对您提交的git账号组变更信息进行审批！";
				$leveldata['name']=$levelinfo['realname'];
				$leveldata['msg']="请尽快审批您的下属员工：{$userdata['name']}的git组变更信息";
				$user_message=$this->load->view('mail/mail_common',$userdata,TRUE);
				$level_message=$this->load->view('mail/mail_common',$leveldata,TRUE);
				$this->_sendmail($userinfo['email'], $usersubject, $user_message);
				$this->_sendmail($levelinfo['email'], $levelsubject, $level_message);
				$creatorinfo=$this->myusers->get_useremail_by_id($groups['group_creator']);
				$creator_subject="{$userinfo['realname']}变更了您的git组信息，请您审核";
				$creatordata['name']=$creatorinfo['realname'];
				$creatordata['msg']="{$userinfo['realname']}变更了您的git组信息，请您尽快审核";
				$creator_msg=$this->load->view('mail/mail_common',$creatordata,TRUE);
				$this->_sendmail($creatorinfo['email'], $creator_subject, $creator_msg);
				//一旦主管和创建者者确认通过我们才能传递信息流给op，op才能进行操作
			}
		}
	}
	//发送邮件的函数
	private function _sendmail($to,$subject,$message,$cc=null)
	{
		$this->email->from(SYS_EMAIL,SYS_EMAILNAME);
		$this->email->to($to);
		if($cc)
		{
			$this->email->cc($cc);
		}
		$this->email->subject($subject);
		$this->email->message($message);
		return $this->email->send();
	}
}