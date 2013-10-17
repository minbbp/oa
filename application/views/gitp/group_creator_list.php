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
    <style type="text/css">
    .hidden{display:none;}
    </style>
  </head>
  <body>
 <div class="span8 offset1">
 <div class="page-header">
				<h3>
					<?php echo $title?>
				</h3>
</div>
<table class='table table-bordered table-hover'>
<thead><tr><th>#</th><th>组名</th><th>申请者</th> <th>审核状态</th><th>相关操作</th></tr></thead>
<?php foreach ($groups as $group):?>
<tr>
<td><?php echo $group['group_id']?></td>
<td><?php echo $group['group_name']?></td>
<td><?php echo $group['realname']?></td>
<td>
 <?php 
 if($group['gcre_state']==0)
 {echo "<span class='muted'>未审批</span>";}
 elseif($group['gcre_state']==1)
{ echo " <span class='text-success'>通过</span>";}
else
{
	echo "<span class='text-error'>驳回</span>";
}
?>
</td>
 <td>
 <?php 
 $url=base_url('index.php/git_creator/pass/'.$group['gcre_id']);
 $bohui=base_url('index.php/git_creator/reject/'.$group['gcre_id']);
 if($group['gcre_state']==0)
{
	echo " <a  href='$url' class='pass'>同意</a>";
	echo "&nbsp;&nbsp;|&nbsp;&nbsp;";
	echo " <a  href='$bohui' class='reject'>驳回</a>";
}
?>
 </td>
</tr>
<?php endforeach;?>
</table>
<?php echo $page?>
</div>
<div class="reject_view span5 hidden">
<form method="post" id='re_form'>
<label  for="gcre_description">驳回原因:</label>
<textarea rows="4" class="span5" name="gcre_description" id="gcre_description"></textarea>
<br/><br/>
<label>
  <a href='javascript::void(0)' class='btn btn-danger reset'>取消</a>&nbsp;&nbsp;&nbsp;&nbsp;<input class="btn btn-primary" type="submit" id="submit" value="提交"/>
</label>
</form>
</div>
<script type="text/javascript">
$(function(){
$('.pass').click(function(){
	var href=$(this).attr('href');
	var time=new Date().getTime();
	$.get(href,{time:time},function(data){
		layer.alert(data,9,'');
		});
	$("a[href='"+href+"']").parents('tr').remove();
	return false;
});
$('.reject').click(function(){
	var href=$(this).attr('href');
	var time=new Date().getTime();
	var i=$.layer({
		type:1,
		title:'驳回',
		area:['500px','200px'],
		offset:['60px','60px'],
		border:[0],
		page:{dom:'.hidden'}
	});
	$('.reset').on('click',function(){
		layer.close(i);
	});
	$('#submit').on('click',function(){
      if($('#gcre_description').val()=="")
         {
          layer.msg('驳回原因不能为空哦！',2,1);
			return false;
          }
      else
      {
          $.post(href,{time:time,gcre_description:$('#gcre_description').val()},function(data)
            {
				layer.alert(data,9,'');
				layer.close(i);
				$("a[href='"+href+"']").parents('tr').remove();
           });
      }
      return false;
		});
	return false;
});
	
});
</script>
</body>
</html>