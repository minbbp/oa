<p> 申请人为：<?php echo $realname;?></p>
<p>请以下人员对git申请进行审批：</p>
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
</style>
        <table class="t">
            <tr>
                <td class="ttd">审批主管</td><td class="ttdr"> <?php echo $levelinfo['realname'];?></td>
             </tr>
             <?php foreach ($gitgroups as $g):?>
             <tr>
                <td class="ttd">git组负责人</td><td class="ttdr"> <?php echo $g['realname']?></td>
             </tr>
             <?php endforeach;?>
            
		  <tr>
		  <td class="ttdb">备注</td><td   class="ttdbr"> 登陆运维平台，审批即可！</td>
		  </tr>
         </table>