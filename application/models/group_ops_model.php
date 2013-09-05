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
	
}