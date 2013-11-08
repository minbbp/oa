<?php
/**
 * 代码上线，对代码上线进行处理，主要包含模块列表显示，以及针对特定的模块进行上线申请。
 * @author minbbp
 * @version 1.0 2013.11.6
 */
class Codeonline extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('codeonline_model','cm',TRUE);
		$this->load->model('M_server_model','ms',TRUE);//载入服务器模型
		$this->load->model('M_test_model','mt',TRUE);//载入服务器模型
	}
	/**
	 * 显示所有的父级模块，以及对应父级下的自己模块
	 * @param number $pid
	 * @return 返回一个显示模块的视图
	 */
	public  function index()
	{
		$config['total_rows']=$this->cm->total_alllist();
		$offset=intval($this->uri->segment(3));
		$re_rs=$this->cm->alllist($offset,PER_PAGE);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$this->load->view('codeonline/index',array('re_rs'=>$re_rs,'page'=>$page,'title'=>'上线模块'));
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
		$this->load->view('codeonline/child',array('re_rs'=>$re_rs,'page'=>$page,'title'=>'子模块'));
	}
	/**
	 *  上线申请表单，需要查询出测试人员和对应的服务器列表
	 *  @param  number $m_id 对应模块的主键
	 */
	public function apply($m_id)
	{
		$data['title']="代码上线申请";
		$data['server_rs']=$this->ms->get_rs('m_id',$m_id);
		$data['tester_rs']=$this->mt->get_tester_by_m_id($m_id);
		$data['m_id']=$m_id;
		$this->load->view('codeonline/apply',$data);
	}
	/**
	 * 保存用户提交的上线申请
	 * @param number $m_id
	 */
	public function save($m_id)
	{
		echo "<pre>";
		var_dump($_POST);
	}
}