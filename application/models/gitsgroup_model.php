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
	public function alllist($num,$offset,$user_id=null)
	{
		if($user_id)
		{
			$sql="select *,(select count(*) from git_groups_user where group_id=$this->table.group_id)num from $this->table,users where users.id=$this->table.group_creator and group_creator=$user_id order by group_id desc limit $offset,$num";
		}
		else
		{
			$sql="select * from $this->table,users where users.id=$this->table.group_creator order by group_id desc limit $offset,$num";
		}
		$query=$this->db->query($sql);
		return  $query->result_array();
	}
	public function alllist_count($user_id=null)
	{
		if($user_id)
		{
			$sql="select * from $this->table where group_creator=$user_id";
			return $this->db->query($sql)->num_rows();
		}
		else
		{
			return $this->db->count_all($this->table);
		}
	}
	/**
	 * 获取所有的用户组以及用户组主键，前提是该用户组已经开通可以使用
	 */
	public function get_all()
	{
		return $this->db->query("select group_id,group_name from $this->table where group_state=1")->result_array();
	}
	public function get_rs_by_csv($str)
	{
		return $this->db->query("select group_name,realname from $this->table ,users where users.id=$this->table.group_creator and group_id in ({$str})")->result_array();
	}
	//git组管理使用的方法
	public function m_list($offset,$num,$state=1)
	{
		$sql="select * from $this->table,users where group_state=$state and users.id=$this->table.group_creator limit $offset ,$num";
		return $this->db->query($sql)->result_array();
	}
	public function m_list_count($state=1)
	{
		$sql="select * from $this->table where group_state=$state";
		return $this->db->query($sql)->num_rows();
	}
	// 搜索使用的方法
	public function search($offset,$num,$group_name)
	{
		$sql="select * from $this->table,users where group_name like '%$group_name%' and users.id=$this->table.group_creator limit $offset ,$num";
		return $this->db->query($sql)->result_array();
	}
	public function t_search($group_name)
	{
		$sql="select * from $this->table where group_name like '%$group_name%'";
		return $this->db->query($sql)->num_rows();
	}
	/**
	 * 通过组主键，获取指定的git组创建者
	 */
	public function get_creator_by_group_id($group_id)
	{
		$this->db->select('group_creator');
		$query=$this->db->get_where($this->table,array('group_id'=>$group_id))->row_array();
		return $query['group_creator'];
	}
	/**
	 * 通过git组查询到用户，获取用户的邮件信息
	 * @param string $group_id 用户的git用户组
	 */
	public function get_email_by_group_id($group_id)
	{
		$sql="select  distinct email from $this->table,users where $this->table.group_id in ({$group_id}) and users.id=$this->table.group_creator";
		return $this->db->query($sql)->result_array();
	}
	/**
	 *  通过git_id字符串获取详细信息git组信息，包括git组创建者机器
	 */
	public function getuser_group_by_str($git_str)
	{
		$sql="select   group_id,realname,group_name,email,group_creator,addtime from $this->table,users where $this->table.group_id in ({$git_str}) and users.id=$this->table.group_creator";
		return $this->db->query($sql)->result_array();
	}
	/**
	 *  查找某个用户没有加入的用户组
	 */
	public function get_str_nouser($user_id)
	{
		$group_user_sql="select GROUP_CONCAT(group_id)group_str from git_groups_user where user_id=$user_id";
		 $group_str=$this->db->query($group_user_sql)->row_array();
		 if($group_str['group_str']!="")
		 {
		 	$sql="select group_id,group_name from $this->table where group_id not in({$group_str['group_str']}) and group_state=1";
		 }
		 else
		 {
		 	$sql="select group_id,group_name from $this->table where  group_state=1";
		 }
		
		return $this->db->query($sql)->result_array();
	}
}