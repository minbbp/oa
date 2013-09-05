<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title>运维OA_git账号修改</title>
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
					git 账号修改
				</h3>
			</div>
			<?php echo form_open('/git/git_save/'.$git_one['git_id'],array('class'=>'form-horizontal'))?>
		<div class="control-group">
    		<label class="control-label" for="git_account">git账号：</label>
    		<div class="controls">
      			<input type="text" id="git_account" value="<?php echo $git_one[git_account]?>" name='git_account' />
    		</div>
  		</div>
  		<div class="control-group">
    		<label class="control-label" for="op_state">op处理状态：</label>
    		<div class="controls">
    			 <input type="radio" id="op_s" name="op_state" value='<?php if($user['level']==1){echo 11;}else{echo 2;}?>' checked/>&nbsp;成功&nbsp;
      			  <input type="radio" id="op_e" name="op_state" value="<?php if($user['level']==1){echo -11;}else{echo -2;}?>"/>&nbsp;失败&nbsp;
      			  
    		</div>
  		</div>
  		<!-- <div class="control-group">
    		<label class="control-label" for="git_state">git账号状态：</label>
    		<div class="controls">
      			  <select name="git_state" id="git_state">
      			  <option value='1'>启用</option>
      			  <option value='-2'>禁用</option>
      			  <option value='2'>未启用</option>
      			  </select>
    		</div>
  		</div> -->
  		
		<div class="control-group">
    		<label class="control-label" for="git_description">处理结果备注：</label>
    		<div class="controls">
      			<textarea id="git_description"  name='git_description' class="span5" rows='6'></textarea>
    		</div>
  		</div>
  	
  		<div class="control-group">
    		<label class="control-label" for="sendmail">发送邮件通知：</label>
    		<div class="controls">
    				<input name="sendmail" type="radio" value='1' checked/>发送&nbsp;&nbsp;
      			  <input  name="sendmail" type="radio" value='0'/>不发送&nbsp;&nbsp;
      			  
      			  <?php if($user['level']!=1){?><input name="sendmailforlevel" type="checkbox" value='1' />&nbsp;&nbsp;告知主管<?php }?>
    		</div>
  		</div>
  		
  		 <button type="submit" class="btn offset6 btn-primary" > 提交</button>
			<?php echo form_close()?>
</div>
</body>
</html>