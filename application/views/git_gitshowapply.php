<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title>运维OA_login</title>
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
				<h1>
					git 账号申请 
				</h1>
				
			</div>
			<div class="alert alert-info">
				 <button type="button" class="close" data-dismiss="alert">×</button>
				<h4>温馨提示!</h4> 
				 <?=$msg?>
			</div>
			<?php if($state==0):?>
				<?php echo form_open("git/apply_add",array('class'=>'form-horizontal'))?>
				<div class="control-group">
					<label class="control-label" for="gitpub">ssh-key:</label>
					<div class="controls">
						<textarea id="gitpub"  name="gitpub" rows="5" class="span5" ></textarea>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="add_datagroups">是否加入data组:</label>
					<div class="controls">
						 <input type="radio" name="add_datagroups" value="0" checked/>&nbsp;否&nbsp;&nbsp;
						 <input type="radio" name="add_datagroups" value="1" checked/>&nbsp;是
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button class="btn" type="submit">申请</button>
					</div>
				</div>
			<?php echo form_close();?>
			<?php endif;?>
		</div>
  </body>
  </html>