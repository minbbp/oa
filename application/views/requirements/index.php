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
     <script src="<?=base_url()?>/bootstrap/layer/layer.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
 <div class="span8 offset1">
 <div class="page-header">
				<h3>
					<?php echo $title?>
				</h3>
</div>
<table class="table table-bordered">
<thead><tr><th>需求编号</th><th>需求标题</th><th>需求状态</th><th>添加人员</th><th>操作</th></tr></thead>
 <tbody>
 <?php print_r($data);?>
 <?php foreach ($re_rs as $r):?>
 <tr>
 <td><?php echo $r['required_no'];?></td>
 <td><span title="<?php echo  $r['re_description'];?>" data-toggle="tooltip" class='showinfo'><?php echo $r['required_title'];?></span></td>
 <td><?php  if($r['re_status']==0)
 			{
 				echo "<span class='label label-important status'>暂停</span>";
 			}
 			else if($r['re_status']==1)
			{
				echo "<span class='label label-success status'>进行中</span>";
			}
 	?>
 </td>
 <td><?php echo $r['realname'];?></td>
 <td>
 <?php  if($r['re_status']==0)
 			{
 				//echo "<a href='requirements/change_status' class='label label-success'>开始</a>";
 				echo anchor('requirements/change_status/'.$r['required_id'].'/1','开始','class="start"');
 			}
 			else if($r['re_status']==1)
			{
				echo anchor('requirements/change_status/'.$r['required_id'].'/0','暂停','class="stop"');
			}
			echo "&nbsp;|&nbsp;";
			echo anchor('requirements/edit/'.$r['required_id'].'','编辑');
			echo "&nbsp;|&nbsp;";
			echo anchor('requirements/delete/'.$r['required_id'].'','删除','class="delete"');
 	?>
 </td>
 </tr>
 <?php endforeach;?>
 </tbody>
</table>
<?php echo $page;?>
<p> <?php echo anchor('requirements/edit','新增需求','class="btn"');?></p>
</div>
<script type="text/javascript">
$(function(){
//开始和暂停
	$('.start,.stop').click(function(){
		var href=$(this).attr('href');
		var now=$(this);
		$.get(href,{time:new Date().getTime()},function(data){
			data=JSON.parse(data);
			if(data.status==1)
			{
				layer.alert(data.msg,9,'成功提示!');
				if(now.is('.start'))
				{
					now.parents('tr').children('td').eq(2).children('span').html('进行中').removeClass('label-important').addClass('label-success');
					href=href.slice(0,-1)+0;
					now.removeClass('start').addClass('stop').text('暂停 ').attr('href',href);
				}
				else if(now.is('.stop'))
				{
					now.parents('tr').children('td').eq(2).children('span').html('暂停').removeClass('label-success').addClass('label-important');
					href=href.slice(0,-1)+1;
					now.removeClass('stop').addClass('start').text('开始 ').attr('href',href);
				}
			}
			else
			{
				layer.alert(data.msg,8,'错误提示！');
			}
			});
		return false;
		});
	$('.delete').click(function(){
		var href=$(this).attr('href');
		var now=$(this);
		$.get(href,{time:new Date().getTime()},function(data){
			data=JSON.parse(data);
			if(data.status==1)
			{
				layer.alert(data.msg,9,'成功提示！');
				now.parents('tr').remove();
			}
			else
			{
				layer.alert(data.msg,8,'错误提示！');
			}
			});
		return false;
		});
	//$('tr td:eq(1) span').hover(function(){$(this).css('cursor','pointer');});
	$('.showinfo').hover(function(){ $(this).css('cursor','pointer').tooltip('show');},function(){$(this).tooltip('hide');});
});
</script>
</body>
</html>