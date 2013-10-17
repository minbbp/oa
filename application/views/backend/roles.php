<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title>角色管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=base_url()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="<?=base_url()?>/bootstrap/js/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
 <div class="span8 offset1">
  <div class="page-header">
				<h3>
					 用户角色管理
				</h3>
</div>
	<?php  				
		// Show error
		echo validation_errors();
		
		// Build drop down menu
		$options[0] = 'None';
		foreach ($roles as $role)
		{
			$options[$role->id] = $role->name;
		}
	
		// Build table
		$this->table->set_heading('#', 'ID', 'Name', 'Parent ID');
		
		foreach ($roles as $role)
		{			
			$this->table->add_row(form_checkbox('checkbox_'.$role->id, $role->id), $role->id, $role->name, $role->parent_id);
		}
		
		// Build form
		echo form_open($this->uri->uri_string());
		
		echo form_label('父角色', 'role_parent_label');
		echo form_dropdown('role_parent', $options); 
				
		echo form_label('角色名', 'role_name_label');
		echo form_input('role_name', ''); 
		echo "<p>";
		echo form_submit('add', '添加角色',"class='btn btn-primary'"); 
		echo "&nbsp;&nbsp;";
		
		echo "</p>";		
		echo '<hr/>';
		$tmpl = array ( 'table_open'  => '<table   class="table table-bordered">' );
		$this->table->set_template($tmpl);
		// Show table
		echo $this->table->generate(); 
		echo form_submit('delete', '删除选中',"class='btn btn-danger'");
		echo form_close();
			
	?>
	</div>
	</body>
</html>