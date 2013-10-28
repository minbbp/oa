<?php	if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 测试方法，看看一个控制器
 * @author minbbp
 *
 */
class Codeonline extends CI_Controller
{
	/**
	 * 重写父类的构造方法，同时添加基本的类库进来
	 */
	private $event;
	public function __construct()
	{
		parent::__construct();
		 $this->load->library('codeonline_event');
	}
	public function save()
	{
		echo "保存了用户数据";
		//print_r($this->event);
		$this->codeonline_event->save();
	}
}