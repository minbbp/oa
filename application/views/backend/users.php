<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title>用户管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=base_url()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="<?=base_url()?>/bootstrap/js/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
 <div class="span8 offset1">
<div class="showmsg">
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">用户信息</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    
  </div>
</div>
</div>
	<?php  				
		// Show reset password message if exist
		if (isset($reset_message))
			echo $reset_message;
		
		// Show error
		echo validation_errors();
		
		$this->table->set_heading('', ' 用户名', '邮件', '角色', '禁用','真实姓名', '操作');
		
		foreach ($users as $user) 
		{
			$banned = ($user->banned == 1) ? 'Yes' : 'No';
			$button=anchor('backend/m_update/'.$user->id,'修改' )."&nbsp;&nbsp;".anchor('backend/m_showinfo/'.$user->id,'查看详细',"class='showinfo' " );
			$this->table->add_row(
				form_checkbox(array('name'=>"checkbox_$user->id",'class'=>'update'),$user->id),
				$user->username, 
				$user->email, 
				$user->role_name, 			
				$banned, 
				$user->realname
				,
				$button
				);
		}
		$tmpl = array ( 'table_open'  => '<table   class="table table-bordered">' );
		$this->table->set_template($tmpl);
		echo form_open($this->uri->uri_string());
		echo "<div class='page-header'><h3>用户管理</h3></div>";
		
		echo $this->table->generate(); 
		
		
		echo form_submit('ban', '禁用',"class='btn btn-danger'");
		echo "&nbsp;&nbsp;";
		echo form_submit('unban', '解禁',"class='btn btn-inverse '");
		echo "&nbsp;&nbsp;";
		echo form_submit('reset_pass', '重置密码',"class='btn btn-info'");
		echo "&nbsp;&nbsp;";
		echo anchor("auth/add_user",'用户添加',"class='btn btn-success'");
		echo form_close();
		
		echo $pagination;
			
	?>
	</div>
	<script type="text/javascript">
	$(function(){
		$(".showinfo").click(function(){
			var href=$(this).attr("href");
			var time=new Date().getTime();
			$("#myModal .modal-body").empty().load(href,{time:time});
			$("#myModal").modal();
			return false;
			});
		});
	</script>
	</body>
</html>