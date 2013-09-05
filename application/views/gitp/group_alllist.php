<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title><?php echo $title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=base_url()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="<?=base_url()?>/bootstrap/js/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
 <div class="span8">
 <div class="page-header">
				<h3>
					<?php echo $title?>
				</h3>
</div>
<table class='table table-bordered table-hover'>
<thead><tr><th>#</th><th>组名</th><th> 创建者</th> <th>账号状态</th> <th>相关操作</th></tr></thead>
<?php foreach ($groups as $group):?>
<tr>
<td><?php echo $group['group_id']?></td>
<td><?php echo $group['group_name']?></td>
<td><?php echo $group['realname']?></td>
<td><?php if($group['group_state']==1){echo "<span class='icon-ok'></span>";}
			else{echo "<span class='icon-remove'></span>";}
	?>
	</td>
<td><?php if($group['group_state']==1)
		{
	$add_url=base_url('index.php/gitgroups/edit/'.$group['group_id']);
	echo "<a class='btn btn-primary' href='$add_url'>加入</a>";
	 }?>
</td>
</tr>
<?php endforeach;?>
</table>
<?php echo $page?>
</div>
</body>
</html>