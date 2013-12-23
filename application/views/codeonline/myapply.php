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
			<?php if(empty($codeonlines)): echo "<h4>您暂无上线申请！</h4>";else:?>
			<table class="table table-bordered table-hover">
			<thead><tr><th>#</th><th>模块</th> <th>升级版本</th><th>上线时间</th><th>申请状态</th><th>上线状态</th><th>操作</th></tr></thead>
			<tbody>
			<?php foreach($codeonlines as $c):?>
			<tr><td><small style="font-size:8px;" ><?php echo $c['apply_id']?></small></td> 
			<td><?php echo $c['m_name']; if($c['is_ungent']==1){echo "&nbsp;<label class='label label-important'>急</label>";}?></td>
			<td><small class=""><?php echo $c['git_tag']?></small></td>
			<td>
			<?php 
				echo "<small class='text-error '>{$c['online_time']}</small>";
			?>
			</td>
			<td><?php if($c['myapply_status']==2){echo "<small class='label label-warning'>未提交</small>";}else{echo "<small class='text-success label label-success'>已提交</small>";}?></td>
			 <td><?php if($c['end_state']==0){  echo" <small class='label'>未上线</small>";}else if($c['end_state']==-1){echo "<small class='label label-warning'>驳回</small>";}else{echo "<small class='label label-success'>已成功</small>";} ?></td>
			<td>
			<?php 
			echo anchor('codeonline/show/'.$c['apply_id'],'查看','class="showinfo"');
			
			if($c['myapply_status']==2)
			{
				echo anchor('codeonline/update/'.$c['apply_id']."/".$c['tester_id']."/".$c['m_id'],"&nbsp;|&nbsp;修改");
				echo anchor('codeonline/commit_apply/'.$c['apply_id']."/".$c['tester_id']."/".$c['m_id'],"&nbsp;|&nbsp;提交审批",'class="commit_apply"');
			}?>
			</td>
			</tr>
			<?php endforeach;?>
			</tbody>
			</table>
			<?php echo $page;endif;?>
</div>
<script type="text/javascript">
$(function(){
	//处理点击查看按钮显示，由于展示内容过多取消弹出层内容显示
	
		$('.commit_apply').click(function(){
			var now=$(this);
			var time=new Date().getTime();
			var href=$(this).attr('href');
			//发送get 请求，获取的机器数目
			 $.get(href,{time:time},function(data){
				 layer.alert(data);
				 data=JSON.parse(data);
				 console.log(data);
				if(data.status==1)
				{
					layer.alert(data.msg,9,'成功提示！');
					now.siblings('a:gt(0)').remove();
					now.parents('tr').find('td').eq(4).children('small').removeClass('label-warning').addClass('label-success').text('已提交');
					now.remove();
				}
				else
				{
					layer.alert (data.msg);
				}
			 });
			
			return false;
		});
		
});
</script>
</body>
</html>
