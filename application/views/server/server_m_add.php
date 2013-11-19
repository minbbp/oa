<?php echo form_open("server_type/server_add",array('class'=>'form-horizontal','id'=>'table_m'))?>

<table class="table table-bordered">
<h5>选择父类为：
<select  id="select-type" name="st_pid" >
</h5>
<option value="0">顶级服务</option>
<?php foreach($list as $value){ ?>
<option value="<?php echo $value['st_id'] ?>"  ><?php echo $value['st_name'] ?></option>
<?php } ?>
</select>
<br />
<br />
<h5>添加服务名：  <input type="text" name="st_name" value=""></h5><br />
添加服务描述：<textarea rows="5" class="span4" name="st_desc" id="textarea-desc" value=""></textarea><br /><br />
<div style="text-align: center"><input type="radio" name="st_yezi" value="1" > &nbsp显示服务</div><br />
<div style="text-align: center"><input type="radio" name="st_yezi" value="0" checked> &nbsp不显示服务</div>
</table>
</form>