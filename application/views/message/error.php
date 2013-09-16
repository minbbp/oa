<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title>操成功页面</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- Bootstrap -->
    <link href="<?=base_url()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="<?=base_url()?>/bootstrap/js/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
 <div class="span8">
 <div class="row">
 <div class="offset2 span5">
  <span>提示信息</span>
  <h4> 操作失败！</h4>
  <p>页面将在 <span id="time"><?php echo $time?></span>秒后跳转，如不想等待请<?php echo anchor($url,'点击');?></p>
 </div>
 </div>
 </div>
 <script>
 var seconds=<?php echo $time?>;
 function redirect()
 {
 	$("#time").html(--seconds);
 	if(seconds==0)
 	{
 		clearInterval(icount);
 		self.location.href="<?php echo $url;?>";
 	}
 }
$(document).ready(function(){icount=setInterval('redirect()',1000);});
 </script>
 </body>
 </html>