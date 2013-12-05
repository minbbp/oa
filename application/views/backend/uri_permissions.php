<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title>用户角色权限</title>
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
					用户角色权限
				</h3>
</div>	
	<?php  				
		// Build drop down menu
		foreach ($roles as $role)
		{
			$options[$role->id] = $role->name;
		}

		// Change allowed uri to string to be inserted in text area
		if ( ! empty($allowed_uris))
		{
			$allowed_uris = implode("\n", $allowed_uris);
		}
		
		// Build form
		echo form_open($this->uri->uri_string());
		
		echo form_label('角色', 'role_name_label');
		echo form_dropdown('role', $options); 
		echo "&nbsp;&nbsp;";
		echo form_submit('show', '查看权限',"class='btn'"); 
		
		echo form_label('', 'uri_label');
				
		echo '<hr/>';
				
		echo 'Allowed URI (One URI per line) :<br/><br/>';
		
		echo "Input '/' to allow role access all URI.<br/>";
		echo "Input '/controller/' to allow role access controller and it's function.<br/>";
		echo "Input '/controller/function/' to allow role access controller/function only.<br/><br/>";
		echo 'These rules only have effect if you use check_uri_permissions() in your controller<br/><br/>.';
		
		echo form_textarea('allowed_uris', $allowed_uris); 
				
		echo '<br/>';
		echo '<br/>';
		echo form_submit('save', '保存权限',"class='btn btn-success'");
		
		echo form_close();
	?>
	</body>
</html>