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
  <div class="span7">
  <p>运维中心欢迎您<b><?php echo $username['username'];?></b>。目前项目的开发进度为10%</p>
  <div class="progress progress-striped active">
  <div class="bar" style="width: 12%;"></div>
	</div>
  </div>
  </body>

  </html>
