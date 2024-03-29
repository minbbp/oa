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
<thead><tr><th>模块名称</th><th>类型</th><th>当前版本</th><th>项目负责人</th><th>最进升级时间</th><th>操作</th></tr></thead>
 <tbody>
 <?php foreach ($re_rs as $r):?>
 <tr>
 <td><?php echo $r['m_name'];?></td>
 <td><?php if($r['m_type']==1):?><span class="label label-success">线上</span><?php else:?><span class="label label-important">线下</span><?php endif;?></td>
 <td><?php  echo $r['m_online']?>
 </td>
 <td><?php echo $r['realname'];?></td>
 <td>
 <?php echo date("Y-m-d",$r['change_time']==""?$r['m_addtime']:$r['change_time']);?>
 </td>
 <td>
 <?php echo anchor('codeonline/apply/'.$r['m_id'],'上线申请');?>&nbsp;&nbsp;|&nbsp;&nbsp;
 <?php echo anchor('codeonline/apply/'.$r['m_id'].'/1','紧急上线申请');?>
 </td>
 </tr>
 <?php endforeach;?>
 </tbody>
</table>
<?php echo $page;?>
<p> <?php echo anchor('codeonline/index','&lt;&lt;返回','class="btn span2"');?></p>
</div>
<script type="text/javascript">
$(function(){
//开始和暂停
	$('.delete').click(function(){
		var href=$(this).attr('href');
		var now=$(this);
		$.get(href,{time:new Date().getTime()},function(data){
			if(data.status==1)
			{
				layer.alert(data.msg,9,'成功提示！');
				now.parents('tr').remove();
			}
			else
			{
				layer.alert(data.msg,8,'错误提示！');
			}
			},'json');
		return false;
		});
	//$('tr td:eq(1) span').hover(function(){$(this).css('cursor','pointer');});
});
</script>
</body>
</html>