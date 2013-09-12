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
}