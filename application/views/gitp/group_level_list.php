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
 <div class="span8 offset1">
 <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">用户组成员列表</h3>
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
				</h3>
</div>
<table class='table table-bordered table-hover'>
<thead><tr><th>#</th><th>组名</th><th>申请者</th><th>申请时间</th> <th>审核状态</th><th>相关操作</th></tr></thead>
<?php foreach ($groups as $group):?>
<tr>
<td><?php echo $group['group_id']?></td>
<td><?php echo $group['group_name']?></td>
<td><?php echo $group['realname']?></td>
<td><?php echo date('Y-m-d',$group['addtime']);?></td>
<td>
 <?php 
							 if($group['gle_state']==0)
 							{
 								echo "<span class='muted'>未审批</span>";
 							}
 							elseif($group['gle_state']==1)
							{ 
									echo " <span class='text-success'>通过</span>";
							}
							else
							{
									echo "<span class='text-error'>驳回</span>";
							}
?>
</td>
 <td>
 <?php 
 $url=base_url('index.php/grouplevel/pass/'.$group['gle_id']);
 $bourl=base_url('index.php/grouplevel/bohui/'.$group['gle_id']);
 $showurl=base_url('index.php/grouplevel/show/'.$group['gle_id']);
 if($group['gle_state']==0)
						{
							echo " <a  href='$url' class='pass'>同意</a>";
							echo " &nbsp;| &nbsp;<a  href='$bourl' class='bohui'>驳回</a>";
							echo " &nbsp;|&nbsp;<a  href='$showurl' class='showinfo'>查看</a>";
						}
?>
 </td>
</tr>
<?php endforeach;?>
</table>
<?php echo $page?>
<div class="span6 offset1">
<style type="text/css">
#bohui{display:none;}
</style>
<form id='bohui' method="post">
<label for='gle_description'>驳回原因:</label>
<textarea rows="3" class="span6" name="gle_description" id='gle_description'></textarea>
<label></label>
<input type="submit" id="submit"/>
</form>
</div>
</div>
<script type="text/javascript">
	$(function(){
		$(".showinfo").click(function(){
			var href=$(this).attr("href");
			var time=new Date().getTime();
			$("#myModal .modal-body").empty().load(href,{time:time});
			$("#myModal").modal();
			return false;
			});
		$(".bohui").click(function(){
			//var trparent=$(this).parents('tr');
			var href=$(this).attr('href');
			//var time=new Date().getTime();
			$("#bohui").show().attr('action',href);
			return false;
		});
		$('#submit').click(function(){
				var $href=$("#bohui").attr('action');
				var time=new Date().getTime();
				var description=$("#gle_description").val();
				if(description==""){alert('请填写驳回原因！');return false;}
				$.post($href,{msg:description,time:time},function(data){
						data=data.split('_');
						if(data[1]==1)
						{
							var a="a[href='"+$href+"']";
							$(a).parents('tr').remove();
							$("#gle_description").val('');
							$('#bohui').hide();
							alert(data[0]);
						}
					});
				return false;
			});
		$(".pass").click(function(){
			    var trparent=$(this).parents('tr');
				var href=$(this).attr('href');
				var time=new Date().getTime();
				$.get(href,{time:time},function(data){
						data=data.split('_');
						if(data[1]==1)
						{
							//审批成功的相关操作，删除掉当前行
							    trparent.remove();
								$("#myModal .modal-body").empty().append(data[0]);
								$("#myModal").modal();
						}else if($data[1]==0)
						{
							//审批失败之后进行的操作，进行提示，什么也不做
							$("#myModal .modal-body").empty().append(data[0]);
							$("#myModal").modal();
						}
					});
				return false;
			});
		});
	</script>
</body>
</html>