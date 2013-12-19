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
						<?php echo $title; ?>
					</h3>
				</div>
				<table class="table table-bordered">
			 <caption><h4><?php if($apply_row['is_ungent']==1){echo "<label class='label label-important'>紧急</label>";}?>上线申请信息</h4></caption>
			 <tr class="success"><td>申请工单号：</td><td><?php echo $apply_row['apply_no'];?></td><td>升级模块:</td><td><?php echo $model_row['m_name']; ?></td></tr>
			  <tr><td>申请人:</td><td><?php echo $apply_user['realname']?></td><td>申请人邮件：</td><td> <?php echo $apply_user['email']?></td></tr>
			  <tr><td>模块负责人:</td><td><?php echo $head_row['realname'];?></td><td>负责人邮件:</td><td><?php echo $head_row['email'];?></td></tr>
			  <tr><td>测试：</td><td><?php echo $tester_row['realname']?></td><td>测试者邮件:</td><td><?php echo $tester_row['email']; ?></td></tr>
			  <tr><td>运维人员：</td><td><?php echo $op_row['realname']?></td><td>运维邮件:</td><td><?php echo $op_row['email']; ?></td></tr>
			 <tr class="warning"><td>需求编号：</td><td><?php echo $require_row['required_no'];?></td><td>需求标题：</td><td><small><?php echo $require_row['required_title'];?></small></td></tr>
			 <tr class="error"><td>git标签</td><td><?php echo $apply_row['git_tag'];?></td><td>git地址：</td><td><small style=" font-size:8;"><?php echo $apply_row['git_url'];?></small></td></tr>
			 <tr class="info"><td>涉及更新配置文件</td>
			 <td colspan="3">
			 <?php if(empty($config_rs)){echo "无更新配置文件!";}else{?>
			<table class="table table-bordered">
			<caption>修改的配置文件</caption>
			<tr><th>文件名</th><th>修改项</th><th>旧值</th><th>新值</th></tr>
			 <?php foreach($config_rs as $cf):?>
			 <tr><td><?php echo $cf['file_name']?></td><td><?php echo $cf['file_item']?></td><td><?php echo $cf['file_item_old_value']?></td><td> <?php echo $cf['file_item_new_value'];?></td></tr>
			 <?php endforeach;?>
			 </table>
			 <?php }?>
			 </td></tr>
			 <tr class="info"><td>涉及更新服务器</td>
			 <td colspan="3">
			 <?php if(empty($server_rs)){echo "无需更新任何服务器";}else{?>
			<table class="table table-bordered">
			<caption>更新服务器列表</caption>
			<tr><th>编号</th><th>IP</th></tr>
			 <?php foreach($server_rs as $sr):?>
			 <tr><td><?php echo $sr['s_id'];?></td><td><?php echo ($sr['s_internet']);?></td></tr>
			 <?php endforeach;?>
			 </table>
			 <?php }?>
			 </td></tr>
			 <tr><td>备注：</td><td colspan="3"><?php echo $apply_row['online_description'];?></td></tr>
			 <tr class="error"><td>上线时间：</td><td><?php echo $apply_row['online_time'];?></td><td>申请时间：</td><td><?php echo date('Y-m-d H:i:s',$apply_row['apply_addtime']);?></td></tr>
			 <!-- <tr><td></td><td></td><td></td><td></td></tr>
			 <tr><td></td><td></td><td></td><td></td></tr> -->
			 </table>
			 <a href="javascript:history.back()" class="btn btn-danger span2 ">&lt;&lt;返回</a>
			</div>
			 
 </body>
</html>