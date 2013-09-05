<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/office/2004/12/omml" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="Generator" content="Microsoft Word 12 (filtered medium)">
    <title>运维OA_git账号申请回复邮件</title>
</head>
  <body>
<p><?php echo $username?>于<?php echo date('Y-m-d H:i:s',$content['addtime'])?>申请<?php if($content['add_datagroups']==1){echo "data组";}else{echo ' 其他组';}?>git账号
，该账号正在等待 <?php echo $level?>审核
<p class="text-success"> 此邮件系系统自动发送，请不要回复，谢谢！</p>
<p>祝：工作愉快！</p>
  
  
</body>
</html>