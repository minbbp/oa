<?php
class M_test_model extends MY_Model
{
	protected $_table='m_test';
	protected  $primary_key='m_test_id';
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 保存测试者信息
	 */
	public function insert_more($str,$m_id)
	{
		$data=array();
		foreach (explode(',', $str) as $value)
		{
			$tmp['m_id']=$m_id;
			$tmp['test_id']=$value;
			$data[]=$tmp;
		}
		return $this->db->insert_batch($this->_table,$data);
	}
	/**
	 * 批量更测试者信息
	 * 先删除就得信息，然后更新新的信息
	 */
	public function update_more($str,$m_id)
	{
		$this->db->delete($this->_table,array('m_id'=>$m_id));
		$this->insert_more($str, $m_id);
	}
	/**
	 * 通过m_id获取测试者的用户名
	 */
	public function get_tester_by_m_id($m_id)
	{
		$sql="select test_id,realname from m_test,users where m_test.test_id=users.id and m_id=$m_id";
		return $this->db->query($sql)->result_array();
	}
}