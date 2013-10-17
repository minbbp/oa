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
 <div class="span8 offset1">
			<div class="page-header">
				<h3>
					git   认证运维处理操作统计
				</h3>
					
			</div>
			<table class="table table-bordered table-hover">
			<thead><tr><th>操作员</th><th>操作数</th></tr></thead>
			<tbody>
			<?php foreach($totals as $t):?>
			<tr>
			<td><?=$t['realname']?></td> 
			
			<td>
			<?=$t['total']?>
			</td>
			
			
			
			</tr>
			<?php endforeach;?>
			</tbody>
			</table>
			
</div>

</body>
</html>
