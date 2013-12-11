<?php
class Grouplevel extends CI_Controller
{
	private $user_id;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','message'));
		$this->load->library(array('email','form_validation','pagination','dx_auth'));
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->dx_auth->get_user_id();
		$this->load->model('Group_ops_model','gop',TRUE);// op操作模型
		$this->load->model('Group_level_model','gle',TRUE);//主管操作模型
		$this->load->model('Users_model','users',TRUE);
		$this->load->model('Gitsgroup_model','group',TRUE);
	}
	/**
	 * 我的审核列表
	 */
	public function alllist()
	{
		$config['per_page']=PER_PAGE;
		$config['total_rows']=$this->gle->alllist_count($this->user_id);
		$config['base_url']=base_url('index.php/grouplevel/alllist');
		$offset=intval($this->uri->segment(3));
		$rs=$this->gle->alllist($this->user_id,$config['per_page'],$offset);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$data['groups']=$rs;
		$data['page']=$page;
		$data['title']='审核信息管理';
		$this->load->view('gitp/group_level_list',$data);
		
	}
	/**
	 *  审核内容添加
	 */
	public function edit($gle_id)
	{
		//   审核的同时把相关的信息都列出来，包括账号的用途，账号的描述，申请人，组中的成员
		$data['info']=$this->gle->get_allinfo($gle_id);
		$data['title']='git组信息审批';
		$change_id=$data['info']['change_id'];
		$change_user=$this->users->get_useremail_by_id($change_id);
		$data['change']=$change_user;
		$this->load->view('gitp/level_edit',$data);
	}
	/**
	 * 主管审批通过
	 */
	public function pass($gle_id)
	{
		$data['gle_state']=1;
		$data['apply_time']=time();
		if($this->gle->save($data,$gle_id))
		{
			//这个方法包含，通知op的邮件
			$this->check_commit($gle_id);
			echo "审批通过!_1";
		}
		else
		{
			echo "审批失败，请联系管理员！_0";
		}
	}
	public  function show_bohui($gle_id)
	{
		$this->load->view('gitp/show_bohui',array('gle_id'=>$gle_id,'title'=>'驳回git组申请'));
	}
	/**
	 * 驳回用户的申请请求,发送邮件通知用户
	 */
	public function bohui($gle_id)
	{
		$data['gle_description']=$this->input->post('msg');
		$data['apply_time']=time();
		$data['gle_state']=-1;
		if($this->gle->save($data,$gle_id))
		{
			//发送邮件通知给申请者，并告知驳回原因
		$gle_rs=$this->gle->find_one($gle_id);
		$userinfo=$this->users->get_useremail_by_id($gle_rs['change_id']);
		$emaildata['name']=$userinfo['realname'];
		$emaildata['msg']="您的主管，驳回了您的git组申请。驳回原因:".$data['gle_description'];
		$message=$this->load->view('mail/mail_common',$emaildata,TRUE);
		sendcloud($userinfo['email'], 'git组申请驳回通知', $message);
		 echo "驳回成功！_1";
		 
		}
		else
		{
			echo "你暂时无法驳回用户信息！_0";
		}
	}
	/**
	 * 查看用户组的详细信息
	 */
	public function show($gle_id)
	{
		$data['info']=$this->gle->get_allinfo($gle_id);
		$change_id=$data['info']['change_id'];
		$change_user=$this->users->get_useremail_by_id($change_id);
		$data['change']=$change_user;
		//echo "<pre>";
		//print_r($data);
		$this->load->view('gitp/level_show_info',$data);
	}
	/**
	 * 主管审核同意之后，给op发送处理请求，既然都需要主管审核了，肯定不用再判断这个用户是否是主管之类的信息
	 * 暂时没有发送邮件通知op，等待以后进行添加。
	 */
	public function check_commit($gle_id)
	{
		$gle_rs=$this->gle->find_one($gle_id);
		//$group_rs=$this->group->find_one($gle_rs['group_id']);
		//只有主管审核通过的时候才过去检查,否则不予推送到op端
		if($gle_rs['gle_state']==1)
		{
				  $data['group_id']=$gle_rs['group_id'];
				  $data['change_id']=$gle_rs['change_id'];
				  $data['gop_state']=0;
				  $data['addtime']=time();
				  $this->gop->save($data);
				  $edata['msg']='您有一个git组申请未处理，请尽快处理！';
				  $edata['name']='刘士超';
				  $msg=$this->load->view('mail/mail_common',$edata,TRUE);
				  sendcloud(ADRD_EMAIL_ONE, '您有一个git组申请未处理',$msg);
		}
	}
}