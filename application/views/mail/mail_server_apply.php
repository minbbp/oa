<p> 申请人为：<?php echo $info['sn_realname']?></p>
<p> 申请人帐号为：<?php echo $info['sn_name']?></p>
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
                <td class="ttd">cpu:</td><td class="ttdr"> <?php echo $info['sn_cpu'];?>核</td>
                <td class="ttd">内存:</td><td class="ttdr"> <?php echo $info['sn_mem'];?>G</td>
             </tr>
             <tr>
                <td class="ttd">硬盘:</td><td class="ttdr"> <?php echo $info['sn_disk']?>G</td>
                <td class="ttd" >Internet:</td><td class="ttdr"> <?php if($info['sn_internet']==1){echo '需要';}else{echo '不需要';} ?></td>
             </tr>
             <tr>
                <td class="ttd">Isp:</td><td class="ttdr"> <?php if($info['sn_internet']==1){ echo $info['sn_isp'];}else{ echo '不需要';}  ?></td>
                <td class="ttd">服务器用途:</td><td class="ttdr"> <?php echo $info['sn_use']?></td>
             </tr>
             <tr>                
                <td class="ttd">申请的服务:</td>                <td  colspan="3" class="ttdr">  <?php if($info['m_name']){foreach($info['m_name'] as $v){
                                $arr[] =  $v['m_name'];
                             }
                    $str = implode(',', $arr); 
                        echo $str;
                            }else{
                        echo "没申请";
                    } ?></td>
             <tr>                  
                <td class="ttd">申请时间:</td><td class="ttdr"> <?php echo date('Y年m月d日',$info['sn_time'])?></td>
                <td class="ttd">申请几台:</td><td class="ttdr"> <?php echo  $info['sn_num']?></td>
          </tr>
		  <tr>
		  <td class="ttdb">服务器描述:</td><td  colspan="3" class="ttdbr"> <?php echo $info['sn_desc']?></td>
		  </tr>
         </table>
