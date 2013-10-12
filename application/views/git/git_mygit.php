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
				<h3>
					我的git 账号列表 
				</h3>
			</div>
			<table class="table table-bordered table-hover">
			<thead><tr><th>#</th><th>机器标识</th> <th>受控目录</th><th>所属git组</th><th>账号状态</th><th>申请时间</th><th>申请操作</th></tr></thead>
			<tbody>
			<?php foreach($all_gits as $git):?>
			<tr><td><?=$git->git_id?></td> 
			<td><?php echo  " <small>".anchor('git/gitkey/'.$git->git_id,'点击查看','class="showkey"').'<small>'?></td>
			<td><small><?php echo $git->cfilename==""?'等待分配':$git->cfilename;?></small></td>
			<td><?php if($git->add_datagroups!=""){echo anchor('git/showgroup/'.$git->git_id,count(explode(',', $git->add_datagroups))."个git组",'class="showgroup"');}else{echo '<small>未加入git组</small>';}?></td>
			<td>
			<?php 
			
			 if($git->git_state==1)
			{
				echo "<span class='icon-ok'></span>";
			}
			 else if($git->git_state==-1)
			{
				echo "<span class='icon-ban-circle'></span>";
			}
			else if($git->git_state==2)
			{
				echo "<span class='icon-remove'></span>";
			}
			else if($git->git_state==0)
			{
				echo "<small class='text-error'>不可用</small>";
			}
			
		
			?></td>
			<td><?=date("Y-m-d",$git->addtime==''?time():$git->addtime)?></td>
			<td><?php if($git->git_state==1)
						{
							echo anchor('git/addgroup/'.$git->git_id,'添加git组',"class='addgroup'");
							echo "&nbsp;|&nbsp;";
							echo anchor('git/addkey/'.$git->git_id,'添加新机器','class="addkey"');
						}
				?>
			</td>
			</tr>
			<?php endforeach;?>
			</tbody>
			</table>
			<?php echo $page?>
</div>
<script type="text/javascript">
$(function(){
	//处理点击查看按钮显示
	
		$('.showkey').click(function(){
			var time=new Date().getTime();
			var href=$(this).attr('href');
			//发送get 请求，获取的机器数目
			 $.get(href,{time:time},function(data){
				 if(data==0)
				{
					 layer.alert('您还没有添加机器哦！',8);
				}
				 else
				{
				 $.layer({
						type:2,
						title:'机器列表',
						area:['430px','300px'],
						shadeClose: true,
						offset:['20px',''],
						iframe:{src:href+'/'+time}
					});
				}
				 });
			
			return false;
		});
		$(".showgroup").click(function(){
			var time=new Date().getTime();
			var href=$(this).attr('href');
			  $.layer({
					type:2,
					title:'git组',
					area:['530px','300px'],
					shadeClose: true,
					offset:['20px',''],
					iframe:{src:href+'/'+time}
				});
				
			return false;
			});
		$(".addkey").click(function(){
			var time=new Date().getTime();
			var href=$(this).attr('href');
			$.layer({
				 type:2,
				 title:'添加机器',
				 area:['530px','320px'],
				 shadeClose:true,
				 offset:['20px',''],
				 border:[0,0,'',false],
				 iframe:{src:href+'/'+time}
			});
			return false;
		});
		$(".addgroup").click(function(){
			var time=new Date().getTime();
			var href=$(this).attr('href');
			$.layer({
				 type:2,
				 title:'添加机器',
				 area:['530px','280px'],
				 shadeClose:true,
				 offset:['20px',''],
				 border:[0,0,'',false],
				 iframe:{src:href+'/'+time}
			});
			return false;
		});
		
});
</script>
</body>
</html>
