<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title><?php echo $title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=base_url()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
     <link href="<?=base_url()?>/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen" />
	<script src="<?=base_url()?>/bootstrap/js/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap-datetimepicker.zh-CN.js"></script>
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
<?php
  echo form_open("requirements/save/$required_id",array('class'=>'form-horizontal'));
?>
<div class="control-group">
    <label class="control-label" for="required_no">需求编号:</label>
    <div class="controls">
      <input type="text" id="required_no" name="required_no" value="<?php echo $required_no?>" placeholder="需求编号">
 	 <span class="help-inline"></span>
 	</div>
 </div>
 
 <div class="control-group">
    <label class="control-label" for="required_title">需求标题:</label>
    <div class="controls">
      <input type="text" id="required_title" class="input-xlarge" name="required_title" value="<?php echo $required_title?>" placeholder="需求标题">
    <span class="help-inline"></span>
    </div>
 </div>
 <div class="control-group">
    <label class="control-label" for="re_description">需求描述:</label>
    <div class="controls">
      <textarea  id="re_description" class="span5" rows="5" name="re_description"><?php echo $re_description;?></textarea>
    <span class="help-inline"></span>
    </div>
 </div>
 <div class="control-group ">
    <label class="control-label" for=re_status>需求状态:</label>
    <div class="controls">
      <select class="span2" name="re_status">
      <option value='1' <?php if($re_status==1){echo 'selected';}?>>进行中</option>
      <option value='0' <?php if($re_status==0){echo 'selected';}?>> 暂停</option>
      </select>
    </div>
 </div>
 <div class="control-group">
    <label class="control-label" for="bg_time">开始时间:</label>
    <div class="controls">
 		<div class="input-append date datetimepicker" id="bg_time" data-date="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
    	<input class="span2" size="16" name="bg_time" type="text" readonly="readonly" value="<?php $bg_time=$bg_time==""?time():$bg_time;echo date("Y-m-d",$bg_time);?>">
    	<span class="add-on"><i class="icon-th"></i></span>
		</div>  
		</div>
</div> 
 <div class="control-group">
    <label class="control-label" for="re_endtime">结束时间:</label>
    <div class="controls">
 		<div class="input-append date datetimepicker" id="re_endtime" data-date="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
    	<input class="span2" size="16" name="re_endtime" type="text" readonly="readonly" value="<?php $re_endtime=$re_endtime==""?time():$re_endtime;echo date("Y-m-d",$re_endtime);?>">
    	<span class="add-on"><i class="icon-th"></i></span>
		</div>  
		</div>
</div> 
<div class="control-group">
    <label class="control-label" for=re_status></label>
    <div class="controls">
       <input type="button" class="btn" onclick="return history.back();" value="&lt;&lt;返回"> &nbsp;&nbsp;&nbsp;
       <input type="submit" class="btn btn-primary span2" id="submit" value="提交"> 
    </div>
 </div>
<?php echo form_close();?>
</div>
<script type="text/javascript">
$('.datetimepicker').datetimepicker({
    language:  'zh-CN',
    weekStart: 1,
    todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	minView: 2,
	forceParse: 0
});
 function validate(element,msg)
 {
	 $(element).blur(function(){
			if($(this).val()=='')
			{
				
				$(this).parents('.control-group').removeClass('success').addClass('error');
				$(this).siblings('.help-inline').removeClass('icon-ok').text(msg);
			}
			else
			{
				$(this).parents('.control-group').removeClass('error').addClass('success');
				$(this).siblings('.help-inline').addClass('icon-ok').text('');
			}
		});
		
 }
 function validate_callback(elment,msg)
 {
	 var length=elment.length;
	 for(var i=0;i<length;i++)
		{
			validate(elment[i],msg[i]);
		}
 }
//使用js来验证用户的输出
$(function(){
	var elment=['#required_title','#required_no','#re_description'];
	var msg=['标题信息填写错误!','需求编号填写错误!','需求内容不能为空！'];
	validate_callback(elment,msg);
	$("#submit").click(function(){
		$('form input').trigger('blur');
			if($('form .error').length)
			{
				layer.alert('表单内容填写错误！',8,'错误提示！');
				return false;
			}
			else
			{
				//使用ajax提交表单
				var href=$('form').attr('action');
				 $.post(href,$('form').serialize(),function(json_data){
					json_data=JSON.parse(json_data);
					if(json_data.status==1)
					{//页面进行条状
						layer.alert(json_data.msg,8,'成功提示！',function(){
							location.href='<?php echo base_url('index.php/requirements/index');?>';
							});
					}
					else
					{//还在当前页面
						layer.alert(json_data.msg,8,'错误提示！');
					}
					 });
				 return false;
			}
	});
});
</script>
</body>
</html>