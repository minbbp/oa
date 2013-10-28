<?php
/**
 * 与需求模块相关的控制器。对需求模块进行增删改查处理。
 * @author wb-zhibinlliu@sohu-inc.com
 * @version v1.0
 */
class Requirements extends CI_Controller
{
	private $user_id;//当前用户的主键id
	/**
	 *  重写父类的构造方法
	 *  并导入相关的类库
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','message'));
		$this->load->library(array('dx_auth','session','pagination'));
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->session->userdata('DX_user_id');
	}
	/**
	 * 列表显示所有需求信息
	 */
	public function index()
	{
		
	}
}