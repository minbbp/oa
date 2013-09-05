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
 <div class="span8">
			<div class="page-header">
				<h3>
					我的git 账号列表 
				</h3>
			</div>
			<table class="table table-bordered table-hover">
			<thead><tr><th>#</th><th>git账号</th> <?php if($user['pid']!=0):?><th>审核状态</th><?php endif;?><th>op处理状态</th><th>账号状态</th><th>申请时间</th></tr></thead>
			<tbody>
			<?php foreach($all_gits as $git):?>
			<tr><td><?=$git->git_id?></td> 
			<td><?php echo $git->git_account?></td>
			<?php if($user['pid']!=0):?>
			<td>
			<?php 
			if($git->h_state==-1)
			{
				echo "<span class='text-error'>被驳回</span>";
			}
			
			else if($git->h_state==1)
			{
				echo "<span class='text-success'>通过</span>";
			}
			else
			{
				echo "<span class='muted'>未审核</span>";
			}
		
			?>
			</td>
			<?php endif;?>
			<td>
			<?php 
				if($git->op_state==-2 ||$git->op_state==-11)
				{
					echo  "<span class='icon-remove'></span>";
				}
				else if($git->op_state==2||$git->op_state==11)
				{
					echo "<span class='icon-ok'></span>";
				}
				else
				{
					echo "<span class='muted'>未处理</span>";
				}
			?>
			</td>
			<td>
			<?php 
			
			 if($git->git_state==1)
			{
				echo "<span class='icon-ok'></span>";
			}
			 else if($git->git_state==-2 || $git->git_state==2)
			{
				echo "<span class='icon-ban-circle'></span>";
			}
			else 
			{
				echo "<span class='icon-remove'>不可用</span>";
			}
			
		
			?></td>
			<td><?=date("Y-m-d H:i:s",$git->addtime)?></td>
			</tr>
			<?php endforeach;?>
			</tbody>
			</table>
			<?php echo $page?>
</div>
</body>
</html>
