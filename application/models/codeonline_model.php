<?php
/**
 * 代码上线模块功能管理
 * @author wb-zhibinliu@sohu-inc.com
 * @version 1.0 2013.10.31
 */
class Codeonline_model extends MY_Model
{
	protected  $_table='codeonline_model';//对应的数据表名
	protected  $primary_key='m_id';//表主键
	public function __construct()
	{
	 parent::__construct();
	}

	 /**
	  * 查询列表显示信息
	  * @param int $offset 分页偏移量
	  * @param int $num  每页页数
	  * @param number $pid 父级分类
	  * @return array 一个包含模块的结果集
	  */
	 public function  alllist($offset,$num,$pid=0)
	 {
	 	//if($pid)
	 	//{
	 		$sql="select *,(select realname from users where users.id=$this->_table.m_head)realname from $this->_table where pid='$pid' and status!=-1 order by $this->primary_key desc limit $offset,$num";
	 	//}
	 	//else
	 	//{
	 		//$sql="select *,(select realname from users where users.id=$this->_table.m_head)realname from $this->_table  where  status!=-1 order by $this->primary_key desc limit $offset,$num";
	 	//}
	 
	 	return $this->db->query($sql)->result_array();
	 }
	 /**
	  * 统计查询有多少条，用于分页显示
	  * @param number $pid
	  * @return number all num_rows
	  */
	public function total_alllist($pid=0)
	{
		//if($pid)
		//{
			$sql="select * from $this->_table where pid=$pid  and status!=-1";
		//}
		//else
		//{
			//$sql="select * from   $this->_table where  status!=-1";
	//	}
		
		return $this->db->query($sql)->num_rows();
	}
	/**
	 * 通过pid,获取模块信息
	 */
	public function get_info_by_pid($pid=0)
	{
		return $this->db->get_where($this->_table,array('pid'=>$pid))->result_array();
	}
	/**
	 * 获取所有新的列表信息
	 * @return  array 获取一个所有子模块的列表
	 */
	public function get_all_list()
	{
		$sql="select $this->primary_key,m_name from $this->_table where pid!=0";
		return $this->db->query($sql)->result_array();
	}
	/*
	 * 根据服务名 取得id
	*/
	public function get_id_by_name($name) 
	{
		$where['m_name'] = $name;
		$res = $this->db->where($where)->get($this->_table);
		$result = current($res->result_array());
		return $result['m_id'] ? $result['m_id'] : array();
	}
	/*
	 * 根据id 查名字
	*/
	public function get_name_by_id($id) 
	{
		$arr = explode(',', $id);
		foreach($arr as $v)
		{
			$where['m_id'] = $v;
			$res = $this->db->where($where)->get($this->_table);
			$result = $res->result_array();
			if($result)
			{
				$results[] = current($result);
			}
			else
			{
				$results = array();
			}
		}
	
		return $results;
	}
	/*
	 * 得到数组 键值   id->名字
	*/
	public function get_alllist()
	{
		$res = $this->db->get($this->_table);
		$result = $res->result_array();
		$arr = array();
		foreach($result as $v)
		{
			$arr[$v['m_id']] = $v['m_name'];
		}
		return $arr;
	}
}