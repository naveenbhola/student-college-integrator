<style>
.upload-payment{background: #f1f1f1 none repeat scroll 0 0; margin-bottom: 20px; margin-left: 235px; margin-top: 20px; padding: 10px 20px; width: 500px;display: block;}
.spn{display: inline-block;padding: 5px 15px;font-size: 13px;font-weight: bold;}
.spn.failed{color:red;}
.spn.success{color:green;}
p.plog{display: inline-block;font-size: 15px;margin-right: 106px;padding: 0 0 10px;padding:0 0 10px;}
</style>
 <div class="upload-payment" >

 <form action="/CAEnterprise/CampusAmbassadorEnterprise/validateCAPayment" accept-charset="utf-8" method="post" enctype="multipart/form-data" id="_makePayment" name="_makePayment">

 	<table cellspacing="0" 
cellpadding="1" border="0" height="100" width="100%" align="center">
 		<tr><td width="30%" align="right" style="font-size:13px;">Upload File:</td><td align="center"><input type="file" name="datafile"></td></tr>
 		<tr><td colspan="2" align="center"><input type="button" value="Submit" name="payment" class="orange-button" onclick="_validateUploadFile('_makePayment','getPayData');"></td></tr>
 		<tr><td colspan="2" align="center" id="error" style="color:red;font-size:13px;" class="_box"></td></tr>
 	</table>

 </form>
 </div>
<div style="padding: 0 0 60px;display:block;width:100%;float;left" id="_paymentLog" class="_box"></div>
