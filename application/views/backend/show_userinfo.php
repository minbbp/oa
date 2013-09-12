<h4>用户个人信息</h4>
<table class="table table-bordered">
<tr><td>用户名</td><td><?php echo $userinfo['username']?></td><td>真实姓名</td><td><?php echo $userinfo['realname'];?></td></tr>
<tr><td> 角色</td><td><?php echo $userinfo['rolename']?></td><td>邮件</td><td><?php echo $userinfo['email']?></td></tr>
<tr><td> 职务</td><td><?php if($userinfo['level']==0){echo "员工";}else{echo '主管';}?></td><td>创建时间</td><td><?php echo $userinfo['created']?></td></tr>
<tr><td>更新时间</td><td><?php echo $userinfo['modified']?></td><td>禁用</td><td><?php if($userinfo['banned']==0){echo 'NO';}else{echo 'YES';}?></td></tr>
<tr><td>最后登陆ip</td><td><?php echo $userinfo['last_ip']?></td><td>最后登陆时间</td><td> <?php echo $userinfo['last_login']?></td></tr>
</table>
<?php if(!empty($levelinfo)):?>
<h4> 用户主管信息</h4>
<table class="table table-bordered">
<tr><td>用户名</td><td><?php echo $levelinfo['username']?></td><td>真实姓名</td><td><?php echo $levelinfo['realname'];?></td></tr>
<tr><td> 角色</td><td><?php echo $levelinfo['rolename']?></td><td>邮件</td><td><?php echo $levelinfo['email']?></td></tr>
<tr><td> 职务</td><td><?php echo "主管";?></td><td>创建时间</td><td><?php echo $levelinfo['created']?></td></tr>
<tr><td>更新时间</td><td><?php echo $levelinfo['modified']?></td><td>禁用</td><td><?php if($levelinfo['banned']==0){echo 'NO';}else{echo 'YES';}?></td></tr>
<tr><td>最后登陆ip</td><td><?php echo $levelinfo['last_ip']?></td><td>最后登陆时间</td><td> <?php echo $levelinfo['last_login']?></td></tr>
</table>
<?php endif;?>