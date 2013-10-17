<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title><?php echo $title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=base_url()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <style type="text/css">
    .hidden{display:none;}
    </style>
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
<table class='table table-bordered table-hover'>
<thead><tr><th>#</th><th>组名</th><th> 申请者</th> <th>审批状态</th> <th>相关操作</th></tr></thead>
<?php foreach ($gops as $gop):?>
<tr>
<td><?php echo $gop['gop_id']?></td>
<td><?php echo $gop['group_name']?></td>
<td><?php echo $gop['realname']?></td>
<td><?php if($gop['gop_state']==1){echo "<small  class=' text-success'>通过</small>";}
			else if($gop['gop_state']==0){echo "<small  class='muted'>未审批</small>";}
			else if($gop['gop_state']==-1){echo "<small  class='text-error'>驳回</small>";}
	?>
	</td>
<td><?php if($gop['gop_state']==0)
		{
		$opadd_url=base_url('index.php/groupops/showinfo/'.$gop['gop_id']);
		$agadd_url=base_url('index.php/groupops/pass/'.$gop['gop_id']);
		$badd_url=base_url('index.php/groupops/bohui/'.$gop['gop_id']);
		echo "<a  href='$opadd_url' class='showinfo'> 操作说明</a>&nbsp | &nbsp;";
		echo "<a  href='$agadd_url' class='pass'> 通过</a>&nbsp | &nbsp;";
		echo "<a  href='$badd_url' class='bohui'> 驳回</a>";
	 }?>
</td>
</tr>
<?php endforeach;?>
</table>
<?php echo $page?>
</div>
<div class="opshow hidden">
<p class="text-error">运维人员需要如何如何的进行操作</p>
</div>
<div id="bohui" class="span6  hidden">
<form method='post'>
<label for="gop_description">驳回原因：</label>
<textarea rows="3" class="span5" name="gop_description" id="gop_description"></textarea>
<label></label>
<input type='submit' id="submit" class="btn btn-danger" value="驳回">&nbsp;&nbsp;&nbsp;<a href="javascript::void(0)"  id='guanbi'>关闭</a>
</form>
</div>
<script type="text/javascript">
$(function(){
 $(".showinfo").click(function(){
	 var href=$(this).attr('href');
	 var time=new Date().getTime();
	 $.get(href,{time:time},function(data){
		 $('.opshow').html(data);
		 });
	 
		$.layer({
				type:1,
				title:'操作说明',
				offset:['20px','20px'],
				area:['550','320'],
				
				bgcolor:'#fff',
				page:{dom:'.opshow'},
				
				close : function(index){
					layer.close(index);
					//$('.opshow').show();
				}
				
			});
		 return false;
	 });
	$('.pass').click(function(){
		var href=$(this).attr('href');
		 var time=new Date().getTime();
		 $.get(href,{time:time},function(data){
			if(data==1)
			{
				layer.alert('审批通过啦！',9);
				$("a[href='"+href+"']").parents('tr').remove();
			}
			else
			{
				layer.alert('审批失败啦！请联系管理员！',8);
			}
	   });
		
		return false;
		});
	$('.bohui').click(function(){
		
		var href=$(this).attr('href');
		var time=new Date().getTime();
		var i=$.layer({
			type:1,
			title:'驳回原因',
			page:{dom:'#bohui'},
			area:['450px','300px']
			});
		$('#guanbi').on('click',function(){
				layer.close(i);
			});
		$("#submit").on('click',function(){
			var msg=$("#gop_description").val();
			if(msg=="")
			{
				layer.alert('驳回信息不能为空',8);
				return false;
			}
			else
			{
				$.get(href,{time:time,gop_description:msg},function(data){
					if(data==1)
					{
						layer.alert('驳回信息成功！',9);
						$("#gop_description").val("");
						//驳回信息成功之后，删除改行信息
						$("a[href='"+href+"']").parents('tr').remove();
					}
					else
					{
						layer.alert('对不起，您暂时无法驳回信息',8);
					}
					});
				layer.close(i);
			}
			
			return false;
			});
		return false;
		});
});
</script>
</body>
</html>