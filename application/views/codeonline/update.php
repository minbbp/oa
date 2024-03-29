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
    <script src="<?=base_url()?>/bootstrap/js/bootstrap-typeahead.js"></script>
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
  echo form_open("codeonline/update_save/{$apply_row['apply_id']}/$m_id",array('class'=>'form-horizontal'));
?>
<div class="control-group ">
    <label class="control-label" for="require_id"> 选择需求:</label>
    <div class="controls">
      <input type="text" id="require_id" name="require_id"  value="<?php echo $require_row->required_title;?>" placeholder="请输入需求关键字" autocomplete="off"/>
 	 <span class="help-inline"></span>
 	</div>
 </div>
 
 <div class="control-group ">
    <label class="control-label" for="git_url">源码git地址:</label>
    <div class="controls">
       <input type="text" id="git_url" name="git_url" value="<?php echo $apply_row['git_url'];?>" placeholder="源码git地址">
    <span class="help-inline"></span>
    </div>
 </div>
 <div class="control-group ">
    <label class="control-label" for="git_tag">git标签:</label>
    <div class="controls">
     <input type="text" id="git_tag" name="git_tag" value="<?php echo $apply_row['git_tag'];?>"  placeholder="git标签">
    <span class="help-inline"></span>
    </div>
 </div>
  <div class="control-group">
    <label class="control-label" for="online_time"> 上线时间:</label>
    <div class="controls">
 		<div class="input-append date datetimepicker" id="online_time" data-date="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
    	<input class="span2" size="16" name="online_time" type="text" readonly="readonly" value="<?php echo  $apply_row['online_time'];?>">
    	<span class="add-on"><i class="icon-th"></i></span>
		</div>  
		</div>
</div>
<div class="row">

<table class="table table-bordered">
<caption><b>涉及配置文件</b></caption>
<tr><th> 文件名</th><th>修改项</th><th>原值</th><th>新值</th><th>操作</th></tr>
<?php foreach($config_rs as $f):?>
<tr><td><input name="file_name[]" value="<?php echo $f['file_name'];?>"  class="span2" type="text"/></td>
<td><input name="file_item[]"  class="span2"  value="<?php echo $f['file_item'];?>"  type="text"/></td>
<td><input name="file_item_old_value[]" class="span1"  value="<?php echo $f['file_item_old_value'];?>"  type="text"/></td>
<td><input name="file_item_new_value[]" class="span1"   value="<?php echo $f['file_item_new_value'];?>"  type="text"/></td>
<td><a href="javascript:void(0)" class="add_tr"><span class="icon16 icon_add"></span></a>&nbsp;&nbsp;<a href="javascript:void(0)" class="remove_tr"><span class="icon16 icon_delete"></span></a></td>
</tr>
<?php endforeach;?>
<?php if(empty($config_rs)):?>
<tr><td><input name="file_name[]"   class="span2" type="text"/></td>
<td><input name="file_item[]"  class="span2"    type="text"/></td>
<td><input name="file_item_old_value[]" class="span1"    type="text"/></td>
<td><input name="file_item_new_value[]" class="span1"     type="text"/></td>
<td><a href="javascript:void(0)" class="add_tr"><span class="icon16 icon_add"></span></a>&nbsp;&nbsp;<a href="javascript:void(0)" class="remove_tr"><span class="icon16 icon_delete"></span></a></td>
</tr>
<?php endif;?>
</table>
<table></table>
</div>
<div class="clearfix"></div>
 <div class="row">
 <label>&nbsp;&nbsp;&nbsp;&nbsp;升级服务器添加：</label>
 <div class="span3 multiple">
 <select multiple="multiple" id="test_a"  name="server_updateold" size="8">
 <?php foreach ($server_rs as $user):?>
 <option value="<?php echo $user['s_id'];?>"><?php echo ($user['s_internet']);?></option>
 <?php endforeach;?>
 </select>
 </div>
 <div class="span2 text-center">
  <p style="margin-top:30px; "><span class="btn add btn-success">添加&gt;&gt;</span></p>
  <p style="margin-top:20px; "><span class="btn btn-danger remove">&lt;&lt;移除</span></p>
 </div>
 <div class="span3 multiple"><select multiple="multiple" name="server_update[]" id="test_b" size="8"></select></div>
 </div>

 <div class="row" style="margin-top:20px">
    <label class="control-label" for='tester_id' style="text-align: left;width:70px;">测试人员:</label>
      <select class="span2" id="tester_id" name="tester_id">
     <?php foreach ($tester_rs as $user):?>
 	<option value="<?php echo $user['test_id'];?>"  <?php if($user['test_id']==$apply_row['tester_id']){echo "selected='selected'";}?>><?php echo $user['realname'];?></option>
 	<?php endforeach;?>
      </select>
 </div>
  <div class="row" style="margin-top:20px;">
    <label class="control-label" style="text-align:left;width:70px;"  for="online_description">备注:</label>
     <textarea  id="online_description" class="span7" rows="10" name="online_description"><?php echo $apply_row['online_description']?></textarea>
    <span class="help-inline"></span>
 </div>
