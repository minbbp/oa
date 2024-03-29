<?php
/**
 * op操作者，对审核通过的信息进行操作。
 * op的操作采用数据推送的方式，而不是数据拉的方式对请求进行处理。
 * 
 * @author minbbp
 * @time 2013/9/5 10:37 
 */
class Groupops extends  CI_Controller
{
	private $user_id;
	public function  __construct()
	{
		parent::__construct();
		//初始化相关使用到的类，form，auth，model ，email 等相关的类
		$this->load->helper(array('form','url','message'));
		$this->load->library(array('email','form_validation','dx_auth','pagination'));
		$this->load->model('dx_auth/Users','users',TRUE);//加载授权用户的模型
		$this->load->model('Users_model','myusers',TRUE);//加载授权用户的模型
		$this->load->model('Gitsgroup_model','groups',TRUE);//加载git账号库的模型
		$this->load->model('Gits_model','git',TRUE);
		$this->load->model('Group_ops_model','gop',TRUE);
		$this->load->model('Gitsgroupuser_model','gpuser',TRUE);
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->dx_auth->get_user_id();
	}
	/**
	 *  要进行操作的列表
	 */
	public function alllist()
	{
		$config['per_page']=PER_PAGE;
		$config['total_rows']=$this->gop->alllist_count();
		$config['base_url']=base_url('index.php/groupops/alllist');
		$offset=intval($this->uri->segment(3));
		$this->pagination->initialize($config);
		$rs=$this->gop->alllist($offset,$config['per_page']);
		$page=$this->pagination->create_links();
		$data['page']=$page;
		$data['gops']=$rs;
		$data['title']='要处理的git组请求';
		$this->load->view('gitp/ops_alllist',$data);
	}
	/**
	 * 操作说明，给运维人员进行操作说明，包括应该加入哪些组进来，加入哪些指定的人人员进来
	 */
	public function showinfo($gop_id)
	{
		echo $this->load->view('gitp/opshowinfo',$this->gop->get_op_info($gop_id),true);
	}
	/**
	 * 审核通过的记录
	 */
	public function pass($gop_id)
	{
		$data['gop_state']=1;
		$data['gop_oper']=$this->user_id;
		$data['endtime']=time();
		if($this->gop->save($data,$gop_id))
		{
			
			
			//发邮件告知申请者
			$gop_rs=$this->gop->get_one($gop_id);
			 //给所有用户正常使用的git 添加修改字符串
			$all_users=$this->gpuser->new_get_users_by_group_id($gop_rs['group_id']);
			foreach ($all_users as $user)
			{
				$this->git->change_str_by_user($user['user_id'],$gop_rs['group_id']);
			}
			//更新字符串结束 
			$group_rs=$this->groups->find_one($gop_rs['group_id']);
			$group_data['group_state']=1;
			$group_data['last_changetime']=time();
			$userinfo=$this->myusers->get_useremail_by_id($gop_rs['change_id']);
			//更新状态，发送邮件
			$this->groups->save($group_data,$gop_rs['group_id']);
			$edata['name']=$userinfo['realname'];
			$edata['msg']='您的git用户组'.$group_rs['group_name'].'已经开通了！';
			$msg=$this->load->view('mail/mail_common',$edata,TRUE);
			sendcloud($userinfo['email'], "git用户组已经开通[通知]", $msg);
			//审核通过发送邮件通知相关人员，同时修改用户组信息状态为可用
			echo 1;
		}
		else
		{
			echo 0;
		}
	}
	public function bohui($gop_id)
	{
		$data['endtime']=time();
		$data['gop_oper']=$this->user_id;
		$data['gop_state']=-1;
		$data['gop_description']=$this->input->get('gop_description');
	   if($this->gop->save($data,$gop_id))
		{
			$gop_rs=$this->gop->get_one($gop_id);
			$group_rs=$this->groups->find_one($gop_rs['group_id']);
			$userinfo=$this->myusers->get_useremail_by_id($gop_rs['change_id']);
			//更新状态，发送邮件
			$edata['name']=$userinfo['realname'];
			$edata['msg']='您的git用户组'.$group_rs['group_name'].'被运维驳回！驳回原因：'.$gop_rs['gop_description'];
			$msg=$this->load->view('mail/mail_common',$edata,TRUE);
			sendcloud($userinfo['email'], '您的git用户组被驳回',$msg);
			//发邮件告知申请者
			echo 1;
		}
		else
		{
			echo 0;
		}
	}
	/**
	 *  相关的操作说明。
	 */
	public function edit($gop_id)
	{
		$data=$this->gop->get_op_info($gop_id);
		$data['title']="op操作说明";
		$this->load->view('gitp/ops_edit',$data);
	}
	/**
	 * 对op操作过之后，对数据进行持久化
	 */
	public function save($gop_id)
	{
		//保存操作结果，保存个组状态，判断发送邮件
		 $gdata['gop_oper']=$this->user_id;
		 $gdata['gop_state']=$this->input->post('gop_state');
		 $gdata['gop_description']=$this->input->post('gop_description');
		 $sendlevel=$this->input->post('sendlevel');
		 $gdata['addtime']=time();
		if($this->gop->save($gdata,$gop_id))
		{
			$group_id=$this->input->post('group_id');
			$data['group_state']=$this->input->post('group_state');
			$this->groups->save($data,$group_id);
			//需要发送邮件通知相关人员，主要是通知主管和申请者
			$subject="您的git组变更申请已经操作完毕";
			$content="您的git用户组变更申请已经成功！可以使用该用户组啦！";
			$gopinfo=$this->gop->get_one($gop_id);
			$user_info=$this->myusers->get_useremail_by_id($gopinfo['change_id']);
			if($sendlevel==1)
			{
				$level_info=$this->myusers->get_useremail_by_id($user_info['pid']);
				$subject_l="{$user_info['realname']}的git组变更申请已经成功！";
				$content_l="{$user_info['realname']}的git组变更申请已经成功！已经可以使用git用户组了！";
				$this->_sendmail($level_info['email'], $subject_l, $content_l);
			}
			$this->_sendmail($user_info['email'], $subject, $content);
			redirect('groupops/alllist');
		}
		else
		{
			echo " 您的修改结果没有录入导数据库！";
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