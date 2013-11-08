<?php
/**
 * 扩展CI 的控制器类
 */
class MY_Controller extends CI_Controller
{
	public $user_id;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','message'));
		$this->load->library(array('dx_auth','session','pagination'));
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->session->userdata('DX_user_id');
	}
}