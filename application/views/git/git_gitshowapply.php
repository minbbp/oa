<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title><?php echo $title;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?php echo base_url('/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet" media="screen">
	<script src="<?php echo base_url('/bootstrap/js/jquery-1.10.2.min.js')?>"></script>
    <script src="<?php echo base_url('/bootstrap/js/bootstrap.min.js')?>"></script>
    <script src="<?php echo base_url('/bootstrap/layer/layer.min.js')?>"></script>
    <!-- bootstrap end -->
  </head>
  <body>

 <div class="span8 offset1">
			<div class="page-header">
				<h3>
					git 认证申请 
				</h3>
			</div>
				<?php echo form_open("git/apply_add",array('class'=>'form-horizontal','id'=>'gitform'))?>
				<?php if(!empty($git_groups)):?>
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
				<?php endif;?>
				<div class="control-group">
				<label class="control-label">添加多个key</label>
				<div class="controls">
				<a href="javascript:void(0)"   id="add_key" class="btn btn-primary">添加新key</a>
				</div>
				</div>
				<div class="control-group show3">
					<label class="control-label" for="gitpub">ssh-key:</label>
					<div class="controls">
						<textarea id="gitpub"  name="gitpub[]" rows="5" class="m_gitpub span5" ></textarea>
						<span class="help-block">小提示：ssk-key不能为空！</span>
					</div>
					
				</div>
				<div id="add_key_content"></div>	
				<div class="control-group">
					<div class="controls">
						<button class="btn btn-success" type="submit" id="submit">&nbsp;&nbsp;&nbsp;&nbsp;提交&nbsp;&nbsp;&nbsp;&nbsp;</button>
					</div>
				</div>
			<?php echo form_close();?>
		
			<div class="alert">
				 <button type="button" class="close" data-dismiss="alert">×</button>
				 <h4>申请流程说明</h4>
				 <div>
				 <p class="muted">把你要申请的机器上的ssk-key拷贝下来，注意要用cat命令！</p>
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
				/* $("#add_key").click(function(){
					$html='<div class="control-group">'+$('.show3').html()+'</div>';
					$('#add_key_content').append($html);
				 }); */
				 $("#add_key").click(function(){
						/*$html='<div class="control-group">'+$('.show3').html()+'</div>'
						$('#add_key_content').append($html);*/
	                                        $html='<div class="control-group">'+"<div class='control-group show3'><label class='control-label' for='gitpub'>ssh-key:</label><div class='controls'><textarea id='gitpub'  name='gitpub[]' rows='5' class='span5' ></textarea><span class='icon16 icon_decline del'></span></div></div>"+'</div>'
						$('#add_key_content').append($html);
	                                });
	                                     /*更改添加*/
	                                /*新添加*/
	                                $('div').delegate('.del','click',function(){
	                                    $(this).parent().parent().parent().remove();
	                                });
	                                 /*新添加*/
					 
				$(document).delegate('.m_gitpub','blur',function(){
					if($(this).val()=='')
					{
						$(this).parents('div.control-group ').removeClass('success').addClass('error');
						//console.log($(this).siblings('span.help-block').addClass('error').text());
					}
					else
					{
						$(this).parents('div.control-group ').removeClass('error').addClass('success');
					}
					});
				$("#submit").click(function(){
					$('.m_gitpub').trigger('blur');
					if($('div.error').length==0){
				
					$.post("<?php echo base_url('index.php/git/apply_add')?>",$('#gitform').serialize(),function(data){
							layer.alert(data,9);
							$("#gitform")[0].reset();
						});
					return false;
					}
					else
					{
						layer.alert('请检查ssk-key填写',8);
						return false;
					}
					});
				
				});
		</script>
  </body>
  </html>