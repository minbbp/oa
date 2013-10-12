<?php
/**
 * 主管和运维审批控制模型
 * @author minbbp
 *
 */
class Git_op_level_model extends CI_Model
{
	private $_table='gits_level_op';//对应的数据表明
	private $primary_key='gits_opid';//git 用户主键
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 数据保存
	 */
	public function save($data,$primarykey)
	{
		if($primarykey)
		{
			return $this->db->update($this->_table,$data,array("$this->primary_key"=>$primarykey));
		}
		else
		{
			$this->db->insert($this->_table,$data);
			return $this->db->insert_id();
		}
	}
	/**
	 * 数据查找
	 */
	public function find_one($primarykey)
	{
		return $this->db->get_where($this->_table,array("$this->primary_key"=>$primarykey))->row_array();
	}
	/**
	 * 列表显示
	 */
	public function alllist($offset,$num,$type_id=0,$user_id=0,$state=0)
	{
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->join('gits',"gits.git_id=$this->_table.git_id");
		$this->db->join('users','users.id=gits.add_user');
		$this->db->where('type_id',$type_id);
		if($user_id!=0)
		{
			$this->db->where('user_id',$user_id);
		}
		$this->db->where('state',$state);
		$this->db->limit($num,$offset);
		return $this->db->get()->result_array();
		
	}
	public function count_alllist($type_id=0,$user_id=0,$state=0)
	{
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->join('gits',"gits.git_id=$this->_table.git_id");
		$this->db->where('type_id',$type_id);
		if($user_id!=0)
		{
			$this->db->where('user_id',$user_id);
		}
		$this->db->where('state',$state);
		return $this->db->count_all_results();
	}
	/**
	 * 返回个个运维人员的操作数
	 */
	public function total()
	{
		$sql="select user_id,count(*)total,realname from gits_level_op ,users where type_id=1 and users.id=gits_level_op.user_id group by user_id order by total desc";
		return $this->db->query($sql)->result_array();
	}
}