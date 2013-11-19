<?php
class codeonline_files_model extends MY_Model
{
	protected  $_table='codeonline_files';
	protected $primary_key='file_id';
	public function __construct()
	{
		parent::__construct();
	}
	public function delete($apply_id)
	{
		return $this->db->delete($this->_table,array('apply_id'=>$apply_id));
	}
}