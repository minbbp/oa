<?php
/**
 *  op操作控制类，其中op的操作是系统分发过来的。也就是走流程过来的
 * @author minbbp
 *
 */
class Group_ops_model extends CI_Model
{
	private $table='group_ops';
	public  function __construct()
	{
		parent::__construct();
	}
	public  function save($data,$gop_id=NULL)
	{
		if($gop_id)
		{
			return $this->db->update($this->table,$data,array('gop_id'=>$gop_id));
		}
		else
		{
			return $this->db->insert($this->table,$data);
		}
	}
	/**
	 * 显示要操作的信息列表，每个op都可以操作，凡是出现在op表中的数据都是通过审核的数据，都需要处理。
	 */
	public function alllist($offset,$num)
	{
		return $this->db->query("select group_name,realname,gop_state,gop_id from $this->table,users,git_groups where $this->table.change_id=users.id and $this->table.group_id=git_groups.group_id order by gop_id desc limit $offset,$num")->result_array();
	}
	public function alllist_count()
	{
		return $this->db->query("select gop_id from $this->table")->num_rows();
	}
	/**
	 * 获取一个申请操作的详细信息
	 */
	public function get_op_info($gop_id)
	{
		$gop_rs=$this->db->query("select * from $this->table where gop_id=$gop_id")->row_array();
		$group_rs=$this->db->query("select * from git_groups where group_id=$gop_rs[group_id]")->row_array();
		$userinfo=$this->db->query("select * from users where id=$gop_rs[change_id]")->row_array();
		$gitaccount=$this->db->query("select g.git_account from git_groups_user as u,gits as g where u.git_account=g.git_id  ")->result_array();
		$data['grop_rs']=$gop_rs;
		$data['group_rs']=$group_rs;
		$data['userinfo']=$userinfo;
		$data['gitaccount']=$gitaccount;
		return $data;
	}
	/**
	 * 获取单条的信息
	 */
	public function get_one($gop_id)
	{
		return $this->db->query("select * from $this->table  where gop_id='$gop_id'")->row_array();
	}
}