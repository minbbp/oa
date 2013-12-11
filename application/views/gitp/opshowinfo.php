<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title>运维OA_login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=base_url()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="<?=base_url()?>/bootstrap/js/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
 <div class="span6"  style="margin-left:10px">
			<div class="page-header">
				<h3>
					git组新建说明
				</h3>
			</div>
<p style="line-height: 20px;font-size:16px;">
申请人：<?php echo $userinfo['username']?> </p>
<p style="line-height: 20px;font-size:16px;">
请新增git组：<?php echo "<span class='text-error'>".$group_rs['group_name']."</span>";?>
</p>
<p style="line-height: 20px;font-size:16px;">
该git用户组人员为：<?php foreach ($alluser as $user){echo "<span class='text-error'>".$user['username']."&nbsp;&nbsp;</span>";}?>
</p>
<p style="line-height:10px;margin-top:10px;color:#ccc;">创建好git组，加入完git组用户之后请点击“通过”，系统会发送邮件通知相关人员</p>
</div>
</body>
</html>