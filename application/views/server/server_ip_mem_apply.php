<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title><?php echo $title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=base_url()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <script src="<?=base_url()?>/bootstrap/js/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/layer/layer.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
 
 <div class="span8 offset1">
			<div class="page-header">
                            <h3><?php echo $title?></h3>
			</div>
<table class="table table-hover table-bordered">
    <h4><?php echo $smalltitle?></h4>
    <br />
              <thead>
                <tr>
                  <th>ip地址</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach ($list as $v) {  ?>
                <tr>
                  <td><?php echo $v['s_internet']; ?></td>
                  <td>
                  <?php echo anchor('server_ip_mem/server_see/'.$v['s_id'],'查看',"class='see'"  ); ?>
                  <?php echo anchor('server_ip_mem/apply_ip/'.$v['s_id'],'申请外网' ); ?>
                  <?php echo anchor('server_ip_mem/apply_e/'.$v['s_id'],'申请扩容',"class='deltype'" ); ?>
                  </td>
                </tr>
                 <?php  } ?>
              </tbody>
            </table>
     <?php echo $link ?>
<!-- Modal -->
<div class="showmsg">
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3>查看服务器信息</h3>
</div>
<div class="modal-body">
</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
</div>
</div>
</div>
<!-- Modal -->
 </div>
<script type="text/javascript">
$(function(){
      $(".see").click(function(){
  var href=$(this).attr("href");
  var time=new Date().getTime();
  $("#myModal .modal-body").empty().load(href,{time:time});
  $("#myModal").modal();
  return false;
      }); 



})
</script>

  </body>
  </html>