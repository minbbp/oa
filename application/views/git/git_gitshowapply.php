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
    <script src="<?=base_url()?>/bootstrap/layer/layer.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
 <div class="span8">
			<div class="page-header">
				<h1>
					git 认证申请 
				</h1>
				
			</div>
			<!--  <div class="alert alert-info">
				 <button type="button" class="close" data-dismiss="alert">×</button>
				<h4>温馨提示!</h4> 
				 <?php echo $msg;?>
			</div>-->
				<?php echo form_open("git/apply_add",array('class'=>'form-horizontal','id'=>'gitform'))?>
				
				<div class="control-group show1">
				<label class="control-label" for='is_group'>是否加入git组</label>
				<div class="controls">
				<label class="radio inline"><input name='is_group' id="is_group" type="radio"  checked="checked" value="1"/>是</label>
				<label class="radio inline"><input name='is_group' id="is_group" type="radio"  value="2"/>否</label>
				</div>
				</div>
				<div class="control-group show2">
					<label class="control-label" for="add_datagroups">git组:</label>
					<div class="controls ">
					<?php foreach($git_groups as $group):?>
						 <label class="inline checkbox"><input type="checkbox" name="add_datagroups[]" value="<?php echo $group['group_id'];?>" /><?php echo $group['group_name'];?></label>
					<?php endforeach; ?>
					</div>
				</div>
				<div class="control-group">
				<label class="control-label">添加多个key</label>
				<div class="controls">
				<a href="javascript::"   id="add_key" class="btn btn-primary">添加新key</a>
				</div>
				</div>
				<div class="control-group show3">
					<label class="control-label" for="gitpub">ssh-key:</label>
					<div class="controls">
						<textarea id="gitpub"  name="gitpub[]" rows="5" class="span5" ></textarea>
					</div>
				</div>
				<div id="add_key_content"></div>	
				<div class="control-group">
					<div class="controls">
						<button class="btn" type="submit" id="submit">&nbsp;&nbsp;&nbsp;&nbsp;提交&nbsp;&nbsp;&nbsp;&nbsp;</button>
					</div>
				</div>
			<?php echo form_close();?>
			<div class="alert">
				 <button type="button" class="close" data-dismiss="alert">×</button>
				 <h4>申请流程说明</h4>
				 <div>
				 <p class="muted">如果您已经有git账号了，想在另外一台机器上使用您的git账号，则选择账号类型为“增加另外一个机器”</p>
				 <p class="muted">如果您没有git账号，则选择一个新建git账号</p>
				 <small>如果要加入指定的git组，请勾选要加入的git组</small>
				 </div>
			</div>
			
		</div>
		<script type="text/javascript">
			$(function(){
				if($("input[name='is_group']").val()==1)
				{
					$('.show2').show();
				}
				else
				{
					$('.show2').hide();
				}
				$("input[name='is_group']").click(function(){
					if($(this).val()==1){$('.show2').show();}else{$('.show2').hide();}
					});
				$("#add_key").click(function(){
					$html='<div class="control-group">'+$('.show3').html()+'</div>'
					$('#add_key_content').append($html);
				 });
				 
				$("#submit").click(function(){
					$.post("<?php echo base_url('index.php/git/apply_add')?>",$('#gitform').serialize(),function(data){
							layer.alert(data,9);
							$("#gitform")[0].reset();
						});
					return false;
					});
				});
		</script>
  </body>
  </html>