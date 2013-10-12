<?php if(empty($users)):?>
<p class="text-error">对不起，没有该用户信息！</p>
<?php else:?>
<table class="table table-bordered table-hover">
 <thead>
  <tr>
    <th>用户名</th>
    <th>真实姓名</th>
    <th>邮件</th>
  </tr>
  </thead>
  <?php foreach ($users as $user): ?>
  <tr>
    <td><?php echo $user['username']?></td>
    <td><?php echo $user['realname']?></td>
    <td><?php echo $user['email']?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif;?>
