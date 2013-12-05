<div class="bs-docs-example"> 
        <dl class="dl-horizontal" style="font-size: 16px">
          <dt>cpu：</dt>
          <dd><?php echo $info['s_cpu']?>核</dd><br />
          <dt>内存：</dt>
          <dd><?php echo $info['s_mem']?>G</dd><br />
          <dt>硬盘：</dt>
          <dd><?php echo $info['s_disk']?>G</dd><br />
          <dt>内网ip：</dt>
          <dd><?php echo $info['s_internet']; ?></dd><br />
          <?php if( $info['s_winternet']){ ?>
          <dt>外网ip：</dt>
          <dd><?php echo $info['s_winternet']; ?></dd><br />
          <?php } ?>
          <?php if($info['s_winternet']){ ?>
          <dt>Isp：</dt>
          <dd><?php echo $info['s_isp']?></dd><br />
          <?php  } ?>     
          <br />
          </dl>
</div>

