<?php
//给主管，以及op，以及申请者发送邮件通知
?>
<html>
<head>
<meta charset="utf-8">
<title>git 账号的工作流程单</title>
</head>
<body>
<h2>用户<?php echo $add_userinfo['realname']?>的git账号申请处理记过工作流程单</h2>
 <table>
 <tr>
 <th>申请者</th>
 <th>申请时间</th>
 <?php if(!empty($level)):?>
 <th>主管</th> <th>主管审批</th><th>审批时间</th>
 <?php endif;?>
 <th>操作者</th><th>操作结果</th><th>操作时间</th></tr>
  <tr>
  <td><?php echo $add_userinfo['realname']?></td>
  <td><?php echo date("Y-m-d H:i:s",$git['addtime'])?></td>
  <?php if(!empty($level)):?>
  <td><?php echo $level['realname']?></td>
  <td><?php if($git['h_state']==1 ||$git['h_state']==10 ){echo " 审核通过";}else{echo "驳回";}?></td>
  <td><?php echo date("Y-m-d H:i:s",$git['h_time'])?></td>
  <?php endif;?>
  <td><?php echo $opinfo['realname']?></td>
  <td><?php if($git['git_state']==1){echo "启用";}else {echo "未开启";}?></td>
  <td><?php echo date("Y-m-d H:i:s",$git['operatime'])?></td>
  </tr>
 </table>
 <P> 该邮件系系统自动发送，请不要回复，谢谢！</P>
 <p>祝您工作顺利！</p>
</body>
</html>