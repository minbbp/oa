<?php
class M_relymodel_model extends MY_Model
{
	protected  $_table='m_relymodel';
	protected  $primary_key='rely_id';
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 保存依赖模块信息
	 */
	public function insert_more($str,$m_id)
	{
		$data=array();
		foreach (explode(',', $str) as $value)
		{
			$tmp['m_id']=$m_id;
			$tmp['rely_name']=$value;
			$data[]=$tmp;
		}
		return $this->db->insert_batch($this->_table,$data);
	}
	/**
	 * 批量更依赖模块信息
	 * 先删除就得信息，然后更新新的信息
	 */
	public function update_more($str,$m_id)
	{
		$this->db->delete($this->_table,array('m_id'=>$m_id));
		$this->insert_more($str, $m_id);
	}
	/**
	 *  通过一个模块的m_id 获取与之依赖的模块名称
	 *  
	 */
	public function get_relymodel($m_id)
	{
		$sql="select rely_name,m_name from $this->_table,codeonline_model where $this->_table.rely_name=codeonline_model.m_id and $this->_table.m_id=$m_id";
		return $this->db->query($sql)->result_array();
	} 
}
