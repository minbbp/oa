<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="<?php echo base_url(); ?>/bootstrap/js/jquery-1.10.2.min.js"></script>
    <script src="<?php echo base_url(); ?>/bootstrap/js/bootstrap.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
 <div class="span6">
			<div class="page-header">
				<h4><?php echo $title; ?>
				</h4>
			</div>
                        <h5>当前内网IP地址为:<?php echo $info['s_internet']; ?></h5>
			<form action="<?php echo site_url('/server_ip_mem/save_apply')?>" method="post"id="form_data">
			<label for="ports">申请端口：</label>
			<textarea rows="4" class="span5" name="ports" id="ports"></textarea>
                        <label for="description">申请描述：</label>
			<textarea rows="4" class="span5" name="description" id="description"></textarea><span id="descerror" ></span>
                        <input type="hidden" name="s_id" value="<?php echo $s_id ?>">
                         <input type="hidden" name="se_type" value="1">
			<label >
			<a href="javascript::void(0)" class="btn btn-danger" id="reset" >取消</a>&nbsp;&nbsp;&nbsp;
			<input type="submit" id="submit" class="btn btn-primary " value="保存"/>
			 </label>
			</form>
</div>
<script type="text/javascript">
    $(function(){
        $('#reset').click(function(){
            history.back();
    })
          $('#description').blur(function(){
           if($(this).val()==''){
               $('#descerror').text("不能为空");
           }else{
               $('#descerror').text('');
           }
       })
       /*
    	$("#submit").click(function(){
        $('form textarea').trigger('blur');
        //使用ajax提交表单
        var href=$('form').attr('action');
         $.post(href,$('form').serialize(),function(json_data){
               if(json_data.status==1)
                {
                    layer.alert(json_data.msg,9,'成功提示！',function(){
                        location.reload();
                    });
                }else{
                    layer.alert(json_data.msg,8,'错误提示！');
                }
                 },'json');
         return false;
	});    
        */
    })
</script>
</body>
</html>