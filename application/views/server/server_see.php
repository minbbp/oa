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
                            <h3><?php echo $title?></h3>
			</div>
				<?php echo form_open("server_manage/server_edit",array('class'=>'form-horizontal','id'=>'serverform'))?>
                                    <div class="control-group">
                                      <label class="control-label" for="inputCpu">Cpu</label>
                                      <div class="controls">
                                        <input type="text" name="cpu" id="inputCpu" placeholder="Cpu" disabled  value="<?php echo $info['s_cpu']; ?>"/><?php echo form_error('cpu'); ?>
                                        <span class="help-inline"></span>
                                      </div>
                                    </div>
                                    <div class="control-group">
                                      <label class="control-label" for="inputMem">Mem</label>
                                      <div class="controls">
                                          <input type="text" name="mem" id="inputMem"  placeholder="内存" disabled value="<?php echo $info['s_mem']; ?>"/><?php echo form_error('mem'); ?>
                                          <span class="help-inline"></span>
                                      </div>
                                    </div>
                                    <div class="control-group">
                                      <label class="control-label" for="inputDisk">Disk</label>
                                      <div class="controls">
                                        <input type="text" name="disk" id="inputDisk" placeholder="硬盘" disabled value="<?php echo $info['s_disk']; ?>"/><?php echo form_error('disk'); ?>
                                        <span class="help-inline"></span>
                                      </div>
                                    </div>
                                    <div class="control-group">
                                      <label class="control-label" for="input_internet">internet</label>
                                      <div class="controls">
                                          <input type="text" name="internet" id="input_internet"  placeholder="internet" disabled value="<?php echo $info['s_internet']; ?>"/>
                                          <span class="help-inline"></span>
                                      </div>
                                    </div>
                                    <div class="control-group">
                                      <label class="control-label" >Isp</label>
                                      <div class="controls">
                                             <label class="radio inline ">
                                                <input type="radio"  name="isp" disabled id="inlineRadio3" value="电信" <?php if($info['s_isp'] == '电信'){echo "checked";} ?>> 电信
                                              </label>
                                              <label class="radio inline ">
                                                <input type="radio"  name="isp" disabled id="inlineRadio4" value="联通" <?php if($info['s_isp'] == '联通'){echo "checked";} ?>> 联通
                                              </label>
                                      </div>
                                    </div>
                                      <div class="control-group">
                                      <label class="control-label" >用途</label>
                                           <div class="controls">
                                           <?php foreach($use_list as $k => $value){ ?>
                                             <label class="radio inline ">
                                                <input type="radio"  name="use" disabled id="radio<?php echo $k ?>" value="<?php echo $k ?>" <?php if($k ==$info['s_use']){ echo "checked";} ?>><?php echo $value ?>
                                             </label>
                                          <?php } ?>
                                      </div>
                                       </div>
     
                                        <div id ="serv" <?php if($info['s_use'] == 1 || $info['s_use'] == 2){echo "style='display:none'";} ?>>
                                         <div class="row" >
                                        <div class="span3">
                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;可申请服务：</label>
                                        <select multiple="multiple"  id="test_a" disabled name="server_update" size="8">
                                         <?php foreach($list as $value){ ?>
                                        <option value="<?php echo $value['st_id'] ?>"><?php echo $value['st_name'] ?></option>
                                        <?php } ?>
                                        </select>
                                        </div>
                                        <div class="span2 text-center">
                                         <p style="margin-top:30px; "><span class="btn add">添加&gt;&gt;</span></p>
                                         <p style="margin-top:20px; "><span class="btn btn-danger remove">&lt;&lt;移除</span></p>
                                        </div>
                                        <div class="span3">
                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;已存在服务：</label>
                                            <select multiple="multiple" disabled name="type[]" id="test_b" size="8">
                                            </select></div>
                                        </div>
                                        <br />
                                        <br />
                                        </div>
     

     
                                         <div id ="server_owner" >
                                         <div class="row" >
                                        <div class="span3">
                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;移除：</label>
                                        <select multiple="multiple"  id="test_owner" name="owner[]" disabled name="" size="8">
                                        </select>
                                        </div>
                                        <div class="span2 text-center">
                                         <p style="margin-top:30px; "><span class="btn add">添加&gt;&gt;</span></p>
                                         <p style="margin-top:20px; "><span class="btn btn-danger remove">&lt;&lt;移除</span></p>
                                        </div>
                                        <div class="span3">
                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;服务器使用人：</label>
                                            <select multiple="multiple" disabled  id="" size="8">
                                        <?php foreach($list_owner as $value){ ?>
                                        <option value="<?php echo $value['so_id'] ?>"><?php echo $value['account'] ?></option>
                                        <?php } ?>
                                            </select></div>
                                        </div>
                                        <br />
                                        <br />
                                        </div>
     
                                         <div class="control-group">
                                        <label class="control-label" for="textarea-desc">服务器作用描述</label>
                                        <div class="controls">
                                            <textarea rows="5" class="span4" name="desc" id="textarea-desc" disabled value=""><?php echo $info['s_desc']; ?></textarea><?php echo form_error('desc'); ?>   
                                            <span id="descerror"></span>
                                        </div>
                                        </div>
     
                                         <input type="hidden" name="s_id" value="<?php echo $info['s_id']?>">
                                    <div class="control-group">
                                      <div class="controls">
                                        <input type="button" class="btn" id="edit" value=" 编辑 " />
                                      </div>
                                      <div class="controls">
                                        <input type="submit" class="btn" id="submit" style="display: none" value="提交编辑" />
                                      </div> 
                                    </div>
                    		<?php echo form_close();?>
		</div>
      <script type="text/javascript">
         
      $(function(){
        
        var $dtmp="<?php echo '['.$info['s_type'].']' ?>";
	$("#test_a option").each(function(i,n){
		var dd=$(n);
		if($.inArray(dd.val(),$dtmp)!= -1)
		{
			dd.appendTo($('#test_b'));
		}
		});

        for(var i =1;i<=4;i++){
              if(i<=2){
                  $('#radio'+i).click(function(){
                      $('#serv').hide(500);
                     $('#test_b option').appendTo($('#test_a'));
                  })
              }else{
                  $('#radio'+i).click(function(){
                      $('#serv').show(500)
                  })
              }
          }
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
        
        
        
        $('#textarea-desc').blur(function(){
           if($(this).val()==''){
               $('#descerror').text("必须输入作用描述");
           }else{
               $('#descerror').text('');
           }
       })
       $('#edit').click(function(){
             $("input").attr("disabled",false);
             $("select").attr("disabled",false);
             $("textarea").attr("disabled",false);
             $("select").attr("disabled",false);
             $('#submit').show(500,function(){ $('#edit').hide(500) });
       })
        var elment=['#inputCpu','#inputMem','#inputDisk'];
	var msg=['cpu信息填写错误!','内存填写错误!','硬盘填写错误！'];
	validate_callback(elment,msg);
	$("#submit").click(function(){
$('form input').trigger('blur');
$('form textarea').trigger('blur');
$('#test_b option').attr("selected",'selected');
$('#test_owner option').attr("selected",'selected');
if($('#textarea-desc').val() == ''){ return false; }
        //使用ajax提交表单
        var href=$('form').attr('action');
         $.post(href,$('form').serialize(),function(json_data){
               if(json_data.status==1)
                {
                    layer.alert(json_data.msg,9,'成功提示！',function(){
                        location.href="<?php echo site_url('server_manage/index') ?>";
                    });
                }else{
                    layer.alert(json_data.msg,8,'错误提示！');
                }
                 },'json');
         return false;
	});
});
function validate(element,msg)
 {
	 $(element).blur(function(){
			if($(this).val()=='' || isNaN($(this).val()) )
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

      </script>

  </body>
  </html>