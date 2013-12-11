 <style type="text/css">
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
 <caption>上线涉及信息</caption>
 <tr><td class="ttd">上线申请人：</td><td class="ttdr"><?php echo $to_adduser['realname'];?></td></tr>
  <tr><td class="ttd">针对需求：</td><td class="ttdr"><?php echo $require_row->required_title;?></td></tr>
 <tr><td class="ttd">git地址：</td><td class="ttdr"><?php echo $apply_rs['git_url']?></td></tr>
 <tr><td class="ttd">git标签：</td><td class="ttdr"><?php echo $apply_rs['git_tag']?></td></tr>
 <tr><td class="ttd">涉及更新内容：</td><td class="ttdr"><?php echo $apply_rs['online_description']?></td></tr>
 <tr><td class="ttd">设计更新配置文件：</td>
 <td class="ttdr">
 <?php 
 echo "<h4>共".count($change_file)."项,如下所示</h4><ul style='list-style-type: none;'>"; 
 foreach ($change_file as $file)
{
 echo "<li>{$file['file_name']} 文件的 {$file['file_item']}项的值由  {$file['file_item_old_value']} &nbsp;修改为 {$file['file_item_new_value']}</li>";
}
echo "</ul>";
 ?>
 </td></tr>
 <tr><td class="ttd">涉及更新服务器:</td>
 <td class="ttdr"> 
 <h4>共涉及<?php echo count($server_rs);?>台服务器</h4>
 <ul style="list-style-type: none;text-align:left;">
 <?php foreach($server_rs as $server):?>
 <li><?php echo $server['s_internet'];?></li>
 <?php endforeach;?>
 </ul>
 </td></tr>
 <tr><td class="ttdb"> 上线时间：</td><td class="ttdbr"><?php echo $apply_rs['online_time'];?></td></tr>
 </table>