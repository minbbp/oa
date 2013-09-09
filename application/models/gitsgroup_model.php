<?php
/**
 * git用户组模型
 * @author minbbp
 *
 */
class Gitsgroup_model extends CI_Model
{
	private $table='git_groups';
	public function __construct()
	{
		parent::__construct();
	}
	public function find_one($group_id)
	{
		$grouprs=$this->db->get_where($this->table,array('group_id'=>$group_id));
		return $grouprs->row_array();
	}
	/**
	 * 保存或者更新操作
	 * @param array $data 要持久化的数据
	 * @param int $group_id  主键
	 * @return boolean 如果成功 返回true 否则返回false
	 */
	public function  save($data,$group_id=NULL)
	{
		if($group_id)
		{
			$savers=$this->db->update($this->table,$data,array('group_id'=>$group_id));
		}
		else
		{
			$this->db->insert($this->table,$data);
			$savers=$this->db->insert_id();
		}
		return $savers;
	}
	/**
	 * 数据表的列表显示，提供列表显示功能，分页显示，每页显示五条
	 */
	public function alllist($num,$offset)
	{
		$query=$this->db->query("select * from $this->table,users where users.id=$this->table.group_creator order by group_id desc limit $offset,$num");
		return  $query->result_array();
	}
	public function alllist_count()
	{
		return $this->db->count_all($this->table);
	}
	/**
	 * 获取所有的用户组以及用户组主键，前提是该用户组已经开通可以使用
	 */
	public function get_all()
	{
		return $this->db->query("select group_id,group_name from $this->table where group_state=1")->result_array();
	}
}