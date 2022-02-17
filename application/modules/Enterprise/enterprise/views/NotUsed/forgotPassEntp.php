
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
      <title>Enterprise-Shiksha Login Page</title>

<?php
   $taburl= site_url('enterprise/Enterprise/index/6');
$headerComponents = array(
                                                                'css'	=>	array('headerCms','raised_all','mainStyle','footer'),
                                                                'js'	=>	array('common','prototype','user','md5'),
                          					'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
								'tabName'	=>	'',
                                                                'taburl' => site_url(''),
								'metaKeywords'	=>''
							);
		$this->load->view('enterprise/headerCMS', $headerComponents);
?>

   </head>
   <div class="row">
      <div class="float_L bld txt_align_r" style="width:100px; line-height:20px">Email Id:</div>
      <div style="margin-left:105px"><input type="text" size="20" name="username" id="username" value="" maxlength = "100" minlength = "2" validate = "validateStr" /></div>

      <div class="row errorPlace" style="margin-top:2px;">
         <div class="r1_1">&nbsp;</div>
         <div class="r2_2 errorMsg" id="username_error"></div>
         <div class="clear_L"></div>
      </div>



      <button id="forgotPasswordSubmitBtn" class="btn-submit19 w4" onclick="javascript:sendForgotPasswordMail();" type="button" name="submit" value="Login">
         <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;" class="btn-submit20 btnTxtBlog">Submit</p></div>
      </button>
      <div class="clear_L"></div>
   </div>
<?php $this->load->view('enterprise/footer'); ?>
