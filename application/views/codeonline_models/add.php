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
<?php
  echo form_open("codeonline_models/save");
?>
<div class="control-group pull-left">
    <label class="control-label" for="m_name">模块名称:</label>
    <div class="controls">
      <input type="text" id="m_name" name="m_name"  placeholder="模块名称">
 	 <span class="help-inline"></span>
 	</div>
 </div>
 
 <div class="control-group pull-right">
    <label class="control-label" for="pid"> 模块分类:</label>
    <div class="controls">
    <select name="pid">
    <option value='0'>顶级模块</option>
    <?php foreach($m_pid as $p):?>
    <option value="<?php echo $p['m_id'];?>"><?php echo $p['m_name'];?></option>
    <?php endforeach;?>
    </select>
    <span class="help-inline"></span>
    </div>
 </div>
 <div class="control-group pull-left">
    <label class="control-label" for="m_type"> 模块类型:</label>
    <div class="controls">
    <select name="m_type" id="m_type">
    <option value='0'>线下模块</option>
    <option value='1'>线上模块</option>
    </select>
    <span class="help-inline"></span>
    </div>
 </div>
 <div class="control-group pull-right">
    <label class="control-label" for="m_online">当前版本:</label>
    <div class="controls">
      <input type="text" id="m_online" name="m_online"  placeholder="当前版本">
 	 <span class="help-inline"></span>
 	</div>
 </div>
 <div class="control-group pull-left">
    <label class="control-label" for="m_head">负责人:</label>
    <div class="controls">
      <select id="m_head" name="m_head">
      <?php foreach($level_users as $level):?>
      <option value="<?php echo $level['id'];?>"><?php echo $level['realname'];?></option>
      <?php endforeach;?>
      </select>
 	 <span class="help-inline"></span>
 	</div>
 </div>
 <div class="control-group pull-right">
    <label class="control-label" for="m_head_name">运维工程师:</label>
    <div class="controls">
      <select  id="op_id" name="op_id">
      <?php foreach ($op_users as $op):?>
      <option value="<?php echo $op['id'];?>"><?php echo $op['realname'];?></option>
      <?php endforeach;?>
      </select>
 	 <span class="help-inline"></span>
 	</div>
 </div>
<div class="clearfix"></div>

 <div class="row ">
 <label>&nbsp;&nbsp;&nbsp;&nbsp;开发人员添加：</label>
 <div class="span3">
 <select multiple="multiple"  id="a_dev" size="8">
 <?php foreach ($all_user as $user):?>
 <option value="<?php echo $user['id'];?>"><?php echo $user['realname'];?></option>
 <?php endforeach;?>
 </select>
 </div>
 <div class="span2 text-center">
  <p style="margin-top:30px; "><span class="btn add">添加&gt;&gt;</span></p>
  <p style="margin-top:20px; "><span class="btn btn-danger remove">&lt;&lt;移除</span></p>
 </div>
 <div class="span3"><select multiple="multiple" id="b_dev" name="m_devloper" size="8"></select></div>
 </div>
 <div class="row">
 <label>&nbsp;&nbsp;&nbsp;&nbsp;测试人员添加：</label>
 <div class="span3">
 <select multiple="multiple" id="test_a"  size="8">
 <?php foreach ($all_user as $user):?>
 <option value="<?php echo $user['id'];?>"><?php echo $user['realname'];?></option>
 <?php endforeach;?>
 </select>
 </div>
 <div class="span2 text-center">
  <p style="margin-top:30px; "><span class="btn add">添加&gt;&gt;</span></p>
  <p style="margin-top:20px; "><span class="btn btn-danger remove">&lt;&lt;移除</span></p>
 </div>
 <div class="span3"><select multiple="multiple" name="m_tester" id="test_b" size="8"></select></div>
 </div>
 <div class="control-group pull-left">
    <label class="control-label" for="m_server">服务器列表:</label>
    <div class="controls">
      <textarea rows="6" class="span7" id="m_server" name="m_server" placeholder="多个ip请使用逗号进行分割"></textarea>
 	 <span class="help-inline"></span>
 	</div>
 </div>
 <div class="clearfix"></div>
  <div class="row">
 <label>&nbsp;&nbsp;&nbsp;&nbsp;依赖模块添加：</label>
 <div class="span3">
 <select multiple="multiple" id="re_a" size="8">
 <?php foreach($m_pid as $p):?>
    <option value="<?php echo $p['m_id'];?>"><?php echo $p['m_name'];?></option>
    <?php endforeach;?>
 </select>
 </div>
 <div class="span2 text-center">
  <p style="margin-top:30px; "><span class="btn add">添加&gt;&gt;</span></p>
  <p style="margin-top:20px; "><span class="btn btn-danger remove">&lt;&lt;移除</span></p>
 </div>
 <div class="span3"><select multiple="multiple" id="re_b" name="m_relymodel" size="8"></select></div>
 </div>
<div class="control-group  clearfix">
    <label class="control-label" for=re_status></label>
    <div class="controls">
       <input type="button" class="btn" onclick="return history.back();" value="&lt;&lt;返回"> &nbsp;&nbsp;&nbsp;
       <input type="submit" class="btn btn-primary span2" id="submit" value="提交"> 
    </div>
 </div>
<?php echo form_close();?>
</div>
<script type="text/javascript">

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
	$("#m_server").blur(function(){
		var str=$(this).val();
		str=str.replace('，',',');
		if(str.substring(0,1)==',')
		{
			str=str.substring(1);
		}
		if(str.substring(str.length-1)==',')
		{
			str=str.substring(0,str.length-1);
		}
		$(this).val(str);
	});
	var elment=['#m_online','#m_name'];
	var msg=['请填写线上版本!','模块名不能为空!'];
	validate_callback(elment,msg);
	//复选框选择值
	$(".add").click(function(){
			var $options=$(this).parents('div.row').find('.span3:eq(0) select option:selected');
			var $other=$(this).parents('div.row').find('.span3:eq(1) select');
			$options.appendTo($other);
		  });
	  $(".remove").click(function(){
		  var $options=$(this).parents('div.row').find('.span3:eq(1) select option:selected');
			var $other=$(this).parents('div.row').find('.span3:eq(0) select');
			$options.appendTo($other);
		  });
	//多选结束
	$("#submit").click(function(){
		$("#re_b option,#test_b option,#b_dev option").attr('selected','selected');//把复选表单提交的部分都选中。
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
					if(json_data.status==1)
					{//页面进行条状
						layer.alert(json_data.msg,9,'成功提示！',function(){
							location.href='<?php echo base_url('index.php/codeonline_models/index');?>';
							});
					}
					else
					{//还在当前页面
						layer.alert(json_data.msg,8,'错误提示！');
					}
					 },"json");
				 return false;
			}
	});
});
</script>
</body>
</html>