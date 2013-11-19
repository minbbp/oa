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
                            <h4><?php echo $title?></h4>
			</div>
     <div style="font-size ">
     申请人：<?php echo $info['sn_name']?>&nbsp&nbsp&nbsp
     <hr />
     cpu:<?php echo $info['sn_cpu']?>核&nbsp&nbsp&nbsp
     内存:<?php echo $info['sn_mem']?>G&nbsp&nbsp&nbsp
     硬盘:<?php echo $info['sn_disk']?>G&nbsp&nbsp&nbsp
     <?php if($info['sn_internet']==1){echo '需要'."运营商为".$info['sn_isp'];}else{echo '不需要外网';} ?>&nbsp&nbsp&nbsp
     用途:<?php echo $info['sn_use']?>&nbsp&nbsp&nbsp
     需要<?php echo $info['sn_num']?>台
     </div>
     <hr /> 
                    <form action="<?php echo site_url('server_approve/op_approve/'.$sa_id)?>" method="get" accept-charset="utf-8" class="form-horizontal" id="server_form">   
                        <select name="type[]" id="select_all" class="span2">
                        <option value="s_internet">ip</option>
                        <option value="s_use">用途</option>
                        <option value="s_type">服务</option>
                        <option value="owner">使用人</option>
                        <option value="s_cpu">cpu</option>
                        <option value="s_mem">mem</option>
                        <option value="s_disk">disk</option>
                        </select>
                        <div class="input-append">
                          <input class="span2" id="keyword" name="keyword" placeholder="请输入关键字" type="text">
                          <button class="btn" id="yes" type="submit">Go!</button>
                        </div>
                        </form>
     <hr />
     <table class="table table-hover table-bordered">
              <thead>
                <tr>
                    <th>#</th>
                  <th>ip地址</th>
                  <th>空闲</th>
                  <th>cpu</th>
                  <th>mem</th>
                  <th>disk</th>
                  <th>用途</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach ($list as $v) {  ?>
                <tr>
                    <td><input type="checkbox" name ="select_allocate" value=<?php echo $v['s_id']; ?> ></td>
                  <td><?php echo $v['s_internet']; ?></td>
                  <td><?php if($v['owner_status'] ==1){echo "已使用";}else{echo "空闲";} ?></td>
                  <td><?php echo $v['s_cpu']; ?></td>
                  <td><?php echo $v['s_mem']; ?></td>
                  <td><?php echo $v['s_disk']; ?></td>
                  <td><?php echo $v['sn_use']; ?></td>
                  <td>
                  <?php echo anchor('server_manage/server_see/'.$v['s_id'],'查看',"class='see'" ); ?>
                  </td>
                </tr>
                 <?php  } ?>
              </tbody>
            </table>
     <?php echo $link ?>
<div>
 <?php echo anchor('server_manage/server_allocate/'.$info['sn_id']."/".$sa_id."/",'分配',"class ='btn allocate' id='allocate'" ); ?>   
 <a href="<?php echo site_url('server_approve/index') ?>" class="btn"  >返回</a>
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
      
<script>
    $(function(){
        
         $("#yes").click(function(){
            var type = $('#select_all option:selected').val();
            var kw = $('#keyword').val();
            var action = $('#server_form').attr('action');
            var href = action+'/'+type+'/'+kw;
            location.href=href;
            return false;
        })
        $(".allocate").click(function(){
                 var href=$(this).attr("href");
                 var time=new Date().getTime();
               var str='';
                 $('input[name="select_allocate"]:checked').each(function(i){ 
                   str+=$(this).val()+'-'; 
                }); 
               if (str.length > 0) { 
                    str = str.substring(0,str.length - 1); 
                }
                href= href+"/"+str;
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
                   //处理点击查看按钮显示
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