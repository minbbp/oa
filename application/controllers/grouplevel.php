<?php
class Grouplevel extends CI_Controller
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
		$this->load->model('Users_model','users',TRUE);
	}
	/**
	 * 我的审核列表
	 */
	public function alllist()
	{
		$config['per_page']=5;
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
	 * 保存审核内容
	 */
	public function save($gle_id)
	{
		$data['gle_state']=$this->input->post('gle_state');
		$data['gle_description']=$this->input->post('gle_description');
		$data['addtime']=time();
		if($this->gle->save($data,$gle_id))
		{
			
			
			redirect('grouplevel/alllist');
		}
		else
		{
			show_404();
		}
	}
	/**
	 *  检查其他人是否提交了，如果说我是最后一个提交，则提交信息给op
	 */
	public function check_commit()
	{
		
	}
}