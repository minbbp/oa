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
 <div class="span8 offset1">
			<div class="page-header">
				<h3>
					<?php echo $title; ?>
				</h3>
			</div>
			<?php if(empty($apply_rs)):echo "<h4>没有您的审批信息！</h4>";else:?>
			<table class="table table-bordered table-hover">
			<thead>
			<tr><th>#</th><th>申请人</th><th> 模块</th><th>版本</th><th>操作</th></tr>
			</thead>
			<tbody>
			<?php foreach($apply_rs as $apply):?>
			<tr><td><?php echo $apply['a_id'];?></td>
			<td><?php echo $apply['realname'];?></td>
			<td><?php echo $apply['m_name'];?></td>
			<td><?php echo $apply['git_tag'];?></td>
			<td>
			<?php 
					echo anchor('codeonline_op/back/'.$apply['a_id']."/",'退回','class="back"');
					echo "&nbsp;|&nbsp;";
					echo anchor('codeonline_op/pass/'.$apply['a_id']."/",'已上线','class="pass"');
					echo "&nbsp;|&nbsp;";
					echo anchor('codeonline_op/reject/'.$apply['a_id']."/",'驳回','class="reject"');
					echo "&nbsp;|&nbsp;";
					echo anchor('codeonline/show/'.$apply['apply_id']."/",'查看');
			?>
			</td>
			</tr>
			<?php endforeach;?>
			</tbody>
			<tfoot></tfoot>
			</table>
			<?php echo $page;endif;?>
</div>
<!-- javascript  -->
<script type="text/javascript">
	$(function(){
		//退回操作
		$(".back").click(function(){
			var href=$(this).attr('href');
			var time=new Date().getTime();
			$.get(href,{time:time},function(data){
				if(data.status==1)
				{
					$("a[href='"+href+"']").parents('tr').remove();
					layer.alert(data.msg,9,'');
				}
				else
				{
					layer.alert(data.msg,8,'');
				}
				},'json');
			return false;
			});
		//已上线操作 
		$(".pass").click(function(){
			var href=$(this).attr('href');
			var time=new Date().getTime();
			$.get(href,{time:time},function(data){
				if(data.status==1)
				{
					$("a[href='"+href+"']").parents('tr').remove();
					layer.alert(data.msg,9,'');
				}
				else
				{
					layer.alert(data.msg,8,'');
				}
				},'json');
			return false;
			}); 
		//驳回操作
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

	});
</script>
</body>
</html>