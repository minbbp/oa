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
 <div class="span8 offset1">
			<div class="page-header">
				<h3>
					git账号列表 
					<form class="form-search pull-right" action='<?php echo base_url('index.php/git/git_search');?>' method='post'>
  					<input type="text" placeholder="输入用户名进行查找..." id="username" name="username" class="input-medium search-query">
  					<button type="submit" id='username_submit' class="btn">搜索</button>
				    </form>
					
				</h3>
			</div>
			<div class="span7">
			<?php 
			if($state===0)
			{
				echo "<h4>没有你搜索的用户名 $user</h4><br/>的认证信息";
			}
			else if($state===1)
			{
				echo "<h4>您搜索的用户名 $user,用户的git认证信息如下</h4><br/>";
			}
			?>
			 <?php if($state===0 ||$state===1):?>
			 <?php else:?>
			<ul class="nav nav-pills">
			<li <?php if($git_state==1):?>class="active"<?php endif;?>><?php echo anchor('git/alllist/1','可用git认证');?></li>
			<li <?php if($git_state==2):?>class="active"<?php endif;?>><?php echo anchor('git/alllist/2','禁用git认证');?></li>
			<li <?php if($git_state==-1):?>class="active"<?php endif;?>><?php echo anchor('git/alllist/-1','删除git认证');?></li>
			<li <?php if($git_state==0):?>class="active"<?php endif;?>><?php echo anchor('git/alllist/0','新申请git认证');?></li>
			</ul>
			<?php endif;?>
			</div>
			<table class="table table-bordered table-hover">
			<thead><tr><th>#</th><th>用户</th><th>机器标识</th> <th>受控目录</th><th>所属git组</th><th>账号状态</th><th>申请时间</th><th>申请操作</th></tr></thead>
			<tbody>
			<?php foreach($all_gits as $git):?>
			<tr><td><?=$git->git_id?></td> 
			<td><?=$git->realname?></td> 
			<td><?php echo  " <small>".anchor('git/gitkey/'.$git->git_id,'点击查看','class="showkey"').'<small>'?></td>
			<td><small><?php echo $git->cfilename==""?'等待分配':$git->cfilename;?></small></td>
			<td><?php if($git->add_datagroups!=""){echo anchor('git/showgroup/'.$git->git_id,count(explode(',', $git->add_datagroups))."个git组",'class="showgroup"');}else{echo '<small>未加入git组</small>';}?></td>
			<td>
			<?php 
			
			 if($git->git_state==1)
			{
				echo "<span class='icon16 icon_tick'></span>";
			}
			 else if($git->git_state==-1)
			{
				echo "<span class='icon16 icon_cross'></span>";
			}
			else if($git->git_state==2)
			{
				echo "<span class='icon16 icon_delete'></span>";
			}
			else if($git->git_state==0)
			{
				echo "<small class='icon16 icon_key'></small>";
			}
			
		
			?></td>
			<td><?=date("Y-m-d",$git->addtime==''?time():$git->addtime)?></td>
			<td>
			<?php
			if($git->git_state==1)
			{
			 	echo anchor('git/git_disable/'.$git->git_id,'禁用','class="disable"');
			 	echo "&nbsp;&nbsp;|&nbsp;&nbsp;";
			 	echo anchor('git/git_delete/'.$git->git_id,'删除','class="delete"');
			 }
			 if($git->git_state==2)
			{
				echo anchor('git/git_delete/'.$git->git_id,'删除','class="delete"');
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
	
	
		$(".disable").click(function(){
			var href=$(this).attr('href');
			var time=new Date().getTime();
			$.layer({
			    shade : [0], //不显示遮罩
			    area : ['auto','auto'],
			    dialog : {
			        msg:'确定要禁用吗？',
			        btns : 2, 
			        type : 4,
			        btn : ['确定','取消'],
			        yes : function(){
			           $.get(href,{time:time},function(data){
				            data=JSON.parse(data);
				            if(data.state==1)
					          {
						          $("a[href='"+href+"']").parents('tr').remove();
						      }
							layer.msg(data.msg,2,1);
							
				           });
			        },
			        no : function(){
			            layer.msg('取消了禁用!',1,5);
			        }
			    }
			});
			return false;
			});
		$(".delete").click(function(){
			var href=$(this).attr('href');
			var time=new Date().getTime();
			$.layer({
			    shade : [0], //不显示遮罩
			    area : ['auto','auto'],
			    dialog : {
			        msg:'确定要删除吗？',
			        btns : 2, 
			        type : 4,
			        btn : ['确定','取消'],
			        yes : function(){
			           $.get(href,{time:time},function(data){
				            data=JSON.parse(data);
				            if(data.state==1)
					          {
						          $("a[href='"+href+"']").parents('tr').remove();
						      }
							layer.msg(data.msg,2,1);
				           });
			        },
			        no : function(){
			            layer.msg('取消了删除!',1,5);
			        }
			    }
			});
			return false;
			});
		$("#username_submit").click(function(){
			if($('#username').val()=="")
			{
				layer.alert('用户名不能为空！',8,'错误提示');
				return false;
			}
			});
		
});
</script>
</body>
</html>
