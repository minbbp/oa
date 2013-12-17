<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title>运维OA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=base_url()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="<?=base_url()?>/bootstrap/js/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/layer/layer.min.js"></script>
    <!-- bootstrap end -->
    <style>/*
		#require_tr{
			line-height:30px;
			height:40px;
		}*/
	</style>
  </head>
  <body>
 <div class="span8 " style="margin-left:15px;margin-top:5px;">
	<table class="table table-bordered table-hover">
		<thead>
			<form action="<?php echo site_url('codeonline/alist')?>" method="post" accept-charset="utf-8" class="form-horizontal" id="require_form"> 
			<tr id="require_tr">
				<th colspan="2"><p>选择时间：
					<select id="choose_months" name="months" class="span4">
						<option value="1" <?php if($months == 1) echo 'selected';?>>近一个月</option>
						<option value="3" <?php if($months == 3) echo 'selected';?>>近三个月</option>
						<option value="6" <?php if($months == 6) echo 'selected';?>>近六个月</option>
					</select></p>
				</th>

				
				<th><p class="input-append">
					<input class="span2" id="keyword" name="keyword" placeholder="请输入关键字" type="text" value="<?php echo $keywords;?>" autocomplete="off">
					<button class="btn btn-info " id="yes" type="submit">Go!</button></p>
				</th>
			</tr>
			</form>
			<tr>
				<th>需求编号</th><th>需求标题</th><th>添加人员</th>
			</tr>
			
		</thead>
		<tbody>
			<?php foreach ($re_rs as $r):?>
			<tr class="choose_require" style="cursor:pointer">
				<td><?php echo $r['required_no'];?></td>
				<td><span title="<?php echo  $r['re_description'];?>" data-toggle="tooltip" class='showinfo' id="require_title"><?php echo $r['required_title'];?></span></td>
				<td><?php echo $r['realname'];?></td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
 
	<div class="pull-right"><?php echo $page?></div>
</div>
<script type="text/javascript">
$(function(){
	//选择需求，显示在需求输入框
	
	$('.choose_require').click(function(){
		//$(this).addClass('btn-success').siblings().removeClass('btn-success');
		$(this).css('background','#F5F5F5').siblings().css('background','');
		var title = $(this).find("#require_title").text();
		$('#require_id', parent.document).val(title); 
	});	

	$('#choose_months').change(function(){
		var va = $(this).val();
		window.location.href = "<?php echo site_url('codeonline/alist')?>?months="+va;
	//	$.post('<?php echo site_url('codeonline/alist')?>',va,function(json_data){
	//		json_data = JSON.parse(json_data);
	//	});
	});
});
</script>
</body>
</html>
