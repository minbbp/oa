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
<thead><tr><th>#</th><th>组名</th><th> 申请者</th> <th>审批状态</th> <th>相关操作</th></tr></thead>
<?php foreach ($gops as $gop):?>
<tr>
<td><?php echo $gop['gop_id']?></td>
<td><?php echo $gop['group_name']?></td>
<td><?php echo $gop['realname']?></td>
<td><?php if($gop['gop_state']==1){echo "<span class='icon-ok'></span>";}
			else{echo "<span class='icon-remove'></span>";}
	?>
	</td>
<td><?php if($gop['gop_state']==0)
		{
		$add_url=base_url('index.php/groupops/edit/'.$gop['gop_id']);
		echo "<a class='btn btn-primary' href='$add_url'> 处理</a>";
	 }?>
</td>
</tr>
<?php endforeach;?>
</table>
<?php echo $page?>
</div>
</body>
</html>