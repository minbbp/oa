<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title>运维OA_git账号申请回复邮件</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=base_url()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="<?=base_url()?>/bootstrap/js/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
   
   <div class="container">
	<div class="row">
		<div class="span12">
			<div class="page-header">
				<h1>
					<strong>git 账号申请回复邮件</strong>
				</h1>
			</div>
			<p>
				欢迎您使用运维OA系统，您的git账号申请
				<?php 
				if($gits['git_state']==2)
				{echo " 成功！您的账号为：$gits[git_account]";}
				else if($gits['git_state']==-1)
				{echo "失败!失败原因为： $gits[git_description]";}
				?>
				
			</p>
			<p> 该邮件为系统发送邮件，请不要回复，谢谢！</p>
			<p>祝您工作顺利！</p>
			<p>
				操作人员：<?php echo $oper_user->row_array()['email'];?> ，时间:<?php echo date("Y-m-d H:i:s",$gits['operatime'])?>
			</p>
		</div>
	</div>
</div>
   
  </body>
  </html>