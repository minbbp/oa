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
	 *  并导入相关的类库,载入对应的数据库模块信息
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','message'));
		$this->load->library(array('dx_auth','session','pagination'));
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->session->userdata('DX_user_id');
		$this->load->model('Codeonline_require_model','cr',TRUE);
	}
	/**
	 * 列表显示所有需求信息,分页显示所有的需求
	 */
	public function index()
	{
		$config['total_rows']=$this->cr->count_alllist();
		$offset=intval($this->uri->segment(3));
		$re_rs=$this->cr->alllist($offset,PER_PAGE);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$this->load->view('requirements/index',array('re_rs'=>$re_rs,'page'=>$page,'title'=>'需求管理'));
	}
	/**
	 *  删除需求信息，为方便以后真正删除，特把这个方法独立出来。
	 *  @param int $required_id 修改需求信息的主键
	 *  @return string 返回一个json字符串
	 *  
	 */
	public function  delete($required_id)
	{
		$msg=array();
		$data['re_status']=-1;
		if($this->cr->save($data,$required_id))
		{
			$msg['status']=1;
			$msg['msg']='删除成功！';
		}
		else
		{
			$msg['status']=0;
			$msg['msg']='删除失败！';
			log_message('error',"{$this->session->usedata('DX_realname')}->".$msg['msg']);
		}
		echo json_encode($msg,TRUE);
	}
	/**
	 * 修改需求状态
	 * @param int $required_id 修改需求信息的主键
	 * @param  int $re_status 状态
	 * @return string 最后结果返回一个字符创
	 */
	public function change_status($required_id,$re_status)
	{
		$msg=array();
		$data['re_status']=$re_status;
		if($this->cr->save($data,$required_id))
		{
			$msg['status']=1;
			$msg['msg']='状态变更成功！';
		}
		else
		{
			$msg['status']=0;
			$msg['msg']='状态变更失败！';
			log_message('error',"{$this->session->usedata('DX_realname')}->".$msg['msg']);
		}
		echo json_encode($msg,TRUE);
	}
	/**
	* 编辑视图展示
	*@param int $required_id 需求表主键
	*@return void 渲染一个编辑视图
	*/
	public function edit($required_id)
	{
		if($required_id)
		{
			$data=$this->cr->get_one($required_id);
		}
		
		$data->title='需求管理';
		$this->load->view('requirements/edit',$data);
	}
	/**
	 * 保存前端验证后的信息，后端暂时不验证验证信息，保存验证信息。保存之后跳转到列表页面
	 */
	public function save($required_id)
	{
		$data['required_no']=$this->input->post('required_no',TRUE);
		$data['required_title']=$this->input->post('required_title',TRUE);
		$data['re_description']=$this->input->post('re_description',TRUE);
		$data['re_status']=$this->input->post('re_status');
		$data['bg_time']=strtotime($this->input->post('bg_time'));
		$data['re_endtime']=strtotime($this->input->post('re_endtime'));
		if($required_id)
		{
			$data['re_change_id']=$this->user_id;
			$data['last_change_time']=time();
			$result=$this->cr->save($data,$required_id);
		}
		else
		{
			$data['re_add_user']=$this->user_id;
			$data['re_addtime']=time();
			$result=$this->cr->save($data);
		}
		$re_msg=array();
		if($result)
		{
			$re_msg['status']=1;
			$re_msg['msg']='数据写入成功！';
		}
		else
		{
			$re_msg['status']=0;
			$re_msg['msg']='数据写入失败！';
			log_message('error','保存需求信息失败！');
		}
		echo json_encode($re_msg,TRUE);
	}
}