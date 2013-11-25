<h4>申请人为：<?php echo $info['sn_realname']?></h4>
<br />
<h4>申请人帐号为：<?php echo $info['sn_name']?></h4>
<br />
<div class="bs-docs-example"> 
        <dl class="dl-horizontal" style="font-size: 18px">
          <dt>cpu：</dt>
          <dd><?php echo $info['sn_cpu']?>核</dd><br />
          <dt>内存：</dt>
          <dd><?php echo $info['sn_mem']?>G</dd><br />
          <dt>硬盘：</dt>
          <dd><?php echo $info['sn_disk']?>G</dd><br />
          <dt>Internet：</dt>
          <dd><?php if($info['sn_internet']==1){echo '需要';}else{echo '不需要';} ?></dd><br />
          <?php if($info['sn_internet']==1){ ?>
          <dt>Isp：</dt>
          <dd><?php echo $info['sn_isp']?></dd><br />
          <?php  } ?>
          <dt>服务器用途：</dt>
          <dd><?php echo $info['sn_use']?></dd><br />
          <?php if($info['m_name']){ ?>
          <dt>申请的服务：</dt>
          <dd>   <?php if($info['m_name']){ 
                            foreach($info['m_name'] as $v){
                                $arr[] =  $v['m_name'];
                             }
                    $str = implode(',', $arr); 
                        echo $str;
                            }else{
                        echo "没申请";
                    }
                    echo "</dd><br />";
           } ?>
              
          <dt>服务器描述：</dt>
          <dd><?php echo $info['sn_desc']?></dd><br />       
          <dt>申请时间：</dt>
          <dd><?php echo date('Y年m月d日 H时i分s秒',$info['sn_time'])?></dd> <br />
          <dt>申请几台：</dt>
          <dd><?php echo  $info['sn_num']?></dd> 
          </dl>
</div>

