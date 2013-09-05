<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/office/2004/12/omml" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="Generator" content="Microsoft Word 12 (filtered medium)">
    <title>运维OA_git账号申请回复邮件</title>
</head>
  <body>
 <?php if ($git['h_state']==1):?>
 <p> 您的git账号申请，已经通过审核，我们已经通知相关的操作人员对您的账号进行开通！</p>
 <?php else:?>
 <p>您的主管驳回了您的git账号申请，驳回原因 <?php echo $git['h_description']?></p>
 <?php endif;?>
<p class="text-success"> 此邮件系系统自动发送，请不要回复，谢谢！</p>
<p>祝：工作愉快！</p>
</body>
</html>
