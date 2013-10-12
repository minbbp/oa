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
 <div class="span6">
			<div class="page-header">
				<h3>
					git 认证操作说明_<?php echo $title?>
				</h3>
			</div>
			 <div>
			 <table class="table table-bordered">
			   <tr><th>#</th><th>内容</th><th>操作</th></tr>
			 <tr>
			 <td>用户名</td><td><?php echo $gituser['realname'];?></td><td></td>
			 </tr>
			 <tr>
			 <td>受控目录</td><td><?php echo $gits['cfilename']==""?'<small>未分配</small>':$gits['cfilename'];?></td><td><?php if($gits['cfilename']==""){echo "<small class='text-error'>后续分配</small>";}?></td>
			 </tr>
			 <tr>
			 <td>已入的git组</td><td>
			  <?php 
			  if(!empty($oldgroups))
				{
					$str="";
					foreach($oldgroups as $group)
					{
						$str.=$group['group_name'].",";
					}
					echo $str=substr($str, 0,-1);
				}
			  ?>
			 </td>
			 <td>
			 
			 </td>
			 </tr>
			 <tr>
			 <td>要添加的git组</td>
			 <td>
			  <?php 
			  if(!empty($addgroups))
				{
					$str="";
					foreach($addgroups as $group)
					{
						$str.=$group['group_name'].",";
					}
					echo $str2=substr($str, 0,-1);
				}
			  ?>
			 </td>
			 <td>
			 <?php 
			 if($str2!=""){echo "<small class='text-error'>新增</small>";}
			 ?>
			 </td>
			 </tr>
			 <tr>
			 <td>已分配机器标识</td>
			 <td>
			  <?php 
			  if(!empty($keys))
			 {
			 	//输出已经可以用的机器
			 	$keystr="";
				foreach($keys as $k)
				{
					if($k['key_state']==1)
					{
					 $keystr.=$k['git_auth'].",";
					}
				} 	
				echo substr($keystr,0,-1);
			 }
			  ?>
			 </td>
			 <td></td>
			 </tr>
			 <tr> <td>未分配的机器</td>
			 <td><?php
			 if(!empty($keys))
			 {
			 	//输出已经可以用的机器
			 	$keystrnone="";
			 	$tmp=array();
			 	foreach($keys as $k)
			 	{
			 		if($k['key_state']==0)
			 		{
			 			$keystrnone.=$k['git_auth'].$k['gitpub']."<br/>";
			 			array_push($tmp, $k['gitpub']);
			 		}
			 	}
			 	echo  $keynone=substr($keystrnone,0,-5)."<br/>".$gits_oprs['gitpub'];
			 }
			 ?></td>
			 <td>
			   <?php
			  
			   if($keynone!="")
				{
					echo anchor('git_ops/upfile/'.$gits_oprs['gits_opid'].'/'.$gits_oprs['apply_type'],'上传文件','class="upfile btn btn-primary"');
				}
			   ?>
			 </td>
			 </tr>
			 </table>
			 </div>
</div>
<script>
$(function(){
	$(".upfile").click(function(){
    var href=$(this).attr('href');
    $.post(href,function(data){
    layer.alert(data);
        });
    return false;
		});
});
</script>
</body>
</html>