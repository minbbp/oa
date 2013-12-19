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
				<?php echo form_open("server_need/add_apply",array('class'=>'form-horizontal','id'=>'serverform'))?>
                                    <div class="control-group">
                                      <label class="control-label" for="inputCpu">Cpu :</label>
                                      <div class="controls">
                                        <input type="text" name="cpu" id="inputCpu" placeholder="Cpu"  value="<?php echo set_value('cpu'); ?>"/><?php echo form_error('cpu'); ?>
                                        <span class="help-inline"></span>
                                      </div>
                                    </div>
                                    <div class="control-group">
                                      <label class="control-label" for="inputMem">Mem :</label>
                                      <div class="controls">
                                          <input type="text" name="mem" id="inputMem"  placeholder="内存"  value="<?php echo set_value('mem'); ?>"/> G<?php echo form_error('mem'); ?>
                                          <span class="help-inline"></span>
                                      </div>
                                    </div>
                                    <div class="control-group">
                                      <label class="control-label" for="inputDisk">Disk :</label>
                                      <div class="controls">
                                        <input type="text" name="disk" id="inputDisk" placeholder="硬盘"  value="<?php echo set_value('disk'); ?>"/> G<?php echo form_error('disk'); ?>
                                        <span class="help-inline"></span>
                                      </div>
                                    </div>
                                    <div class="control-group">
                                      <label class="control-label" >Internet :</label>
                                      <div class="controls">
                                             <label class="radio inline ">
                                                <input type="radio"  name="internet" id="inlineRadio1" value="1"> 需要
                                              </label>
                                              <label class="radio inline ">
                                                <input type="radio"  name="internet" id="inlineRadio2" value="2" checked> 不需要
                                              </label>
                                      </div>
                                    </div>
                                    <div class="control-group" id="sisp" style="display: none">
                                      <label class="control-label" >Isp :</label>
                                      <div class="controls">
                                             <label class="radio inline ">
                                                <input type="radio"  name="isp" id="inlineRadio3" value="电信"> 电信
                                              </label>
                                              <label class="radio inline ">
                                                <input type="radio"  name="isp" id="inlineRadio4" value="联通" checked> 联通
                                              </label>
                                      </div>
                                    </div>
                                        <div class="control-group">
                                      <label class="control-label" >用途 :</label>
                                           <div class="controls">
                                           <?php foreach($use_list as $k => $value){ ?>
                                             <label class="radio inline ">
                                                <input type="radio"  name="use" id="radio<?php echo $k ?>" value="<?php echo $k ?>" <?php if($k ==1){ echo "checked";} ?>><?php echo $value ?>
                                             </label>
                                          <?php } ?>
                                      </div>
                                       </div>
                                        
                                       <div id ="serv" style="display: none">
                                         <div class="row" >
                                        <label>&nbsp;&nbsp;&nbsp;&nbsp;申请服务 ：</label>
                                        <div class="span3">
                                        <select multiple="multiple" id="test_a"  name="server_update" size="8">
                                         <?php foreach($list as $value){ ?>
                                        <option value="<?php echo $value['m_id'] ?>"><?php echo $value['m_name'] ?></option>
                                        <?php } ?>
                                        </select>
                                        </div>
                                        <div class="span2 text-center">
                                         <p style="margin-top:30px; "><span class="btn add">添加&gt;&gt;</span></p>
                                         <p style="margin-top:20px; "><span class="btn btn-danger remove">&lt;&lt;移除</span></p>
                                        </div>
                                        <div class="span3"><select multiple="multiple" name="type[]" id="test_b" size="8"></select></div>
                                        </div>
                                        <br />
                                        <br />
                                        </div>
     
                                        <div class="control-group">
                                        <label class="control-label" for="textarea-desc">服务器作用描述 :</label>
                                        <div class="controls">
                                            <textarea rows="10" class="span5" name="desc" id="textarea-desc" value="<?php echo set_value('desc'); ?>"></textarea><?php echo form_error('desc'); ?>   
                                            <span id="descerror"></span>
                                        </div>
                                        </div>
                                     <div class="control-group">
                                      <label class="control-label" for="inputNum">申请台数 :</label>
                                      <div class="controls">
                                        <input type="text" name="num" id="inputNum" placeholder="几台"  value="<?php echo set_value('num'); ?>"/><?php echo form_error('num'); ?>台
                                        <span class="help-inline"></span>
                                      </div>
                                    </div>
                                    <div class="control-group">
                                      <div class="controls">
                                        <input type="submit" class="btn btn-success" id="submit" value="提交申请" />
                                      </div>
                                    </div>
                    		<?php echo form_close();?>
		</div>
      <script type="text/javascript">
      $(function(){
          $('#inlineRadio1').click(function(){
              $('#sisp').show(500);
          })
         $('#inlineRadio2').click(function(){
              $('#sisp').hide(500);
          })
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
               $(this).parents('.control-group').removeClass('success').addClass('error');
           }else{
               $('#descerror').text('');
               $(this).parents('.control-group').removeClass('error').addClass('success');
           }
       })
        var elment=['#inputCpu','#inputMem','#inputDisk','#inputNum'];
	var msg=['cpu信息填写错误!','内存填写错误!','硬盘填写错误！','台数填写错误！'];
	validate_callback(elment,msg);
	$("#submit").click(function(){
        $('form input').trigger('blur');
        $('form textarea').trigger('blur');
        $('#test_b option').attr("selected",'selected');
        if($('.error').length != 0){return false;}
        //使用ajax提交表单
        var href=$('form').attr('action');
         $.post(href,$('form').serialize(),function(json_data){
               if(json_data.status==1)
                {
                    layer.alert(json_data.msg,9,'成功提示！',function(){
                        location.reload();
                    });
                    //$("form")[0].reset();
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