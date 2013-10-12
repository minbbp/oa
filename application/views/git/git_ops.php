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
    <script src="<?=base_url()?>/bootstrap/layer/layer.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
 <div class="span8">
			<div class="page-header">
				<h1>
					git 认证审批
				</h1>
			</div>
			<table class="table table-bordered">
			<thead><tr><th>#</th><th>申请者</th><th>申请内容</th><th>申请时间</th><th>操作</th></tr></thead>
			<?php foreach($rs as $g):?>
			<tr>
				<td><?php echo $g['gits_opid'];?></td>
				<td><?php echo "<small>".$g[realname]."</small>";?></td>
				<td>
				<?php 
				if($g['apply_type']==0)
				{
					echo "<small class='text-info'>新增git认证</small>";
				}
				else if($g['apply_type']==1)
				{
					echo "<small class='text-success'>git认证新增机器</small>";
				}
				else if($g['apply_type']==2)
				{
					echo "<small class='text-error'>git认证增加git组</small>";
				}
				?>
				</td>
				<td><?php echo date('Y-m-d H:i:s ',$g['btime']);?></td>
				<td>
				<?php 
					echo anchor('git_ops/showinfo/'.$g['gits_opid'],'操作说明','class="showinfo"');
					echo "&nbsp&nbsp|&nbsp;&nbsp;";
					echo anchor('git_ops/pass/'.$g['gits_opid'],'通过','class="pass"');
					echo "&nbsp&nbsp|&nbsp;&nbsp;";
					echo anchor('git_ops/reject/'.$g['gits_opid'],'驳回','class="reject"');
				?>
				</td>
			</tr>
			<?php endforeach;?>
			</table>
			<?php echo $page;?>
</div>
<script type="text/javascript">
	$(function(){
	$(".pass").click(function(){
		var href=$(this).attr('href');
		var time=new Date().getTime();
		  $.layer({
				type:2,
				title:false,
				area:['540px','300px'],
				border:[0],
				bgcolor:'#fff',
				shadeClose: true,
				offset:['20px',''],
				iframe:{src:href+'/'+time},
				close:function(index)
				{
					var sendmsg=layer.getChildFrame('#sendmsg',index).val();
					if(sendmsg==1)
					{
						$("a[href='"+href+"']").parents('tr').remove();
					}
					layer.close(index);
				}
			});
		
		return false;
		}); 
	$(".reject").click(function(){
		var href=$(this).attr('href');
		var time=new Date().getTime();
		  $.layer({
				type:2,
				title:false,
				area:['540px','300px'],
				border:[0],
				bgcolor:'#fff',
				shadeClose: true,
				offset:['20px',''],
				iframe:{src:href+'/'+time},
				close:function(index)
				{
					var sendmsg=layer.getChildFrame('#sendmsg',index).val();
					if(sendmsg==1)
					{
						$("a[href='"+href+"']").parents('tr').remove();
					}
					layer.close(index);
				}
			});
			
		return false;
		});
	$(".showinfo").click(function(){
		var href=$(this).attr('href');
		var time=new Date().getTime();
		$.layer(
				{
					type:2,
					title:false,
					area:['540px','415px'],
					border:[0],
					bgcolor:'#fff',
					shadeClose: true,
					offset:['20px',''],
					iframe:{src:href+'/'+time},
				}
				);
		return false;
		});
	});
</script>
</body>
</html>