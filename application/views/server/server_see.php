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
                                        <input type="text" name="cpu" id="inputCpu" placeholder="Cpu"   value="<?php echo $info['s_cpu']; ?>"/><?php echo form_error('cpu'); ?>
                                        <span class="help-inline"></span>
                                      </div>
                                    </div>
                                    <div class="control-group">
                                      <label class="control-label" for="inputMem">Mem</label>
                                      <div class="controls">
                                          <input type="text" name="mem" id="inputMem"  placeholder="内存"  value="<?php echo $info['s_mem']; ?>"/><?php echo form_error('mem'); ?>
                                          <span class="help-inline"></span>
                                      </div>
                                    </div>
                                    <div class="control-group">
                                      <label class="control-label" for="inputDisk">Disk</label>
                                      <div class="controls">
                                        <input type="text" name="disk" id="inputDisk" placeholder="硬盘"  value="<?php echo $info['s_disk']; ?>"/><?php echo form_error('disk'); ?>
                                        <span class="help-inline"></span>
                                      </div>
                                    </div>
                                     <div class="control-group inter">
                                      <label class="control-label" for="input_internet">内网IP</label>
                                      <div class="controls">
                                          <input type="text" name="internet" id="input_internet" class="ipcheck" placeholder="内网ip地址"  value="<?php echo $info['s_internet']; ?>"/>
                                          <span class="help-inline"></span>
                                          <span class="help-inline"><a href="" class="internei ia icon16 icon_add" ></a></span>
                                      </div>                    
                                    </div>
                                      <?php foreach ($ipnei_list as $value) { ?>
                                            <div class='control-group inter'>
                                                    <label class='control-label' for='input_internet'>内网IP</label><div class='controls'>
                                                        <input  type='text'  class="ipcheck" name='internetnei[]' id='input_internet'  placeholder='内网ip地址'  value='<?php echo $value['si_ip'] ?>'/><span class='help-inline'></span>
                                                        <span class='help-inline'><a href='' class='internei ia icon16 icon_add' ></a></span>
                                            <span class='help-inline'><a href='' class='interb ia icon16 icon_delete'></a></span></div></div>
                                       <?php  } ?>
                                    <div class="control-group inter">
                                      <label class="control-label" for="input_winternet">外网IP</label>
                                      <div class="controls">
                                          <input type="text" name="winternet" id="input_winternet" class="ipchecks"  placeholder="外网ip地址"  value="<?php echo $info['s_winternet']; ?>"/>
                                          <span class="help-inline"></span>
                                          <span class="help-inline"><a href="" class="interwai ia icon16 icon_add"  ></a></span>
                                      </div>                    
                                    </div>

                                        
                                        <?php foreach ($ipwai_list as $value) { ?>
                                            <div class='control-group inter'>
                                                    <label class='control-label' for='input_internet'>外网IP</label>
                                                    <div class='controls'>
                                                    <input  type='text' name='internetwai[]' id='input_internet'  class="ipcheck" placeholder='内网ip地址'  value='<?php echo $value['si_ip'] ?>'/>
                                                    <span class='help-inline'></span><span class='help-inline'>
                                                        <a href='' class='interwai ia icon16 icon_add'></a></span>
                                                    <span class='help-inline'><a href='' class='interb ia icon16 icon_delete' ></a></span></div></div>
                                      <?php  } ?>
                                    <div class="control-group">
                                      <label class="control-label" >Isp</label>
                                      <div class="controls">
                                             <label class="radio inline ">
                                                <input type="radio"  name="isp"  id="inlineRadio3" value="电信" <?php if($info['s_isp'] == '电信'){echo "checked";} ?>> 电信
                                              </label>
                                              <label class="radio inline ">
                                                <input type="radio"  name="isp"  id="inlineRadio4" value="联通" <?php if($info['s_isp'] == '联通'){echo "checked";} ?>> 联通
                                              </label>
                                              <label class="radio inline ">
                                                <input type="radio"  name="isp"  id="inlineRadio5" value="0" <?php if($info['s_isp'] == '不需要'){echo "checked";} ?>> 不需要
                                              </label>
                                      </div>
                                    </div>
     
                                      <div class="control-group">
                                      <label class="control-label" >用途</label>
                                           <div class="controls">
                                           <?php foreach($use_list as $k => $value){ ?>
                                             <label class="radio inline ">
                                                <input type="radio"  name="use"  id="radio<?php echo $k ?>" value="<?php echo $k ?>" <?php if($k ==$info['s_use']){ echo "checked";} ?>><?php echo $value ?>
                                             </label>
                                          <?php } ?>
                                      </div>
                                       </div>
     
                                        <div id ="serv" <?php if($info['s_use'] == 1 || $info['s_use'] == 2){echo "style='display:none'";} ?>>
                                         <div class="row" >
                                        <div class="span3">
                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;可申请服务：</label>
                                        <select multiple="multiple"  id="test_a"  name="server_update" size="8">
                                         <?php foreach($list as $value){ ?>
                                        <option value="<?php echo $value['m_id'] ?>"><?php echo $value['m_name'] ?></option>
                                        <?php } ?>
                                        </select>
                                        </div>
                                        <div class="span2 text-center">
                                         <p style="margin-top:30px; "><span class="btn add">添加&gt;&gt;</span></p>
                                         <p style="margin-top:20px; "><span class="btn btn-danger remove">&lt;&lt;移除</span></p>
                                        </div>
                                        <div class="span3">
                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;已存在服务：</label>
                                            <select multiple="multiple"  name="type[]" id="test_b" size="8">
                                            </select></div>
                                        </div>
                                        <br />
                                        <br />
                                        </div>
                                          <?php if($list_owner){ ?>
                                         <div id ="server_owner" >
                                         <div class="row" >
                                        <div class="span3">
                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;移除：</label>
                                        <select multiple="multiple"  id="test_owner" name="owner[]"  name="" size="8">
                                        </select>
                                        </div>
                                        <div class="span2 text-center">
                                         <p style="margin-top:30px; "><span class="btn add">添加&gt;&gt;</span></p>
                                         <p style="margin-top:20px; "><span class="btn btn-danger remove">&lt;&lt;移除</span></p>
                                        </div>
                                        <div class="span3">
                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;服务器使用人：</label>
                                            <select multiple="multiple"   id="" size="8">
                                        <?php foreach($list_owner as $value){ ?>
                                        <option value="<?php echo $value['so_id'] ?>"><?php echo $value['account'] ?></option>
                                        <?php } ?>
                                            </select></div>
                                        </div>
                                        <br />
                                        <br />
                                        </div>
                                          <?php } ?>
                                         <div class="control-group">
                                        <label class="control-label" for="textarea-desc">服务器作用描述</label>
                                        <div class="controls">
                                            <textarea rows="5" class="span4" name="desc" id="textarea-desc"  value=""><?php echo $info['s_desc']; ?></textarea><?php echo form_error('desc'); ?>   
                                            <span id="descerror"></span>
                                        </div>
                                        </div>
     
                                         <input type="hidden" name="s_id" value="<?php echo $info['s_id']?>">
                                    <div class="control-group">
                                      <div class="controls">
                                        <input type="submit" class="btn btn-success " id="submit"  value="提交编辑" />
                                      </div> 
                                    </div>
                    		<?php echo form_close();?>
		</div>
      <script type="text/javascript">
         
      $(function(){
        $('form').delegate('.internei','click',function(){
         var c = $(this).parent().parent().parent();
        var str = "<div class='control-group inter'><label class='control-label' for='input_internet'>内网IP</label><div class='controls'><input class='ipcheck' type='text' name='internetnei[]' id='input_internet'  placeholder='内网ip地址'  value=''/><span class='help-inline'></span><span class='help-inline'><a href='' class='internei icon16 icon_add' ></a></span><span class='help-inline'><a href='' class='interb icon16 icon_delete' ></a></span></div></div>";
        c.after(str);
                return false;
        })
       $('form').delegate('.interwai','click',function(){
         var c = $(this).parent().parent().parent();
        var str = "<div class='control-group inter'><label class='control-label' for='input_internet'>外网IP</label><div class='controls'><input class='ipcheck' type='text' name='internetwai[]' id='input_internet'  placeholder='内网ip地址'  value=''/><span class='help-inline'></span><span class='help-inline'><a href='' class='interwai icon16 icon_add' ></a></span><span class='help-inline'><a href='' class='interb icon16 icon_delete' ></a></span></div></div>";
        c.after(str);
         return false;
        })
        $('form').delegate('.interb','click',function(){
        $(this).parent().parent().parent().remove();
                return false;
        })
        <?php $arr = explode(',', $info['s_type']); 
                $testtmp="[";
                foreach ($arr as $t)
                {
                        $testtmp.="'".$t."',";
                }
                $testtmp=substr($testtmp, 0,-1);
                $testtmp.="]";
        ?>
        var $dtmp=<?php echo $testtmp=="]"?'[]':$testtmp;?>;
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
        
           function checkIP(value){
            var exp=/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
            var reg = value.match(exp);
            if(reg==null)
            {
            return false;
            }else{
            return true;
            }
        }
        
        $('form').delegate('.ipcheck','blur',function(){
            var bool;
            bool = checkIP($(this).val());
            var newip = $(this).val();
            var obj = $(this);
            var oldip = "<?php echo $info['s_internet']; ?>";
            href = "<?php echo site_url('server_manage/server_check_ip');?>"+'/'+$(this).val();
           if(bool){
           $.get(href,function(data){
              if(data == 0){
                obj.next().text("");
                obj.parents('.control-group').removeClass('error').addClass('success');
              }else{
                  if(newip != oldip){
                  obj.next().text("ip地址冲突");
                  obj.parents('.control-group').removeClass('success').addClass('error');
                  }
              }
           },'json')
           }else{
               $(this).next().text("ip地址填写错误");
               $(this).parents('.control-group').removeClass('success').addClass('error');
           }
        })
        
         $('.ipchecks').blur(function(){
             if($(this).val() !=''){
             var bool;
           bool = checkIP($(this).val());
                if(bool){
                     $(this).next().text("");
                     $(this).parents('.control-group').removeClass('error').addClass('success');
                }else{
                    $(this).next().text("ip地址填写错误");
                    $(this).parents('.control-group').removeClass('success').addClass('error');
                }
            }else{
                     $(this).next().text("");
                     $(this).parents('.control-group').removeClass('error').addClass('success');
            }
            })
        $('#textarea-desc').blur(function(){
           if($(this).val()==''){
               $('#descerror').text("必须输入作用描述");
               $(this).parents('.control-group').removeClass('success').addClass('error');
           }else{
               $('#descerror').text('');
               $(this).parents('.control-group').removeClass('error').addClass('success');
           }
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
              $('.ipcheck').trigger('blur');
               if($('.error').length != 0){return false;}
        
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