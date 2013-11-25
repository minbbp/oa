<?php
class codeonline_apply_table_model extends MY_Model
{
	protected  $_table='codeonline_apply_table';
	protected  $primary_key='apply_id';
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 *  超找出我的的git认证申请
	 * @param int $offset
	 * @param int $num
	 * @param int $user_id
	 */
	public function alllist($offset,$num,$user_id=0)
	{
		if($user_id)
		{
			$sql="select * from $this->_table,users,codeonline_model,codeonline_require  where users.id=$this->_table.apply_user and codeonline_model.m_id=$this->_table.m_id and codeonline_require.required_id=$this->_table.require_id  and $this->_table.apply_user=$user_id  order by $this->_table.$this->primary_key desc limit $offset,$num ";
			return $this->db->query($sql)->result_array();
		}
		else
		{
			$sql="select * from $this->_table,users,codeonline_model,codeonline_require  where users.id=$this->_table.apply_user and codeonline_model.m_id=$this->_table.m_id and codeonline_require.required_id=$this->_table.require_id  order by  $this->_table.$this->primary_key desc limit  $offset,$num ";
			return $this->db->query($sql)->result_array();
		}
		
	}
	public function count_alllist($user_id=0)
	{
		if($user_id)
		{
			$sql="select * from $this->_table,users,codeonline_model,codeonline_require  where users.id=$this->_table.apply_user and codeonline_model.m_id=$this->_table.m_id and codeonline_require.required_id=$this->_table.require_id  and $this->_table.apply_user=$user_id ";
			return $this->db->query($sql)->num_rows();
		}
		else
		{
			$sql="select * from $this->_table,users,codeonline_model,codeonline_require  where users.id=$this->_table.apply_user and codeonline_model.m_id=$this->_table.m_id and codeonline_require.required_id=$this->_table.require_id ";
			return $this->db->query($sql)->num_rows();
		}
	}
	/**
	 * 获取所有的和申请相关的信息：提交的用户信息，依赖模块信息，修改配置文件信息，涉及服务器信息，对应需求信息,项目负责人信息，以及运维人员信息
	 * @param int $apply_id
	 */
	public function showinfo_by_id($apply_id)
	{
		//申请信息
		$apply_row=$this->db->get_where($this->_table,array('apply_id'=>$apply_id))->row_array();
		//对应需求
		$require_row=$this->db->get_where('codeonline_require',array('required_id'=>$apply_row['require_id']))->row_array();
		//配置文件信息
		$config_rs=$this->db->get_where('codeonline_files',array('apply_id'=>$apply_id))->result_array();
		//对应模块信息
		$model_row=$this->db->get_where('codeonline_model',array('m_id'=>$apply_row['m_id']))->row_array();
		//设计更新服务器信息
		//$servers=explode(',', $apply_row['server_update']);
		 $sql="select * from  server where s_id in ('{$apply_row['server_update']}')";
		$server_rs=$this->db->query($sql)->result_array();
		//项目负责人信息
		$head_row=$this->db->get_where('users',array('id'=>$model_row['m_head']))->row_array();
		//运维人员信息
		$op_row=$this->db->get_where('users',array('id'=>$model_row['op_id']))->row_array();
		//测试人员信息
		$tester_row=$this->db->get_where('users',array('id'=>$apply_row['tester_id']))->row_array();
		//申请人员信息
		$apply_users=$this->db->get_where('users',array('id'=>$apply_row['apply_user']))->row_array();
		return array('apply_row'=>$apply_row,'require_row'=>$require_row,'config_rs'=>$config_rs,'model_row'=>$model_row,'server_rs'=>$server_rs,'head_row'=>$head_row,'op_row'=>$op_row,'tester_row'=>$tester_row,'apply_user'=>$apply_users);
	}
	/**
	 * 通过用户的检查申请者和项目负责人是同一个人，
	 * @param number $apply_id
	 * @return  bool 如果是同一个人的话，则返回TRUE ，不是同一个人返回FALSE
	 */
	public function check_head_adduser($apply_id)
	{
		$row_apply=$this->get_one($apply_id);
		$sql="select * from codeonline_model where m_id=$row_apply[m_id] ";
		$row_model=$this->db->query($sql)->row_array();
		return $row_apply['apply_user']==$row_model['m_head'];
		
	}
	public function get_head_by_apply_id($apply_id)
	{
		$row_apply=$this->get_one($apply_id);
		$sql="select * from codeonline_model where m_id=$row_apply[m_id] ";
		return $this->db->query($sql)->row_array();
	}
}
