<dl class="dl-horizontal">
<dt>申请者:</dt>
<dd><?php echo $change['realname']?></dd>
<dt>git组</dt>
<dd> <?php echo $info['group_name'];?></dd>
<dt>git组人员</dt>
 <dd>
 <?php
foreach($info['all_users'] as $user)
{
	echo "<span>&nbsp;{$user['realname']}&nbsp;</span>";
}
 ?>
 </dd>
 <dt>用户组描述</dt>
   <dd><?php echo $info['group_description'];?></dd>
</dl>
