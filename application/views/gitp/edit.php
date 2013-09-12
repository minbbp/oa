<?php 
$group_name=array
				(
					'name'=>'group_name',
					'id'=>'group_name',
					'class'=>'input_xlarge',
					'value'=>set_value('group_name',$group['group_name']),
					
				);
$group_description=array(
				'name'=>'group_description',
				'id'=>'group_description',
				'rows'=>8,
				'class'=>'span5',
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
    <!-- bootstrap end -->
  </head>
  <body>
 <div class="span8">
 <div class="page-header">
				<h3>
					<?php echo $title?>
				</h3>
</div>
<?php echo form_open('/gitgroups/save/'.$group_id);?>
<?php echo form_error($group_name['name'])?>
<?php echo form_label('git用户组名：','group_name');?>
 <?php echo form_input($group_name)?>
 <?php echo form_error($group_description['name'])?>
 <?php echo form_label('组描述：',$group_description['id'])?>
  <?php echo form_textarea($group_description)?>
  <div class="row">

   
   <div class="span3">
    <?php echo form_label('加入该组的账号：')?>
    <select name="git_account[]" id="git_account" multiple="multiple" size="6">
   <?php 
   foreach($in as $git):
   ?>
   <option value="<?php echo $git['git_id']?>"><?php echo $git['git_account']?></option>
   <?php
    endforeach;
    ?>
    </select>
   </div>
   <div class="span2 text-center"> 
    <p style="margin-top:30px;"><span class="btn" id="add">&lt;&lt;添加</span></p>
     
    <p style="margin-top:10px;"><span class="btn btn-danger" id="remove">去除&gt;&gt;</span></p>
    </div>
     <div class="span3">
   <?php echo form_label('可选的git账号：')?>
   <select name="allgit" id="allgit" multiple="multiple" size="6">
   
   <?php 
   
   foreach($gits as $git):
   ?>
   <option value="<?php echo $git['git_id']?>"><?php echo $git['git_account']?></option>
   <?php
    endforeach;
    ?>
   </select>
   </div>
   </div>
  <label>
  <!-- <input type="reset" value="重新填写 " class="btn btn-danger"/> &nbsp;&nbsp; --> <input type="submit"  id="mysubmit" value=" 提交 " class="btn btn-primary"/> 
  </label>
 
<?php echo form_close();?>
 </div>
 <script  type="text/javascript">
	$(function(){
	  $("#add").click(function(){
			var $options=$('#allgit  option:selected');
			$options.appendTo('#git_account');
		  });
	  $("#remove").click(function(){
			var $options=$('#git_account option:selected');
			$options.appendTo('#allgit');
		  });
	  $("#mysubmit").click(function(){
			$('#git_account option').each(function(n){
					$(this).attr('selected','selected');
					
				});
			
					   if($('#git_account option:selected').length==0)
						{
						  alert('必须为用户组添加用户 ');
						   $('#allgit option').first().focus();
						   return false;
						}
		  });
	});
 </script>
 </body>
</html>