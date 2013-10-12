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
    <!-- bootstrap end -->
  </head>
  <body>
  <div class="span5">
  <div class="page_header">
  <h4> 机器列表</h4>
  </div>
  <?php if(!empty($allkeys)):?>
  <table class="table table-bordered">
  <thead><tr><th>机器标识</th><th>状态</th> <th>添加时间</th></tr></thead>
   <tbody>
   <?php foreach ($allkeys as $key):?>
   <tr> 
   <td><?php echo $key['git_auth']==''?'<small class="muted">未分配</small>':$key['git_auth'];?></td>
   <td><?php if($key['key_state']==0)
   			 	{
   			 		echo '<small class="muted">未分配</small>';
   			 	}
   			 	else if($key['key_state']==1)
				{
					echo "<small class='text-success'>可用<small>";
				}
   			 	else
				{
					echo "<small class='text-error'>ssh-key错误</small>";
				}
   		?>
   </td>
   <td><?php echo date('Y-m-d H:i:s',$key['addtime']);?></td>
   </tr>
   <?php endforeach;?>
   </tbody>
  </table>
  <?php else:?>
   <p class="text-error">机器列表为空！</p>
  <?php endif;?>
  </div>
 </body>
 </html>