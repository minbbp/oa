			<table>
			 <caption><h4>已上线模块通知</h4></caption>
			 <tr><td>申请工单号：</td><td><?php echo $apply_row['apply_no'];?></td><td>升级模块:</td><td><?php echo $model_row['m_name']; ?></td></tr>
			  <tr><td>申请人:</td><td><?php echo $apply_user['realname']?></td><td>申请人邮件：</td><td> <?php echo $apply_user['email']?></td></tr>
			  <tr><td>模块负责人:</td><td><?php echo $head_row['realname'];?></td><td>负责人邮件:</td><td><?php echo $head_row['email'];?></td></tr>
			  <tr><td>测试：</td><td><?php echo $tester_row['realname']?></td><td>测试者邮件:</td><td><?php echo $tester_row['email']; ?></td></tr>
			  <tr><td>运维人员：</td><td><?php echo $op_row['realname']?></td><td>运维邮件:</td><td><?php echo $op_row['email']; ?></td></tr>
			 <tr><td>需求编号：</td><td><?php echo $require_row['required_no'];?></td><td>需求标题：</td><td><small><?php echo $require_row['required_title'];?></small></td></tr>
			 <tr><td>git标签</td><td><?php echo $apply_row['git_tag'];?></td><td>git地址：</td><td><small style=" font-size:8;"><?php echo $apply_row['git_url'];?></small></td></tr>
			 <tr><td>涉及更新配置文件</td>
			 <td colspan="3">
			<table>
			<caption>修改的配置文件</caption>
			<tr><th>文件名</th><th>修改项</th><th>旧值</th><th>新值</th></tr>
			 <?php foreach($config_rs as $cf):?>
			 <tr><td><?php echo $cf['file_name']?></td><td><?php echo $cf['file_item']?></td><td><?php echo $cf['file_item_old_value']?></td><td> <?php echo $cf['file_item_new_value'];?></td></tr>
			 <?php endforeach;?>
			 </table>
			 </td></tr>
			 <tr><td>涉及更新服务器</td>
			 <td colspan="3">
			<table>
			<caption>更新服务器列表</caption>
			<tr><th>编号</th><th>IP</th></tr>
			 <?php foreach($server_rs as $sr):?>
			 <tr><td><?php echo $sr['server_id'];?></td><td><?php echo long2ip($sr['server_ip']);?></td></tr>
			 <?php endforeach;?>
			 </table>
			 </td></tr>
			 <tr><td>备注：</td><td colspan="3"><?php echo $apply_row['online_description'];?></td></tr>
			 <tr class="error"><td>上线时间：</td><td><?php echo $apply_row['online_time'];?></td><td>申 请时间：</td><td><?php echo date('Y-m-d H:i:s',$apply_row['apply_addtime']);?></td></tr>
			 <tr><td>运维上线时间:</td><td><?php echo date('Y-m-d H:i',$apply_row['end_time']);?></td><td></td><td></td></tr>
			 <tr><td></td><td></td><td></td><td></td></tr>
			 </table>	