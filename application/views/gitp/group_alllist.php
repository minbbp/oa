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
				</h3>
</div>
<table class='table table-bordered table-hover'>
<thead><tr><th>#</th><th>组名</th><th> 创建者</th> <th>账号状态</th> <th>用户组成员</th></tr></thead>
<?php foreach ($groups as $group):?>
<tr>
<td><?php echo $group['group_id']?></td>
<td><?php echo $group['group_name']?></td>
<td><?php echo $group['realname']?></td>
<td><?php if($group['group_state']==1){echo "<span class='icon-ok'></span>";}
			else{echo "<span class='icon-remove'></span>";}
	?>
	</td>
<td>
<?php echo anchor('gitgroups/showuser/'.$group['group_id'],$group['num'].'人',"title='点击查看' class='showuser' ")?>
</td>
</tr>
<?php endforeach;?>
</table>
<?php echo $page?>
</div>
<script type="text/javascript">
	$(function(){
		$(".showuser").click(function(){
			var href=$(this).attr("href");
			var time=new Date().getTime();
			$("#myModal .modal-body").empty().load(href,{time:time});
			$("#myModal").modal();
			return false;
			});
		});
	</script>
</body>
</html>