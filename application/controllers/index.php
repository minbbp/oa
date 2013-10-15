<?php
/**
 * 用户登陆进来主页信息显示
 */
class Index extends  CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->library(array('dx_auth','session'));
		$this->load->model('dx_auth/users','user',TRUE);
		$this->dx_auth->check_uri_permissions();
		
	}
	//显示用户登陆的地址。
	 public function index()
	 {
	 	$roles_allowed_uris = $this->dx_auth->get_permissions_value('uri');
	 	$user=$this->user->get_user_by_id($this->dx_auth->get_user_id());
	 	$data['admin']=$this->dx_auth->is_admin();
	 	$data['url']=$roles_allowed_uris[0];
	 	$data['userinfo']=$user->row_array();
	 	$this->session->userdata('DX_role_id');
	  	$this->load->view("index/Index_index",$data);
	  	$role_id=$this->session->userdata('DX_role_id');//根据角色的不同导入不同的配置文件
	  	$this->load->view("index/Index_menu".$role_id,$data);
	  	$this->load->view("index/Index_center");
	  	
	 }
	 public function center()
	 {
	 	$data['username']=array('username'=>$this->dx_auth->get_username());
	 	$this->load->view("index/Index_info",$data);
	 }
}