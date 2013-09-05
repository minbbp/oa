<?php
/**
 * 用户的，设计到的相关信息，主要是为了获取用户的邮箱以及真实姓名
 */
class Users_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function get_useremail_by_id($id)
	{
		$this->db->select('username,email,realname,pid,level');
		$query=$this->db->get_where('users',array('id'=>$id));
		return $query->row_array();
	}
}