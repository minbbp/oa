<?php
/**
 * 保存服务器模块的model
 * @author minbbp
 *
 */
class M_server_model extends  MY_Model
{
	protected $_table='m_server';
	protected $primary_key='server_id';
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 保存服务器信息
	 */
	public function insert_more($str,$m_id)
	{
		$data=array();
		foreach (explode(',', $str) as $value)
		{
			$tmp['m_id']=$m_id;
			$tmp['server_ip']=ip2long($value);
			$data[]=$tmp;
		}
		return $this->db->insert_batch($this->_table,$data);
	}
	/**
	 * 批量更新服务器信息
	 * 先删除就得信息，然后更新新的信息
	 */
	public function update_more($str,$m_id)
	{
		$this->db->delete($this->_table,array('m_id'=>$m_id));
		$this->insert_more($str, $m_id);
	}
	public function get_server_by_str($str)
	{
		$sql="select * from $this->_table where find_in_set(server_id,'{$str}')";
		return $this->db->query($sql)->result_array();
	}
}