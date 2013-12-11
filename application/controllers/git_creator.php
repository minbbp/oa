<?php
class Git_creator extends CI_Controller
{
	private $user_id;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','message'));
		$this->load->library(array('email','form_validation','pagination','dx_auth','session'));
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->dx_auth->get_user_id();
		$this->load->model('Git_op_level_model','gol',TRUE);// op操作模型
		$this->load->model('Group_creators_model','creator',TRUE);//创建者操作模型
		$this->load->model('Users_model','users',TRUE);
		$this->load->model('Gits_model','git',TRUE);
	}
	/**
	 * 我的审核列表
	 */
	public function alllist()
	{
		$config['per_page']=PER_PAGE;
		$config['total_rows']=$this->creator->alllist_count($this->user_id);
		$config['base_url']=base_url('index.php/groupcreator/alllist');
		$offset=intval($this->uri->segment(3));
		$rs=$this->creator->alllist($this->user_id,$config['per_page'],$offset);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$data['groups']=$rs;
		$data['page']=$page;
		$data['title']='我的git组审批';
		$this->load->view('gitp/group_creator_list',$data);
	}
	/**
	 * 同意审批通过
	 */
	public function pass($gcre_id)
	{
		$data['gcre_state']=1;
		//1,修改当前状态
		if($this->creator->save($data,$gcre_id))
		{
			//2,检查当前表中的审批是否都审批结束了，如果都审批结束了，则推送消息给op，同时发送邮件给指定的op
			$this->check_commit($gcre_id);
		}
	}
	/**
	 * 保存用户的驳回信息,审批者当中有一个人驳回了审批信息，当前用户的审批不能通过。所以用户审核不通过的时候，
	 * 直接发送邮件告诉用户，有人驳回了git用户申请信息。同时把驳回原因告诉用户。视图就在当前页面
	 * 这个方法保存工单失效，同时驳回所有的工单信息
	 */
	public function reject($gcre_id)
	{
		$data['gcre_state']=-1;
		$data['gcre_description']=$this->input->post('gcre_description');
		if($this->creator->save($data,$gcre_id))
		{
			$gcre_rs=$this->creator->find_one($gcre_id);
			$app_userinfo=$this->git->get_userinfo_by_git_id($gcre_rs['git_id']);
			$edata['name']=$app_userinfo['realname'];
			$edata['msg']=$subject."驳回原因：".$gcre_rs['gcre_description'];
			$subject=$this->session->userdata('DX_realname')."驳回了您的git组申请";
			$message=$this->load->view('mail/mail_common',$edata,TRUE);
			//  发送邮件告诉用户驳回原因
			if(sendcloud($app_userinfo['email'], $subject, $message))
			{
				echo "成功驳回用户请求！";
			}
			else
			{
				echo "发送邮件通知用户失败！";
			}
		}
		else		 
		{
			echo  "您暂时不能驳回用户申请！";
		}
	}
	/**
	 *  审核内容添加
	 */
	public function edit($gcre_id)
	{
		//   审核的同时把相关的信息都列出来，包括账号的用途，账号的描述，申请人，组中的成员
		$data['info']=$this->creator->get_allinfo($gcre_id);
		$data['title']='git组信息审批';
		$change_id=$data['info']['change_id'];
		$change_user=$this->users->get_useremail_by_id($change_id);
		$data['change']=$change_user;
		$this->load->view('gitp/creator_edit',$data);
	}
	/**
	 * 保存审核内容
	 */
	public function save($gcre_id)
	{
		$data['gcre_state']=$this->input->post('gcre_state');
		$data['gcre_description']=$this->input->post('gcre_description');
		$data['addtime']=time();
		if($this->creator->save($data,$gcre_id))
		{
			$gcre_rs=$this->creator->find_one($gcre_id);
			if($gcre_rs['git_id']!="")
			{
				$this->check_commit($gcre_id);
			}
			else
			{
				$this->check_git_commit($gcre_rs['git_id']);
			}
			redirect('groupcreator/alllist');
			//要在这里判断是否把这个操作流程推送到op
		}
		else
		{
			show_404();
		}
	}
	/**
	 * git组拥有者进行审批，如果审批不通过则不推送消息给op，
	 * 如果对应项目的主管也审核通过了，则直接推送消息给op
	 */
	public function check_commit($gcre_id)
	{
		$gcre_rs=$this->creator->find_one($gcre_id);
		if($gcre_rs['gcre_state']==1)
		{
			if($this->creator->check_state($gcre_rs['gle_id']))
			{
				$gle_rs=$this->gol->find_one($gcre_rs['gle_id']);
				if($gle_rs['state']==1)
				{//推送消息给op
					$gle_rs['level_id']=$gcre_rs['gle_id'];
					$gle_rs['btime']=time();
					$gle_rs['state']=0;
					$gle_rs['type_id']=1;
					unset($gle_rs['gits_opid'],$gle_rs['user_id']);
					if($this->gol->save($gle_rs))
					{
						$edata['msg']='您有一个git组申请未处理，请尽快处理！';
						$edata['name']='刘士超';
						$msg=$this->load->view('mail/mail_common',$edata,TRUE);
						sendcloud(ADRD_EMAIL_ONE, '您有一个git组申请未处理',$msg);
						echo " 请等待运维人员进行审批！";
						
					}
					else
					{
						echo "系统暂时无法向运维人员推送消息";
					}
						
				}
			}
			else
			{
				echo "请等待相关人员进行审批！";
			}
		}

	}
	public function check_git_commit($git_id)
	{
		if($this->creator->check_git_state($git_id))
		{//审核通过改变op的操作状态
			$this->load->model('Gits_model','gits',TRUE);
			$data['op_state']=0;
			$this->gits->save($data,$git_id);
		}
	}
}