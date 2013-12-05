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
 <div class="span6" style="margin-left:10px">
	<div class="page-header">
		<h4>加入的用户组信息</h4>
	</div>
	<table class="table table-bordered">
	<thead>
	<tr><th> 所有者</th><th>git组名</th><th>创建时间</th></tr>
	</thead>
	<tbody>
	<?php foreach($groups as $group):?>
	<tr><td><?php echo $group['realname'];?></td><td><?php echo $group['group_name'];?></td><td><?php echo date('Y-m-d',$group['addtime']) ?></td></tr>
	<?php endforeach;?>
	</tbody>
	</table>
</div>
</body>
</html>
