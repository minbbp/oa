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
					我的审批git 账号列表 
				</h3>
			</div>
			<div id="showgroupinfo">
			</div>
			<table class="table table-bordered table-hover">
			<thead><tr><th>#</th><th>申请人<th style="width:20%">git账号</th><th>所属组</th> <th>审批</th><th>op操作</th><th>账号状态</th><th>申请时间</th><th style="width:80px;">操作</th></tr></thead>
			<tbody>
			<?php foreach($gits->result() as $git):?>
			<tr class="tr<?=$git->git_id?>"><td><?=$git->git_id?></td> 
			<td><?=$git->username?></td>
			<td><?php echo $git->git_account?></td>
			 <td><?php echo anchor('#','查看',"class=showgroup data=$git->add_datagroups")?></td>
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
				echo "<span class='muted'>未审批</span>";
			}
			?>
			</td>
			<td>
			<?php 
				if($git->op_state==-2 ||$git->op_state==-11)
				{
					echo  "<span class='text-error'>操作失败</span>";
				}
				else if($git->op_state==2||$git->op_state==11)
				{
					echo "<span class='text-success'>操作成功</span>";
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
				echo "<span class='text-success'>可用</span>";
			}
			else 
			{
				echo "<span class='text-error'>不可用</span>";
			}
			
		
			?></td>
			<td><?=date("Y-m-d H:i:s",$git->addtime)?></td>
			<td>
			<?php 
			//对未审核的可以进行审核操作，对已审核通过的可以禁用该账号
			if($git->h_state==0)
			{
				echo anchor("git/h_apply/$git->git_id","审批","class='btn btn-primary'"); 
			}
			?>
			
			</td>
			</tr>
			<?php endforeach;?>
			</tbody>
			</table>
			<?php echo $page?>
</div>
<script type="text/javascript">
$(function()
		{
	$(".showgroup").click(function(){
			var href=$(this).attr('data');
			$.get("gitgroups",{str:href},function(data){
				data=JSON.parse(data);
				var str='<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><table class="table table-bordered"><tr><th>组名</th><th>创建者</th></tr>';
				$.each(data,function(group,value){
					str+=('<tr><td>'+value.group_name+'</td><td>'+value.realname+'</td></tr>');
					})
				str+='</table></div>'
					 $("#showgroupinfo").empty().append(str);
				});
			return false;
		});
	});
</script>
</body>
</html>
