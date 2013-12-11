<?php 
$group_name=array
				(
					'name'=>'group_name',
					'id'=>'group_name',
					'class'=>'input_xlarge',
						'autocomplete'=>"off",
						'placeholder'=>'组名不能包含中文',
					'value'=>set_value('group_name',$group['group_name']),
					
				);
$group_description=array(
				'name'=>'group_description',
				'id'=>'group_description',
				'rows'=>8,
				'class'=>'span5',
				'placeholder'=>'请详细描述您使用申请改组的用途',
				'value'=>set_value('group_description',$group['group_description'])
						);

?>
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
<?php echo form_open('/gitgroups/save/'.$group_id);?>
<?php echo form_error($group_name['name'])?>
<br/><?php echo form_label('git用户组名：','group_name');?><br/>
 <?php echo form_input($group_name)?>
 <?php echo form_error($group_description['name'])?>
 <br/><?php echo form_label('组描述：',$group_description['id'])?><br/>
  <?php echo form_textarea($group_description)?>
  <div class="row">
   <div class="span3 multiple">
  <br/><?php echo form_label('加入该组人员：')?><br/>
    <select name="user_id[]" id="git_account" multiple="multiple" size="6">
    </select>
   </div>
   <div class="span2 text-center dual-control"> 
    <p style="margin-top:30px;"><span class="btn btn-success" id="add">&lt;&lt;添加</span></p>
    <p style="margin-top:10px;"><span class="btn btn-danger" id="remove">去除&gt;&gt;</span></p>
   
    </div>
     <div class="span3  multiple">
   <br/><?php echo form_label('可选加入人员：')?><br/>
   <select name="allgit" id="allgit" multiple="multiple" class="span3" size="6">
   <?php 
   foreach($all_users as $user):
   ?>
   <option value="<?php echo $user['id'];?>"><?php echo $user['realname'];?></option>
   <?php
    endforeach;
    ?>
   </select>
   </div>
   </div>
   
  <label style="margin-top:30px;margin-left:486px;">
  <!-- <input type="reset" value="重新填写 " class="btn btn-danger"/> &nbsp;&nbsp;  -->
  <input type="submit"  id="mysubmit" value=" 提交申请 "   class="btn btn-success"/> 
  </label>
 
<?php echo form_close();?>
 </div>
 <script  type="text/javascript">
	$(function(){
		if(<?php echo $show_msg?>==1)
		{
			layer.alert('成功添加git组！',9,'消息提示',function(i){
					$("form")[0].reset();
					layer.close(i);
				});
		}
	  $("#add").click(function(){
			var $options=$('#allgit  option:selected');
			$options.appendTo('#git_account');
		  });
	  $("#remove").click(function(){
			var $options=$('#git_account option:selected');
			$options.appendTo('#allgit');
		  });
	  $("#mysubmit").click(function(){
			$('#git_account option').each(function(n)
				{
					$(this).attr('selected','selected');
				});
			var group_name=$("#group_name").val();
			if(group_name=="")
			{
				layer.alert('组名不能为空',8,'提示');
				return false;
			}
			if(/[\u4e00-\u9fa5]+/.test(group_name))
			{
				layer.alert('不能包含中文',8,'提示',function(i){
					$("#group_name").focus();
					layer.close(i);
					});
				return false;
			}
			if($('#git_account option:selected').length==0)
			{
				layer.alert('必须为用户组添加用户 ',8,'提示');
				$('#allgit option').first().focus();
				return false;
			}
		  });
	});
 </script>
 </body>
</html>