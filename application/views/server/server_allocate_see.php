<?php echo form_open("server_manage/server_allocate_add",array('class'=>'form-horizontal','id'=>'table_m'))?>
<div class="control-group">
  <label class="control-label" for="inputaccount">帐号</label>
  <div class="controls">
    <input type="text" name="account" id="inputaccount" placeholder="帐号"  value=""/>
    <span class="help-inline"></span>
  </div>
</div>
<input type="hidden" name ="sn_id" value="<?php echo $sn_id ?>">
<input type="hidden" name ="s_id" value="<?php echo $s_id ?>">
<input type="hidden" name ="sa_id" value="<?php echo $sa_id ?>">
</form>