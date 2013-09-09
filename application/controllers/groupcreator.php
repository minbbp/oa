<?php
class Groupcreator extends CI_Controller
{
private $user_id;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url'));
		$this->load->library(array('email','form_validation','pagination','dx_auth'));
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->dx_auth->get_user_id();
		$this->load->model('Group_ops_model','gop',TRUE);// op操作模型
		$this->load->model('Group_level_model','gle',TRUE);//主管操作模型
		$this->load->model('Group_creators_model','creator',TRUE);//创建者操作模型
		$this->load->model('Users_model','users',TRUE);
	}
	/**
	 * 我的审核列表
	 */
	public function alllist()
	{
		$config['per_page']=5;
		$config['total_rows']=$this->creator->alllist_count($this->user_id);
		$config['base_url']=base_url('index.php/groupcreator/alllist');
		$offset=intval($this->uri->segment(3));
		$rs=$this->creator->alllist($this->user_id,$config['per_page'],$offset);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$data['groups']=$rs;
		$data['page']=$page;
		$data['title']='审核信息管理';
		$this->load->view('gitp/group_creator_list',$data);
		
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
		//print_r($gcre_rs);
		if($gcre_rs['gcre_state']==1)
		{
			//echo "<br/>";
			//var_dump($this->creator->check_state($gcre_rs['gle_id']));
			//echo " 到这里吗？";
			//echo $gcre_rs['gle_id'];
			if($this->creator->check_state($gcre_rs['gle_id']))
			{
				$gle_rs=$this->gle->find_one($gcre_rs['gle_id']);
				print_r($gle_rs);
				if($gle_rs['gle_state']==1)
				{//推送消息给op
					$data['change_id']=$gcre_rs['change_id'];
					$data['gop_state']=0;
					$data['group_id']=$gcre_rs['group_id'];
					$this->gop->save($data);
					//echo "都没有问题了，给op推送消息了啊！";
					
				}
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