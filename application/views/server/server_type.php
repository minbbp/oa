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
<table class="table table-hover">
              <thead>
                <tr>
                  <th>服务名称</th>
                  <th>编辑操作</th>
                  <th>删除操作</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach($list as $value){ ?>
                <tr>
                  <td><?php echo $value['st_name'] ?></td>
                  <td><?php echo anchor('server_type/server_m/'.$value['st_id'],'编辑',"class='selecttype' " ); ?></td>
                  <td><?php echo anchor('server_type/server_del/'.$value['st_id'],'删除',"class='deltype' " ); ?></td>
                </tr>
                  <?php } ?>
              </tbody>
</table>
 <div>
 <button address="<?php echo site_url('server_type/server_m_add/') ?>" class="btn" id="add_type" type="button">添加服务</button>
 </div>


<!-- Modal -->
<div class="showmsg">
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
  </div>
  <div class="modal-body">
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    <button class="btn btn-primary" id="put" data-dismiss="modal" aria-hidden="true">确认</button>
  </div>
</div>
</div>
<!-- Modal -->
</div>
<script type="text/javascript">
	$(function(){
		$(".selecttype").click(function(){
			var href=$(this).attr("href");
			var time=new Date().getTime();
			$("#myModal .modal-body").empty().load(href,{time:time});
			$("#myModal").modal();
			return false;
		});   
                $("#add_type").click(function(){
			var href=$(this).attr("address");
			var time=new Date().getTime();
			$("#myModal .modal-body").empty().load(href,{time:time});
			$("#myModal").modal();
			return false;
		});   
                $("#put").bind("click",function(){
                var href = $("#table_m").attr('action');
                var data = $("#table_m").serialize();
                 $.post(href,data,function(json_data){
                if(json_data.status==1)
                 {
                     layer.alert(json_data.msg,9,'成功提示！',function(){
                         location.reload();
                     });
                 }else{
                     layer.alert(json_data.msg,8,'错误提示！');
                 }
                 },'json');
                  });
            $(".deltype").click(function(){
            var href=$(this).attr("href");
            layer.confirm('确定删除?', function(){ 
                $.post(href,function(json_data){
                if(json_data.status==1)
                 {
                     layer.alert(json_data.msg,9,'成功提示！',function(){
                         location.reload();
                     });
                 }else{
                     layer.alert(json_data.msg,8,'错误提示！');
                 }
                 },'json');
                });
            return false;
            });  
                  
                  
		});
</script>
</body>
</html>
