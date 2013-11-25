<?php
/**
 * 代码上线模块管理控制器
 * @author wb-zhibinliu@sohu-inc.com
 * @version 2013.10.31
 */
class Codeonline_models extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('codeonline_model','cm',TRUE);
		$this->load->model('Users_model','myusers',TRUE);//加载授权用户的模型
		$this->load->model('M_server_model','ms',TRUE);//载入服务器模型
		$this->load->model('M_relymodel_model','mr',TRUE);
		$this->load->model('M_devloper_model','md',TRUE);
		$this->load->model('M_test_model','mt',TRUE);
	}
	/**
	 * 显示一个父级模块的列表
	 */
	public function index()
	{
		$config['total_rows']=$this->cm->total_alllist();
		$offset=intval($this->uri->segment(3));
		$re_rs=$this->cm->alllist($offset,PER_PAGE);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$this->load->view('codeonline_models/index',array('re_rs'=>$re_rs,'page'=>$page,'title'=>'服务管理'));
	}
	/**
	 * 子类层级关系,暂时没有使用到
	 */	
	public function child($pid)
	{
		$config['total_rows']=$this->cm->total_alllist($pid);
		$offset=intval($this->uri->segment(4));
		$re_rs=$this->cm->alllist($offset,PER_PAGE,$pid);
		$page=$this->pagination->create_links();
		$this->load->view('codeonline_models/child',array('re_rs'=>$re_rs,'page'=>$page,'title'=>'服务管理'));
	}
	/**
	 * 编辑模块列表,如果存在$m_id 则是编辑，否则则是添加
	 * @param int $m_id 编号id 
	 * @result 渲染视图类型
	 */
	public function edit($m_id)
	{
		$all_users=$this->myusers->get_all_users();
		$level_users=$this->myusers->get_user_by_pid();//获取所有主管用户信息以及没有主管用户的信息
		$op_users=$this->myusers->get_user_by_role_id(5);//获取所有op用户信息
		if($m_id)
		{
			$data['all_users']=$all_users;
			$data['level_users']=$level_users;
			$data['op_users']=$op_users;
			$data['m_rs']=$this->cm->get_one($m_id);//获取当前结果集
			$data['m_pid']=$this->cm->get_info_by_pid();//依赖模块信息
			//$data['server_rs']=$this->ms->get_rs('m_id',$m_id);服务器模块综合之后，不需要这个东西了
			$data['devloper_rs']=$this->md->get_rs('m_id',$m_id);
			$data['rely_rs']=$this->mr->get_rs('m_id',$m_id);
			$data['test_rs']=$this->mt->get_rs('m_id',$m_id);
			$data['title']="修改服务";
			$this->load->view('codeonline_models/edit',$data);
		}
		else
		{
			$m_pid=$this->cm->get_info_by_pid();
			$this->load->view('codeonline_models/add',array('title'=>'新增服务','m_pid'=>$m_pid,'all_user'=>$all_users,'level_users'=>$level_users,'op_users'=>$op_users));
		}
	}
	/**
	 * 保存模块信息,保存开发者信息，保存服务器信息，保存依赖模块信息，保存测试者信息
	 * 如果存在m_id则是修改，否则添加。
	 */
	public function save()
	{
		//保存模块信息
		$datamodel['pid']=$this->input->post('pid');
		$datamodel['m_name']=$this->input->post('m_name');
		$datamodel['m_type']=$this->input->post('m_type');
		$datamodel['m_online']=$this->input->post('m_online');
		$datamodel['op_id']=$this->input->post('op_id');
		$datamodel['m_head']=$this->input->post('m_head');
		$datamodel['m_addtime']=time();
		$datamodel['m_adduser_id']=$this->user_id;
		$data_devloper['m_devloper']=$this->input->post('m_devloper');
		$data_tester['m_tester']=$this->input->post('m_tester');
		//$data_mserver['m_server']=$this->input->post('m_server'); 废弃的业务逻辑
		$data_relymodel['m_relymodel']=$this->input->post('m_relymodel');
		$m_id=$this->cm->save($datamodel);
		if($m_id)
		{
			//保存开发者，服务器，测试者，依赖模块信息
			//$this->ms->insert_more($data_mserver['m_server'],$m_id);//废弃的业务逻辑
			$this->mr->insert_more($data_relymodel['m_relymodel'],$m_id);
			$this->md->insert_more($data_devloper['m_devloper'],$m_id);
			$this->mt->insert_more($data_tester['m_tester'],$m_id);
			echo json_encode(array('status'=>1,'msg'=>'数据写入成功'),TRUE);
		}
		else
		{
			log_message('error','保存信息失败！');
			echo json_encode(array('status'=>0,'msg'=>'数据写入失败'),TRUE);
			 exit;
		}
	}
	/**
	 * 由于涉及到的更新操作比较多，所以把保存个更新文件的方法分离
	 * 更新时，的保存操作。
	 */
	public function update($m_id)
	{
		$datamodel['pid']=$this->input->post('pid');
		$datamodel['m_name']=$this->input->post('m_name');
		$datamodel['m_type']=$this->input->post('m_type');
		$datamodel['m_online']=$this->input->post('m_online');
		$datamodel['op_id']=$this->input->post('op_id');
		$datamodel['m_head']=$this->input->post('m_head');
		$datamodel['m_addtime']=time();
		$datamodel['m_adduser_id']=$this->user_id;
		$data_devloper['m_devloper']=$this->input->post('m_devloper');
		$data_tester['m_tester']=$this->input->post('m_tester');
		//$data_mserver['m_server']=$this->input->post('m_server');废弃的业务逻辑
		$data_relymodel['m_relymodel']=$this->input->post('m_relymodel');
		$this->cm->save($datamodel,$m_id);
			//保存开发者，服务器，测试者，依赖模块信息
		//$this->ms->update_more($data_mserver['m_server'],$m_id); 废弃的业务逻辑
		$this->mr->update_more($data_relymodel['m_relymodel'],$m_id);
		$this->md->update_more($data_devloper['m_devloper'],$m_id);
		$this->mt->update_more($data_tester['m_tester'],$m_id);
		echo json_encode(array('status'=>1,'msg'=>'数据更新成功'),TRUE);
	}
	/**
	 *删除操作
	 *改变当前模块的装填
	 */	
	public function delete($m_id)
	{
		$data['status']=-1;
		$data['change_time']=time();
		if($this->cm->save($data,$m_id))
		{
			echo json_encode(array('status'=>1,'msg'=>'删除成功！'));
		}
		else
		{
			echo json_encode(array('status'=>0,'msg'=>'删除 失败！'));
		}
	}
}