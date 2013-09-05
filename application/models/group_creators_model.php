<?php
/**
 * 保存操作者的审核信息
 * @author minbbp
 *
 */
class Group_creators_model extends CI_Model
{
	private $table="group_creator";
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 创建者持久化
	 * @param array $data
	 * @param int $gcre_id
	 */
	public function save($data,$gcre_id=NULL)
	{
		if($gcre_id)
		{
			return $this->db->update($this->table,$data,array('gcre_id'=>$gcre_id));
		}
		else
		{
			return $this->db->insert($this->table,$data);
		}
	}
}