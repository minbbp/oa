<p> 申请人为：<?php echo $info['sn_realname']?></p>
<p> 申请人帐号为：<?php echo $info['sn_name']?></p>
        <table>
            <tr>
                <td>cpu:</td><td> <?php echo $info['sn_cpu']?>核</td>
                <td>内存:</td><td> <?php echo $info['sn_mem']?>G</td>
             </tr>
             <br />
             <tr>
                <td>硬盘:</td><td> <?php echo $info['sn_disk']?>G</td>
                <td>Internet:</td><td> <?php if($info['sn_internet']==1){echo '需要';}else{echo '不需要';} ?></td>
             </tr>
             <br />
             <tr>
                <td>Isp:</td><td> <?php if($info['sn_internet']==1){ echo $info['sn_isp'];}else{ echo '不需要';}  ?></td>
                <td>服务器用途:</td><td> <?php echo $info['sn_use']?></td>
             </tr>
             <br />
             <tr>                
                <td>申请的服务:</td>                <td  colspan="3">  <?php if($info['m_name']){foreach($info['m_name'] as $v){
                                $arr[] =  $v['m_name'];
                             }
                    $str = implode(',', $arr); 
                        echo $str;
                            }else{
                        echo "没申请";
                    } ?></td>
                
             <br />
             <tr>                  
                <td>申请时间:</td> <td> <?php echo date('Y年m月d日',$info['sn_time'])?></td>
                <td>申请几台:</td><td> <?php echo  $info['sn_num']?></td>
          </tr>
		  <tr>
		  <td>服务器描述:</td><td  colspan="3"> <?php echo $info['sn_desc']?></td>
		  </tr>
         </table>
