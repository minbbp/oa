<?php
/**
 * 用户条操作成功之后，显示相关信息，之后进行页面条状
 */
if(!function_exists('message'))
{
	/**
	 * @param array $data,传递给视图的数据
	 * $data=array('content'=>'','title'=>'','url'=>'',time=>'');
	 * @param string $view，渲染指定的视图文件
	 */
	function message($data,$view)
	{
		$_CI=&get_instance();
		$_CI->load->view($view,$data);
	}
}