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
 <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">git组详细信息</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    
  </div>
</div>
 <div class="page-header">
				<h3>
					<?php echo $title?>
					<form class="form-search pull-right" action='<?php echo base_url('index.php/gitgroups/search')?>' method='post'>
  					<input type="text" placeholder="输入git组名查找..." id="group_name" name="group_name" class="input-medium search-query">
  					<button type="submit" id='group_submit' class="btn">搜索</button>
				    </form>
				</h3>
</div>
<div></div>
<div>
<p><?php if($showinfo==1)
{
	echo "您搜索的关键词为<b>".$keywords."</b>查询结果如下：<br/><br/>";
}?>
</p>
</div>
<div></div>
<div class="span7">
<ul class="nav nav-pills">
<li class='active'><?php echo anchor('gitgroups/groups/1','可用git组')?></li>
<li><?php echo anchor('gitgroups/groups/-1','禁用git组')?></li>
<li><?php echo anchor('gitgroups/groups/2','删除git组')?></li>
<li><?php echo anchor('gitgroups/groups/0','新申请git组')?></li>
</ul>
</div>
<table class='table table-bordered table-hover'>
<thead><tr><th>#</th><th>组名</th><th> 创建者</th> <th>账号状态</th> <th>申请时间</th><th>用户组成员</th><th>操作</th></tr></thead>
<?php foreach ($groups as $group):?>
<tr>
<td><?php echo $group['group_id']?></td>
<td><?php echo $group['group_name']?></td>
<td><?php echo $group['realname']?></td>
<td><?php if($group['group_state']==1){echo "<span class='icon-ok'></span>";}
			else{echo "<span class='icon-remove'></span>";}
	?>
	</td>
	<td><?php echo date('Y-m-d',$group['addtime'])?></td>
<td>
<?php echo anchor('gitgroups/showuser/'.$group['group_id'],'查看',"title='点击查看' class='showuser' ")?>
</td>
<td>
<?php 
if($group['group_state']==1)
{
	echo anchor('gitgroups/disable/'.$group['group_id'],'禁用','class=disable');
	echo "&nbsp;|&nbsp;";
	echo anchor('gitgroups/delete/'.$group['group_id'],'删除','class=delete');
}else if($group['group_state']==-1)
{
	echo anchor('gitgroups/delete/'.$group['group_id'],'删除','class=delete');
	echo "&nbsp;|&nbsp;";
	echo anchor('gitgroups/restart/'.$group['group_id'],'还原','class=restart');
}else if($group['group_state']==2)
{
	echo anchor('gitgroups/restart/'.$group['group_id'],'还原','class=restart');
}
?>
</td>
</tr>
<?php endforeach;?>
</table>
<?php echo $page?>
</div>
 <script src="<?=base_url()?>/bootstrap/layer/layer.min.js"></script>
<script type="text/javascript">
	$(function(){
		$(".showuser").click(function(){
			var href=$(this).attr("href");
			var time=new Date().getTime();
			$("#myModal .modal-body").empty().load(href,{time:time});
			$("#myModal").modal();
			return false;
			});
		$(".disable").click(function(){
			var href=$(this).attr("href");
			var time=new Date().getTime();
			$.get(href,{time:time},function(data){
					if(data==1)
						{
						layer.alert('禁用git组成功！',9);
						$("a[href='"+href+"']").parents('tr').remove();
						}
						else
						{
						 layer.alert('禁用git组失败，请联系管理员！',8);
						}
				});
			return false;
			});
		$(".delete").click(function(){
			var href=$(this).attr("href");
			var time=new Date().getTime();
			$.get(href,{time:time},function(data){
					if(data==1)
						{
						layer.alert(' 删除git组成功！',9);
						$("a[href='"+href+"']").parents('tr').remove();
						}
						else
						{
						 layer.alert('删除git组失败，请联系管理员！',8);
						}
				});
			return false;
			});
		$(".restart").click(function(){
			var href=$(this).attr("href");
			var time=new Date().getTime();
			$.get(href,{time:time},function(data){
					if(data==1)
						{
						layer.alert(' 还原git组成功！',9);
						$("a[href='"+href+"']").parents('tr').remove();
						}
						else
						{
						 layer.alert('还原git组失败，请联系管理员！',8);
						}
				});
			return false;
			});
		$("#group_submit").click(function(){
				if($("#group_name").val()=="")
				{
					layer.alert('搜索信息不能为空！',8);
					 return false;
				}
				
			});
		});
	</script>
</body>
</html>