<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *对网站的分页参数进行限制
 */
$config['uri_segment'] = 3;
//启用显示当前页码数,默认显示是10条记录
$config['per_page']=10;
$config['next_link']='下一页';
$config['prev_link']='上一页';
$config['first_link'] = '第一页';
$config['last_link']="最后一页";