<?php echo form_open("server_type/server_edit",array('class'=>'form-horizontal','id'=>'table_m'))?>
<table class="table table-bordered">
当前父类为：&nbsp&nbsp
<select  id="select-type" name="st_pid" >
    <option value="0">顶级服务</option>
<?php foreach($list as $value){ ?>
<option value="<?php echo $value['st_id'] ?>" 
<?php 
 if($value['st_id']==$arr['st_pid']){
 echo "selected"; } 
?> ><?php echo $value['st_name'] ?></option>
<?php } ?>
</select>
<br /><br />
<p>当前编辑为：&nbsp&nbsp <input type="text" name="st_name" value="<?php echo $arr['st_name'] ?>"></p><br />
添加服务描述：<textarea rows="5" class="span4" name="st_desc" id="textarea-desc" value=""><?php echo $arr['st_desc'] ?></textarea><br /><br />
<div style="text-align: center"><input type="radio" name="st_yezi" value="1" <?php if( $arr['st_yezi'] == 1){echo "checked";}?>> &nbsp显示服务</div><br />
<div style="text-align: center"><input type="radio" name="st_yezi" value="0" <?php if( $arr['st_yezi'] == 0){echo "checked";}?>> &nbsp不显示服务</div>
<input type="hidden" name="st_id" value="<?php echo $arr['st_id'] ?>">
</table>
    </form>