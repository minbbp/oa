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
<thead><tr><th>#</th><th>组名</th><th>申请者</th> <th>审核状态</th><th>相关操作</th></tr></thead>
<?php foreach ($groups as $group):?>
<tr>
<td><?php echo $group['group_id']?></td>
<td><?php echo $group['group_name']?></td>
<td><?php echo $group['realname']?></td>
<td>
 <?php 
 if($group['gcre_state']==0)
 {echo "<span class='muted'>未审批</span>";}
 elseif($group['gcre_state']==1)
{ echo " <span class='text-success'>通过</span>";}
else
{
	echo "<span class='text-error'>驳回</span>";
}
?>
</td>
 <td>
 <?php 
 $url=base_url('index.php/groupcreator/edit/'.$group['gcre_id']);
 if($group['gcre_state']==0)
						{
							echo " <a class='btn btn-primary' href='$url'>审核</a>";
						}
?>
 </td>
</tr>
<?php endforeach;?>
</table>
<?php echo $page?>
</div>
</body>
</html>