<div class="control-group" style="margin-top:20px;">
    <label class="control-label" for=re_status></label>
    <div class="controls pull-right">
       <input type="button" class="btn btn-danger" onclick="return history.back();" value="&lt;&lt;返回"> &nbsp;&nbsp;&nbsp;
       <input type="submit" class="btn btn-success span2" id="submit" value="提交"> 
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
	$("#require_id").typeahead({
        source:<?php echo $testjson;?>,
        display: 'required_title',
        val: 'required_id' ,
	});
	var $dtmp=<?php echo '['.$apply_row['server_update'].']';?>;
	$("#test_a option").each(function(i,n){
		var dd=$(n);
		if($.inArray(Number(dd.val()),$dtmp)!=-1)
		{
			dd.appendTo($('#test_b'));
		}
		});
	
	$("#require_id").blur(function(){
		var val=$(this).val();
	 	$.post('<?php echo base_url('index.php/codeonline/check_require_name');?>',{name:val,time:new Date().getTime()},function(data)
	 		 	{
					if(data==1)
					{
						$("#require_id").parents('.control-group').removeClass('error').addClass('success');
						$("#require_id").siblings('.help-inline').addClass('icon-ok').text('');
					}
					else
					{
						$("#require_id").parents('.control-group').removeClass('success').addClass('error');
						$("#require_id").siblings('.help-inline').removeClass('icon-ok').text('需求编号不能自行输入！');
					}
		 		});
		});
	var elment=['#required_title','#required_no','#re_description'];
	var msg=['标题信息填写错误!','需求编号填写错误!','需求内容不能为空！'];
	validate_callback(elment,msg);
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
  $(document).delegate('.add_tr','click',function(){
		$tr=$(this).parents('tr').html();
		$tr1="<tr>"+$tr+"</tr>";
		$(".table").append($tr1);
	  });
  $(document).delegate('.remove_tr','click',function(){
	  var $length=$(".table tr").length;
	  if($length>2)
		{
		  $tr=$(this).parents('tr').remove();
		}
		else
		{
			layer.alert('不能再删除了',8);
		}
		
		
	  });
  $('#git_tag').blur(function(){
		var $val=$(this).val();
		if(''!=$val)
		{
			if(/v\d+\.\d+\.\d+\.\d+/.test($val))
			{
				$("#git_tag").parents('.control-group').removeClass('error').addClass('success');
				$("#git_tag").siblings('.help-inline').addClass('icon-ok').text('');
			}
			else
			{
				
				$("#git_tag").parents('.control-group').removeClass('success').addClass('error');
				$("#git_tag").siblings('.help-inline').removeClass('icon-ok').text('git标签格式错误！正确格式v0.9.0.1');
			}
		}
		else
		{
			$("#git_tag").parents('.control-group').removeClass('error');
			$("#git_tag").siblings('.help-inline').text('');
		}
	  });
	$("#submit").click(function(){
		$('form input').trigger('blur');
		$('#test_b option').attr('selected','selected');
		if(null==$('#test_b').val())
		{
			layer.alert('请选择服务器');
			return false;
		}
			if($('form .error').length)
			{
				layer.alert('表单内容填写错误！',8,'错误提示！');
				return false;
			}
			else
			{
				//使用ajax提交表单
				//提示依赖关系提示
				var relymodel='<?php foreach($relymodels as $r){echo $r['m_name'].",";} ?>';
				var msg='';
				if(relymodel=='')
				{
					 msg="是否直接提交数据进行保存？";
				}
				else
				{
					 msg='该模块依赖:'+relymodel+"<br/>是否已经通知相关同学？";
				}
				$.layer({
					 shade : [0.5 ,'#000',true], //不显示遮罩
					 title : '依赖模块提示',
					    area : ['400px','auto'],
					    offset : ['400px','50%'],
					    dialog : {
					        msg:msg,
					        btns : 2, 
					        type : -1,
					        btn : ['确认','暂时保存'],
					        yes : function(){
					        	 href=$('form').attr('action')+'/1';
								 $.post(href,$('form').serialize(),function(json_data){
									 json_data=JSON.parse(json_data);
									if(json_data.status==1)
									{//页面进行条状
										layer.alert(json_data.msg,8,'成功提示！',function()
										{
											location.href='<?php echo base_url('index.php/codeonline/myapply');?>';
											});
									}
									else
									{//还在当前页面
										layer.alert(json_data.msg,8,'错误提示！');
									}
									 });
					        },
					        no : function()
					        {
					        	 href=$('form').attr('action')+'/2';
								 $.post(href,$('form').serialize(),function(json_data){
									json_data=JSON.parse(json_data);
									if(json_data.status==1)
									{//页面进行条状
										layer.alert(json_data.msg,8,'成功提示！',function(){
											location.href='<?php echo base_url('index.php/codeonline/myapply');?>';
											});
									}
									else
									{//还在当前页面
										layer.alert(json_data.msg,8,'错误提示！');
									}
									 });
					        }
					    }
					
					});
				return false;
			}
	});
});
</script>
</body>
</html>