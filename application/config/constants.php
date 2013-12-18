<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

//定义几个常用的邮箱号码
define('ADRD_EMAIL_ONE','wb-zhibinliu@sohu-inc.com');//git账号部分处理人员
define('ADRD_EMAIL_TWO','wb-zhibinliu@sohu-inc.com');
define('ADRD_EMAIL_OTHER','wb-zhibinliu@souhu-inc.com');
define('ADRD_OP_TWO','wb-wennanma@sohu-inc.com');//服务器模块运维人员
define('SYS_EMAIL','MessageCenter@sohu-inc.com');//发件mail
define('SYS_EMAILNAME','MessageCenter@sohu-inc.com');//发件人
//配置几个模块的文件夹显示目录
define('CODE_ONLINE','codeonline');
// 分页常量显示
define('PER_PAGE',10);
//紧急上线审批主管定义
define('UN_UNGENT_LEVEL',1);
/* End of file constants.php */
/* Location: ./application/config/constants.php */