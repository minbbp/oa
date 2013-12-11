<style>
.ttd {
border-top: 1px solid #DDDDDD;
line-height: 20px;
padding: 8px;
text-align: left;
vertical-align: top;
border-left: 1px solid #DDDDDD;
color: #5F5F5F;
font-size: 14px;
}
.t{
border-collapse: separate;border-image: none;
border-radius: 4px;
border-width: 1px 1px 1px 0;
margin-bottom: 20px;
width: 570px;
font-family: "微软雅黑",Helvetica,Arial,sans-serif;
}
.ttdb {
border-top: 1px solid #DDDDDD;
border-bottom: 1px solid #DDDDDD;
line-height: 20px;
padding: 8px;
text-align: left;
vertical-align: top;
border-left: 1px solid #DDDDDD;
color: #5F5F5F;
font-size: 14px;
}
.ttdr {
border-top: 1px solid #DDDDDD;
border-right: 1px solid #DDDDDD;
line-height: 20px;
padding: 8px;
text-align: left;
vertical-align: top;
border-left: 1px solid #DDDDDD;
color: #5F5F5F;
font-size: 14px;
}
.ttdbr {
border-top: 1px solid #DDDDDD;
border-right: 1px solid #DDDDDD;
border-bottom: 1px solid #DDDDDD;
line-height: 20px;
padding: 8px;
text-align: left;
vertical-align: top;
border-left: 1px solid #DDDDDD;
color: #5F5F5F;
font-size: 14px;
}
.th{
border-top: 1px solid #DDDDDD;
background-color: #F8F8F8;
color: #5F5F5F;
font-size: 14px;
line-height: 20px;
padding: 8px;
text-align: left;
border-left: 1px solid #DDDDDD;
}
.thr{
border-top: 1px solid #DDDDDD;
border-right: 1px solid #DDDDDD;
background-color: #F8F8F8;
color: #5F5F5F;
font-size: 14px;
line-height: 20px;
padding: 8px;
text-align: left;
border-left: 1px solid #DDDDDD;
}
</style>
<p>您的申请已被运维人员批复,具体信息如下:</p>
        <table class="t">
		<thead>
                        <tr>
			<td class="th">内网服务器地址</td><td class="th">帐号</td><td class="thr">密码</td>
			</tr>
		</thead>
		<tbody>
			<?php 
			$num = count($arr);
			foreach($arr as $v){
			if($num != 1){ ?>
			<tr>
			<td class = "ttd"><?php echo $v['ip'];?></td><td class = "ttd"><?php echo $v['account'];?></td><td class="ttdr"><?php echo $v['pwd'];?></td>
			</tr>
			<?php }else{ ?>
			<tr>
			<td class = "ttdb"><?php echo $v['ip'];?></td><td class = "ttdb"><?php echo $v['account'];?></td><td class="ttdbr"><?php echo $v['pwd'];?></td>
			</tr>
			<?php } 
                        $num--;
                        } ?>
		</tbody>
         </table>
