<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 扩展CI_Model ,自行封装了产找单挑数据和保存修改数据的方法。
 * @author minbbp
 * @version 1.0
 */
class MY_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 通过主键查询单条数据
	 */
	public function get_one($primarykey)
	{
		$sql="select * from $this->_table where $this->primary_key='$primarykey'";
		return $this->db->query($sql)->row_array();
	}
	/**
	 * 保存一个结果集
	 */
	public function save($data,$primarykey=null)
	{
			if($primarykey)
			{
				return $this->db->update($this->_table,$data,array("$this->primary_key"=>$primarykey));
			}
			else
			{
				$this->db->insert($this->_table,$data);
				return $this->db->insert_id();
			}
	}
	/**
	 * 通过某一个字段获取值
	 */
	public function get_rs($key,$value)
	{
		return $this->db->get_where($this->_table,array("$key"=>$value))->result_array();
	}
}