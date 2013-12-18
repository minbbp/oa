<?php
class Git_key_model extends CI_Model
{
	private $_table='git_sshkey';
	private $pk='key_id';
	public function __construct()
	{
		parent::__construct();
	}
	public function save_batch($data,$key=null)
	{
		if($key)
		{
			return $this->db->update_batch($this->_table,$data,$key);
		}
		else
		{
			$rs= $this->db->insert_batch($this->_table,$data);
			//log_message('error',$this->db->last_query());
			return $rs;
		}
	}
	public function save($data,$pk=null)
	{
		if($pk)
		{
			$this->db->where($this->pk,$pk);
			return $this->db->update($this->_table,$data);
		}
		else
		{
			return $this->db->insert($this->_table,$data);
		}
	}
	public function find_one($pk)
	{
		$this->db->where($this->pk,$pk);
		return $this->db->get($this->_table)->row_array();
	}
	/**
	 * 通过git_id 获取属于git_id的所有机器
	 */
	public function getinfo_by_git_id($git_id)
	{
		$this->db->where('git_id',$git_id);
		return $this->db->get($this->_table)->result_array();
	}
	/**
	 * 上边的一个方法的升级版本，增加了状态的改变
	 * @param int $git_id
	 * @param number $state
	 */
	public function getinfo_by_git_id_state($git_id,$state=0)
	{
		$sql="select * from $this->_table where git_id=$git_id and key_state=$state";
		return $this->db->query($sql)->result_array();
	}
}