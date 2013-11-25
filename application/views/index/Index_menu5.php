<?php
//运维操作人员的导航菜单
?>
<div class=" span3 ">
<div data-spy="affix" data-offset-top="50">
<!-- 新效果-->
<div class="accordion span3" id="accordion2">
  <div class="accordion-group">
  	<div class="accordion-heading">
  	<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse0">
        个人中心
  	</a>
  	</div>
  	<div id="collapse0" class="accordion-body collapse">
  	<div class="accordion-inner">
  	<ul class="nav nav-list">
  	<li class="active"> <a href="<?php echo base_url('index.php/index/center')?>">我的个人信息</a></li>
  	<li> <a href="<?php echo base_url('index.php/index/center')?>">我的待办列表</a></li>
  	<li> <a href="<?php echo base_url('index.php/index/center')?>">我的申请信息</a></li>
  	<li> <a href="<?php echo base_url('index.php/index/center')?>">我的历史工单</a></li>
  	</ul>
  	</div>
  	</div>
  </div>
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
        git认证管理
      </a>
    </div>
    <div id="collapseOne" class="accordion-body collapse ">
      <div class="accordion-inner">
        <ul class="nav nav-list">
 			
			<li class="active"><?php echo anchor("/git/gitshowapply/","git认证申请","target=index_center");?></li>
 			<li><?php echo anchor("/git/mygit/","我的git认证","target=index_center");?></li>
 			<li><?php echo anchor("/git_ops/index/","git认证审批" ,'target=index_center')?></li>
 			<li><?php echo anchor("/git/alllist/","git认证管理","target=index_center");?></li>
 			<li class="divider"></li>
 			 <li><?php echo anchor("/gitgroups"," git组添加","target=index_center");?></li>
 			<li><?php echo anchor("/git_creator/alllist","我的git组审批","target=index_center");?></li>
 			<li><?php echo anchor("/groupops/alllist","git组运维审批","target=index_center");?></li>
 			<li><?php echo anchor("/gitgroups/alllist"," 我的git组","target=index_center");?></li>
 			<li><?php echo anchor("/gitgroups/groups"," git组管理","target=index_center");?></li>
	</ul>
      </div>
    </div>
  </div>
  <!--  代码上线 -->
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapsecodeonline">
        代码上线
      </a>
    </div>
    <div id="collapsecodeonline" class="accordion-body collapse ">
      <div class="accordion-inner">
        <ul class="nav nav-list">
 			<li><?php echo anchor("/codeonline_op/myapply/","代码上线处理 ","target=index_center");?></li>
 			<li><?php echo anchor("/requirements/index/","需求管理 ","target=index_center");?></li>
 			<li><?php echo anchor("/codeonline_models/index/","模块资源管理 ","target=index_center");?></li>
		</ul>
      </div>
    </div>
  </div>
<!--  代码上线结束 -->
  <!--新添加服务器模块-->
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
 服务器管理
      </a>
    </div>
    <div id="collapseThree" class="accordion-body collapse ">
      <div class="accordion-inner">
        <ul class="nav nav-list">
			<li class="active"><?php echo anchor("/server_need/index/","服务器申请","target=index_center");?></li>
 			<li><?php echo anchor("/server_manage/index/","服务器管理","target=index_center");?></li>
 			<li><?php echo anchor("/server_approve/index/","服务器审批" ,'target=index_center')?></li>
	</ul>
      </div>
    </div>
  </div>
<!--新添加服务器模块-->
  <!-- 服务管理模块 -->
   <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#fuwu">
        服务管理
      </a>
    </div>
    <div id="fuwu" class="accordion-body collapse ">
      <div class="accordion-inner">
        <ul class="nav nav-list">
        <li><?php echo anchor("/codeonline_models/index/","服务管理 ","target=index_center");?></li>
	</ul>
      </div>
    </div>
  </div>
 <!-- 服务管理模块 结束-->
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
        	<i class='icon-user'></i>用户管理
      </a>
    </div>
    <div id="collapseTwo" class="accordion-body collapse">
      <div class="accordion-inner">
      <ul class="nav nav-list nav-pills text-center">
 		<li><?php echo anchor("/auth/change_password/","修改密码","target=index_center");?></li>
		<li><?php echo anchor("/auth/logout/","<i class='icon-off'></i>安全退出");?></li>
 </ul>
  </div>
  </div>
  </div>
</div>
<!-- 新效果结束 -->
</div>
</div> 
 <script>
 $(function(){
	$("li a").click(function(){
	var href=$(this).attr('href');
	$("iframe").attr('src',href);
	$(this).parent('li').addClass('active').siblings().removeClass('active');
	return false;
		});
 });
 </script>