<?php
/**
 * 需求表的模型
 */
class Codeonline_require_model extends CI_Model
{
	private $_table='codeonline_require';//表明
	private $primary_key='required_id';//表主键
	/**
	 * 重写父类的构造方法
	 */
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 显示需求列表,需要联合查询用户表获取用户信息
	 */
	public function alllist($offset,$num)
	{
		$sql="select *,(select realname from users where users.id=$this->_table.re_add_user)realname from $this->_table where re_status!=-1 order by $this->primary_key desc limit $offset,$num";
		return $this->db->query($sql)->result_array();
	}
	/**
	 * 显示列表展示查询方法的条数
	 */
	public function count_alllist()
	{
		$sql="select * from $this->_table where re_status!=-1 order by $this->primary_key desc ";
		return $this->db->query($sql)->num_rows();
	}
	/**
	 * 保存或者修改一条数据
	 * @param $data array 要更新的数据
	 * @param $required_id int 要修改数据的行号
	 * @return boolean if success return TRUE else return false
	 */
	public function save($data,$required_id=null)
	{
		if($required_id)
		{
			return $this->db->update($this->_table,$data,array('required_id'=>$required_id));
		}
		else
		{
			return $this->db->insert($this->_table,$data);
		}
	}
	/**
	 * 查找一条数据，通过id号
	 * @param $required_id int 获取的单行数据
	 * @return array 获取查询结果。一共一行记录
	 */
	public function get_one($required_id)
	{
		$sql="select * from $this->_table where $this->primary_key=$required_id";
		return $this->db->query($sql)->row();
	}
}