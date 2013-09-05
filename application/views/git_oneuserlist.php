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
					用户 <?=$userinfo['username']?>的git 账号列表 
				</h3>
			<p>
			<?php echo form_open('/git/git_user_search/',array('class'=>'form-search'))?>
  				<input type="text" name="username" placeholder="输入用户名进行搜索" class="input-medium search-query">
  				<button type="submit" class="btn">搜索</button>
			
			<?php echo form_close();?>
			</p>			
			</div>
			<table class="table table-bordered table-hover">
			<thead><tr><th>#</th><th>git账号</th> <th>账号状态</th><th>申请时间</th><th width='140px'>操作</th></tr></thead>
			<tbody>
			<?php foreach($all_gits as $git):?>
			<tr class="tr<?=$git->git_id?>"><td><?=$git->git_id?></td> 
			
			<td><?php echo $git->git_account?></td>
			<td>
			<?php 
			if($git->git_state==1)
			{
				echo "<span class='icon-ok'></span>";
			}
			else if($git->git_state==2)
			{
				echo "<span class='icon-ban-circle'></span>";
			}
			else
			{
				echo "<span class='icon-remove'></span>";
			}
			?></td>
			<td><?=date("Y-m-d H:i:s",$git->addtime)?></td>
			<td>
			<?php 
			if($git->git_state!=2){
			 if(($git->h_state==1 || $git->h_state==10) && $git->git_state==0)
			{
				echo "<a href='#' class='btn btn-primary chuli' id='$git->git_id'>处理</a>&nbsp;&nbsp;";
			}
			
			if( $git->git_state==1 && $git->h_state!=-1)
			{
				echo "<a href='#' class='btn btn-danger forbid' id='$git->git_id'>禁用</a>&nbsp;&nbsp;";
				echo "<a href='#' class='btn btn-danger delete' id='$git->git_id'>删除</a>&nbsp;&nbsp;";
				
			}
			else if($git->git_state==-2 )
			{
				echo "<a href='#' class='btn btn-danger delete' id='$git->git_id'>删除</a>&nbsp;&nbsp;";
			}
			}
			?>
			
			</td>
			</tr>
			<?php endforeach;?>
			</tbody>
			</table>
			<?php echo $page?>
</div>
<!-- 对基本的操作进行ajax处理 -->
<script type="text/javascript">
$(function()
			{
				$(".chuli").click(function(){
					var git_id=$(this).attr("id");
					var time=new Date().getTime();
					$(".tr"+git_id+" td:eq(4)").html("<span class='text-warning'>正在处理！</span>");
					$(this).hide(1000);
					$.get("<?php echo base_url('index.php/git/git_change_state')?>",{timestamp:time,h_state:1,git_id:git_id},function(data){
							alert(data);
							self.location.href="<?php echo base_url('index.php/git/gitupdate/');?>/"+git_id;
						});
					
					return false;
					});
				$(".forbid").click(function(){
					var git_id=$(this).attr("id");
					var time=new Date().getTime();
					$(".tr"+git_id+" td:eq(5)").html("<span class='icon-ban-circle'></span>");
					$(this).hide(1000);
					$.get("<?php echo base_url('index.php/git/git_change_state')?>",{timestamp:time,h_state:2,git_id:git_id},function(data){
						alert(data);
						
					});
				return false;
					});

				$(".delete").click(function(){
					var git_id=$(this).attr("id");
					var time=new Date().getTime();
					$(".tr"+git_id+" td:eq(5)").html("<span class='icon-ban-circle'></span>");
					$(this).hide(1000);
					$.get("<?php echo base_url('index.php/git/git_change_state')?>",{timestamp:time,h_state:3,git_id:git_id},function(data){
						alert(data);
						
					});
				return false;
					});
			});

</script>
</body>
</html>
