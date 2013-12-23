<div class="bs-docs-example"> 
        <dl class="dl-horizontal" style="font-size: 16px">
          <dt>cpu：</dt>
          <dd><?php echo $info['s_cpu']?>核</dd><br />
          <dt>内存：</dt>
          <dd><?php echo $info['s_mem']?>G</dd><br />
          <dt>硬盘：</dt>
          <dd><?php echo $info['s_disk']?>G</dd><br />
          <dt>内网ip：</dt>
          <dd><?php echo $info['s_internet']; if($ipnei_list){ foreach ($ipnei_list as $v){ echo "&nbsp&nbsp ".$v['si_ip']; }} ?></dd><br />
          <?php if( $info['s_winternet']){ ?>
          <dt>外网ip：</dt>
          <dd><?php echo $info['s_winternet'];  if($ipwai_list){ foreach ($ipwai_list as $v){ echo "&nbsp&nbsp ".$v['si_ip']; }}?></dd><br />
          <?php } ?>
          <?php if($info['s_winternet']){ ?>
          <dt>Isp：</dt>
          <dd><?php echo $info['s_isp']?></dd><br />
          <?php  } ?>
          <dt>服务器用途：</dt>
          <dd><?php echo $use_list[$info['s_use']]; ?></dd><br />
          <?php if($info['s_use']> 2 && $info['s_type']!=''){ ?>
          <dt>已有服务：</dt>
          <dd><?php $arr = explode(',', $info['s_type']);
                    $array = array();
                    $new = array();
                    foreach ($list as $val) {
                           $array[$val['m_id']] = $val['m_name'];
                    }
                    foreach($arr as $v){
                         $new[] =$array[$v];
                    }
                    echo implode(',', $new);
          ?></dd><br />
          <?php } ?>
          <?php if($list_owner){?>
          <dt>使用人：</dt>
          <dd><?php foreach($list_owner as $value){ 
              echo "<p>".$value['name_type']."<p>";
       }?>
          </dd>
          <br />
          <?php } ?>
          <dt>服务器描述：</dt>
          <dd><?php echo $info['s_desc']?></dd><br />       
          <br />
          </dl>
    
    
</div>

