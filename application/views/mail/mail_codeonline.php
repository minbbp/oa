<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>邮件通知</title>
<style type="text/css">
tr{background: #d0dafd;}
td{padding-top:15px;padding-left:10px;}
</style>
</head>
<body>
 <div style="font-size: 14px;">
 <h4><?php echo $to_users['realname'];?>,您好！</h4>
 <p>请您审批<?php echo $to_adduser['realname'];?>的上线申请！</p>
 <table>
 <caption>上线涉及信息</caption>
 <tr><td>上线申请人：</td><td><?php echo $to_adduser['realname'];?></td></tr>
  <tr><td>针对需求：</td><td><?php echo $require_row->required_title;?></td></tr>
 <tr><td>git地址：</td><td><?php echo $apply_rs['git_url']?></td></tr>
 <tr><td>git标签：</td><td><?php echo $apply_rs['git_tag']?></td></tr>
 <tr><td>涉及更新内容：</td><td><?php echo $apply_rs['online_description']?></td></tr>
 <tr><td>设计更新配置文件：</td>
 <td>
 <?php 
 echo "<h4>共".count($change_file)."项,如下所示</h4><ul style='list-style-type: none;'>"; 
 foreach ($change_file as $file)
{
 echo "<li>{$file['file_name']} 文件的 {$file['file_item']}项的值由  {$file['file_item_old_value']} &nbsp;修改为 {$file['file_item_new_value']}</li>";
}
echo "</ul>";
 ?>
 </td></tr>
 <tr><td>涉及更新服务器:</td>
 <td> 
 <h4>共涉及<?php echo count($server_rs);?>台服务器</h4>
 <ul style="list-style-type: none;text-align:left;">
 <?php foreach($server_rs as $server):?>
 <li><?php echo long2ip($server['server_ip']);?></li>
 <?php endforeach;?>
 </ul>
 </td></tr>
 <tr><td> 上线时间：</td><td><?php echo $apply_rs['online_time'];?></td></tr>
 </table>
 <p>此邮件系系统自动发送！请不要回复！</p>
 <p>运维平台地址 <a href="http://op.adrd.sohuno.com">http://op.adrd.sohuno.com</a></p>
 </div>
</body>
</html>
