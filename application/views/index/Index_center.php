 <script type="text/javascript">
function setheight(height)
{
	var frame_element=document.getElementById('index_center');
	if(height<800)
	{
		frame_element.height=800;
	}
	else
	{
		frame_element.height=height;
	}
	//this.height=index_center.document.body.scrollHeight
}
 </script>
 <div class="span9 " >
 <iframe  name="index_center" id="index_center" src="<?php echo base_url('index.php/index/center');?>" frameborder="0" width="870"  onload='setheight(index_center.document.body.scrollHeight)'></iframe>
 </div>
 </div>
 </div> 
 </body>
 </html>
