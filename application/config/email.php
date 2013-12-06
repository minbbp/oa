<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 | -------------------------------------------------------------------
| EMAIL CONFING
| -------------------------------------------------------------------
| Configuration of outgoing mail server.
| */
$config['protocol']='smtp';
$config['smtp_host']='internal.smtpcloud.sohu.com';
$config['smtp_port']='25';
$config['smtp_user']='postmaster@adrdop-sendmore.sendcloud.org';
$config['smtp_pass']='d2oG5mXj';
/* $config['smtp_host']='ssl://smtp.gmail.com';
$config['smtp_port']='465';
$config['smtp_user']='lbinzxue@gmail.com';
$config['smtp_pass']='2312qqbc'; */
$config['smtp_timeout']='30';
$config['charset']='utf-8';
$config['newline']="\r\n";
$config['mailtype']="html";
$config['wordwrap']=FALSE;
/* End of file email.php */
/* Location: ./system/application/config/email.php */
