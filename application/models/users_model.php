<?php
/**
 * 用户的，设计到的相关信息，主要是为了获取用户的邮箱以及真实姓名
 */
class Users_model extends CI_Model
{
	private $_table;
	public function __construct()
	{
		parent::__construct();
		$this->_table='users';
	}
	public function get_useremail_by_id($id)
	{
		$this->db->select('username,email,realname,pid,level');
		$query=$this->db->get_where('users',array('id'=>$id));
		return $query->row_array();
	}
	/**
	 *  获取用户以及用户主管信息
	 * @param int $id
	 * @return array
	 */
	public function getuserinfo($id)
	{
		$userinfo=$this->db->query("select *,(roles.name)rolename from $this->_table,roles where $this->_table.id='$id' and roles.id=$this->_table.role_id")->row_array();
		$userdata['userinfo']=$userinfo;
		if($userinfo['pid']!=0)
		{
			$level_id=$userinfo['pid'];
			$levelinfo=$this->db->query("select *,(roles.name)rolename from $this->_table,roles where $this->_table.id='$level_id' and roles.id=$this->_table.role_id")->row_array();
			$userdata['levelinfo']=$levelinfo;
		}
		return $userdata;
	}
	/**
	 * 获取数据表中的全部用户信息
	 * 在申请git组的时候使用到了这个方法。
	 */
	public function get_all_users()
	{
		$sql="select id,username,realname from $this->_table where banned='0' ";
		return $this->db->query($sql)->result_array();
	}
	public function searchuser($username)
	{
		$sql="select id,username,realname,email from $this->_table where username='$username' ";
		return $this->db->query($sql)->row_array();
	}
	/**
	 * 通过用户角色获取用户列表
	 * @param number $role_id 用户角色id
	 */
	public function get_user_by_role_id($role_id)
	{
		return  $this->db->get_where($this->_table,array('role_id'=>$role_id))->result_array();
	}
	/**
	 * 通过用户的pid获取用户的详细信息
	 * @param number $pid 用户的级别信息
	 */
	public function get_user_by_pid($pid=0)
	{
		return  $this->db->get_where($this->_table,array('pid'=>$pid))->result_array();
	}
}