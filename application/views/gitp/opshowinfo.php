
<div class="span5 offset1">

<h5>git组新建说明</h5>
<p>用户<?php echo $userinfo['username']?> 
申请的git用户组已经通过了审批，请新增git组：<?php echo "<span class='text-error'>".$group_rs['group_name']."</span>";?>
该git用户组人员为：<?php foreach ($alluser as $user){echo "<span class='text-error'>".$user['username']."&nbsp;&nbsp;</span>";}?>
</p>
<p>创建好git组，以及加入完git组用户之后请点击“通过”，系统会发送邮件通知相关人员</p>
</div>