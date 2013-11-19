<?php
class M_devloper_model extends MY_Model
{
	protected  $_table='m_devloper';
	protected  $primary_key='m_devloper_id';
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 保存开发者信息
	 */
	public function insert_more($str,$m_id)
	{
		$data=array();
		foreach (explode(',', $str) as $value)
		{
			$tmp['m_id']=$m_id;
			$tmp['devloper_id']=$value;
			$data[]=$tmp;
		}
		return $this->db->insert_batch($this->_table,$data);
	}
	/**
	 * 批量更开发者信息
	 * 先删除就得信息，然后更新新的信息
	 */
	public function update_more($str,$m_id)
	{
		$this->db->delete($this->_table,array('m_id'=>$m_id));
		$this->insert_more($str, $m_id);
	}
}