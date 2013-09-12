<div class="offset1 span3 text-center">
<div data-spy="affix" data-offset-top="50">
<!-- 新效果-->
<div class="accordion span3" id="accordion2">
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
        git账号管理
      </a>
    </div>
    <div id="collapseOne" class="accordion-body collapse ">
      <div class="accordion-inner">
        <ul class="nav nav-list nav-pills text-center ">
 			<?php if(!$admin):?>
 			<li><?php if(in_array("'/git/gitshowapply/'",$url)){ echo anchor("/git/gitshowapply/","git账号申请","target=index_center");}?></li>
 			<li><?php if(in_array("'/git/mygit/'",$url)) {echo anchor("/git/mygit/","我的git账号","target=index_center");}?></li>
 			<li><?php if(in_array("'/git/alllist/'",$url)){ echo anchor("/git/alllist/","git账号管理","target=index_center");}?></li>
 			 <?php if($userinfo['pid']==0 && $userinfo['level']==1):?>
			<li><?php echo anchor("/git/h_level_apply","审批git账号" ,'target=index_center')?></li>
				<?php endif; ?>
				<?php else:?>
			<li><?php echo anchor("/git/gitshowapply/","git账号申请","target=index_center");?></li>
 			<li><?php echo anchor("/git/mygit/","我的git账号","target=index_center");?></li>
 			<li><?php echo anchor("/git/alllist/","git账号管理","target=index_center");?></li>
 			<li><?php echo anchor("/git/h_level_apply","审批git账号" ,'target=index_center')?></li>
 			<li><?php echo anchor("/git/git_total/","git账号操作统计","target=index_center");?></li>
			<?php endif; ?>
	</ul>
      </div>
    </div>
  </div>
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
        	<i class='icon-user'></i>用户管理
      </a>
    </div>
    <div id="collapseTwo" class="accordion-body collapse">
      <div class="accordion-inner">
      <ul class="nav nav-list nav-pills text-center">
 		<?php if($admin):?>
 			<li><?php echo anchor("/backend/","用户管理","target=index_center");?></li>
 			<li><?php echo anchor("/backend/roles/","角色定义","target=index_center");?></li>
 			<li><?php echo anchor("/backend/uri_permissions/","角色权限","target=index_center");?></li>
 		<?php endif;?>
 		<li><?php echo anchor("/auth/change_password/","修改密码","target=index_center");?></li>
		<li><?php echo anchor("/auth/logout/","<i class='icon-off'></i>安全退出");?></li>
 </ul>
  </div>
  </div>
  </div>
  <!-- git用户组管理 -->
   <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapsethree">
        	git用户组管理
      </a>
    </div>
    <div id="collapsethree" class="accordion-body collapse">
      <div class="accordion-inner">
      <ul class="nav nav-list nav-pills text-center">
 		<?php if($admin):?>
 			<li><?php echo anchor("/gitgroups"," 用户组添加","target=index_center");?></li>
 			<li><?php echo anchor("/gitgroups/alllist"," 用户组人员管理","target=index_center");?></li>
 			<li><?php echo anchor("/grouplevel/alllist","主管审批","target=index_center");?></li>
 			<li><?php echo anchor("/groupcreator/alllist","我的git组审批","target=index_center");?></li>
 			<li><?php echo anchor("/groupops/alllist","git组操作","target=index_center");?></li>
 		<?php else:?>
 		<li><?php  if(in_array("'/gitgroups/'",$url)){echo anchor("/gitgroups"," 用户组添加","target=index_center");}?></li>
 		<li><?php  if(in_array("'/gitgroups/alllist/'",$url)){echo anchor("/gitgroups/alllist"," 用户组人员管理","target=index_center");}?></li>
 		<li><?php  if(in_array("'/grouplevel/alllist/'",$url)){echo anchor("/grouplevel/alllist","审批申请","target=index_center");}?></li>
 		<li><?php  if(in_array("'/groupcreator/alllist/'",$url)){echo anchor("/groupcreator/alllist","我的git 组审批","target=index_center");}?></li>
 		<li><?php  if(in_array("'/groupops/alllist/'",$url)){ echo anchor("/groupops/alllist","git组操作","target=index_center");}?></li>
 		<?php endif;?>
 </ul>
  </div>
  </div>
  </div>
  <!-- git用户组管理结束 -->
</div>
<!-- 新效果结束 -->
</div>
</div> 