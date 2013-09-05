<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 |--------------------------------------------------------------------------
 |这个项目的表单验证规则
 |-------------------------------------------------------------------------
 |整个OA项目的表单验证规则，每张数据表对应一个验证规则，如果一个表单有多个验证规则，加以区分
 |
 |gits 表gits的验证规则
 |
 |
 |
*/
$config=array(
	
		'gits'=>array(),
		'gitgroups'=>array(
							array(
									'field'=>'group_name',
									'label'=>'git用户组名',
									'rules'=>'required|min_length[6]|max_length[32]'
									),
							array(
								'field'=>'group_description',
								'label'=>'组描述',
								'rules'=>'required|min_length[20]|max_length[255]'
									
									)
			),
);