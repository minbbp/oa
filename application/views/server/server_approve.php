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
                        <?php if(empty($list)){ ?>
                        <h4>当前没有您需要做的审批</h4>
                        <?php }else{ ?>
			<table class="table table-bordered table-hover">
			<thead><tr>
                                <th>#</th><th>申请人</th><th>服务器用途</th><th>申请时间</th><th>当前状态</th><th>选择操作</th>
                               </tr>
                        </thead>
			<tbody>
                         <?php foreach($list as $val){ ?>
			<tr>
                        <td></td> 
			<td><?php echo $val['sn_name']; ?></td>
			<td><?php echo $val['sn_use']; ?></td>
			<td><?php echo date('Y-m-d H:i:s',$val['sn_time']); ?></td>
                        <td><?php if($val['sa_status'] == 0){ echo "未审核";}else if($val['sa_status'] == 1){ echo "已通过"; }else{ echo "已退回";} ; ?></td>
                        <td><?php echo anchor('server_approve/server_agree/'.$val['sa_id'], '同意', "class='agree'") ?> | 
                            <?php echo anchor('server_approve/server_disagree/'.$val['sa_id'], '退回', "class='disagree'") ?> | 
                            <?php echo anchor('server_approve/server_see/'.$val['sn_id'], '查看', "class='see'") ?>| 
                            <?php if($role_id == 5){echo anchor('server_approve/server_agree_op/'.$val['sa_id'], '分配完成', "class='change'");} ?>
                        </td>
			</tr>
                         <?php } ?>
			</tbody>
			</table>
                        <?php echo $link ?>
                        <?php } ?>      
<!-- Modal -->
<div class="showmsg">
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>查看申请信息</h3>
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
    
          $(".change").click(function(){
         var href=$(this).attr("href");
         layer.confirm('确定分配完成？', function(){ 
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
	//处理点击查看按钮显示
	$(".see").click(function(){
            var href=$(this).attr("href");
            var time=new Date().getTime();
            $("#myModal .modal-body").empty().load(href,{time:time});
            $("#myModal").modal();
            return false;
	});   
        $(".agree").click(function(){
            var href =$(this).attr('href');
            $.post(href,function(json_data){
            if(json_data.status==2){
                location.href = json_data.msg;
            }else if(json_data.status==1)
            {
                layer.alert(json_data.msg,9,'成功提示！',function(){
                location.reload();
                });
            }else{
                layer.alert(json_data.msg,8,'错误提示！');
            }
            
            },'json');
            return false;
        })
	$(".disagree").click(function(){
		var href=$(this).attr('href');
		var time=new Date().getTime();
		  $.layer({
				type:2,
				title:false,
				area:['540px','300px'],
				border:[0],
				bgcolor:'#fff',
				shadeClose: true,
				offset:['20px',''],
				iframe:{src:href+'/'+time},
				close:function(index)
				{
					var sendmsg=layer.getChildFrame('#sendmsg',index).val();
					if(sendmsg==1)
					{
                                            $("a[href='"+href+"']").parents('tr').remove();
					}
					layer.close(index);
				}
			});
                        
		return false;
		});
                function re(){
                location.reload();
                }
});
</script>
</body>
</html>
