<?php
/**
 * git账号和git用户组的对应关系表
 * @author minbbp
 */
class Gitsgroupuser_model extends CI_Model
{
	private $table='git_groups_user';
	public function __construct()
	{
		parent::__construct();
	}
	public function delete_more($group_id)
	{
		return $this->db->delete($this->table,array('group_id'=>$group_id));
	}
	public function insert_more($data)
	{
		return $this->db->insert_batch($this->table,$data);
	}
	/**
	 * 获取所有的在指定git组中包含的git账号
	 * @param int $git_id
	 */
	public function get_gits_in_groups_id($group_id)
	{
	  $query=$this->db->query("select gits.git_id,gits.git_account from $this->table ,gits where gits.git_id=$this->table.git_account and $this->table.group_id=$group_id");
	  return $query->result_array();
	}
	/**
	 * 获取指定的指定的用户组不包含的用户
	 * @param groups_id
	 */
	public function get_gits_not_groups_id($group_id)
	{
		$query=$this->db->query("select git_id,git_account from gits where git_id not in(select git_account from $this->table where $this->table.group_id=$group_id)");
		return $query->result_array();
	}
}