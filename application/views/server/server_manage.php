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
                        <form action="<?php echo site_url('server_manage/index')?>" method="get" accept-charset="utf-8" class="form-horizontal" id="server_form">   
                        <select name="type[]" id="select_all" class="span2">
                         <option value="s_internet" >ip(内网)</option>
                        <option value="s_use" >用途</option>
                        <option value="s_type">服务</option>
                        <option value="owner">使用人</option>
                        <option value="s_cpu">cpu</option>
                        <option value="s_mem">mem</option>
                        <option value="s_disk">disk</option>
                        </select>
                        <div class="input-append">
                          <input class="span2" id="keyword" name="keyword" placeholder="请输入关键字" type="text" value="<?php echo $u_keyword; ?>"  autocomplete="off">
                          <button class="btn btn-info " id="yes" type="submit">Go!</button>
                        </div>
                        </form>
     <hr />
     <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>ip地址</th>
                  <th>使用状态</th>
                  <th>用途</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach ($list as $v) {  ?>
                <tr>
                  <td><?php echo $v['s_internet']; ?></td>
                  <td><?php echo ($v['s_owner'] == '暂时没有使用人') ? "空闲":'已被使用'; ?></td>
                  <td><?php echo $v['sn_use']; ?></td>
                  <td>
                  <?php echo anchor('server_manage/server_see/'.$v['s_id'],'查看',"class='see'"  ); ?>
                  <?php echo anchor('server_manage/server_update/'.$v['s_id'],'编辑' ); ?>
                  <?php echo anchor('server_manage/server_del/'.$v['s_id'],'删除',"class='deltype'" ); ?>
                  </td>
                </tr>
                 <?php  } ?>
              </tbody>
            </table>
     <?php echo $link ?>
<div>
 <a href="<?php echo site_url('server_manage/server_add/') ?>" class="btn btn-success"  >添加服务器</a>
 </div>
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
    <button class="btn btn-danger  " data-dismiss="modal" aria-hidden="true">关闭</button>
  </div>
</div>
</div>
<!-- Modal -->
</div>
<script>
    $(function(){
        var u_type = "<?php echo $u_type; ?>";
        $("select option").each(function(){
            if($(this).val() == u_type){
                $(this).attr('selected','selected');
            }
        })
        $("#yes").click(function(){
            var type = $('#select_all option:selected').val();
            var kw = $('#keyword').val();
            var action = $('#server_form').attr('action');
            var href = action+'/'+type+'/'+kw;
            location.href=href;
            return false;
        })
        //处理点击查看按钮显示
	$(".see").click(function(){
            var href=$(this).attr("href");
            var time=new Date().getTime();
            $("#myModal .modal-body").empty().load(href,{time:time});
            $("#myModal").modal();
            return false;
	}); 
           $(".deltype").click(function(){
            var href=$(this).attr("href");
            layer.confirm('确定删除?(须先删除服务器所有帐号)', function(){ 
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
        
    })
</script>
  </body>
  </html>