<?php
$examName  = htmlentities($examInfo['examName']);
$examUrl   = $examInfo['examUrl'];
$examNameForMailer = htmlentities($examInfo['examNameForMailer']);
$profileUrl        = SHIKSHA_HOME.'/userprofile/'.$userInfo['user_id'];
$firstName         = $userInfo['first_name'];
if(!empty($instituteAcceptingSectionData)){
	$instituteAcceptingSectionData['examName'] = $examName;
}
if(!empty($similarExams)){
        $similarExams['examName'] = $examName;
}
if(!empty($samplePapers)){
        $samplePapers['examName'] = $examName;
}
if(!empty($ana)){
        $ana['examName']          = $examName;
}
?>
          <tr>
           <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#5a595c;text-align:left">Dear <?php echo $firstName;?>, here is everything about <a href="<?php echo $examUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-weight:bold; color:#03818d;"><?php echo $examNameForMailer;?></a> you would want to know:</td>
          </tr>
          <tr>
           <td valign="top" height="12"></td>
          </tr>
          <tr>
           <td valign="top" align="center">
            <table width="95%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #e0dddd;">
	   <tr>
              <td valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
              <tr>
              <td valign="top" height="16"></td>
              </tr>
		<?php if(!empty($instituteAcceptingSectionData)){ ?>
                <?php $this->load->view('personalizedMailer/widgets/examDigest/instituteSection', $instituteAcceptingSectionData);?>
		<tr>
                 <td valign="top" height="20"></td>
                </tr>
		<?php } ?>
                <?php if($iimcallPredictor['showIIMCallPredictor']){ ?>
                <?php  $this->load->view('personalizedMailer/widgets/examDigest/iimCallPredictor', $iimcallPredictor);?>
		 <tr>
                 <td valign="top" height="10"></td>
                </tr>
		<tr>

                          <td valign="top" align="center">
				<table width="97%" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
				  <tbody><tr>
					  <td height="1" bgcolor="#e0dddd"></td>
				  </tr></tbody>
				</table>
			 </td>
                </tr>
		<tr>
                 <td valign="top" height="16"></td>
                </tr>
               <?php } ?>
		<?php
     if(!empty($sampleAndGuidePapers['samplePaperData'])){ 
            $viewData['viewData'] = $sampleAndGuidePapers['samplePaperData'];
            $viewData['heading'] = 'Question Papers';
            $viewData['count'] = $sampleAndGuidePapers['samplePaperCount'];
            $viewData['viewAllLink'] = $sampleAndGuidePapers['viewAllLink'];
            $viewData['examName'] = $examName;
      ?>
                <?php $this->load->view('personalizedMailer/widgets/examDigest/samplePapers', $viewData);
                ?>

		<tr>
                 <td valign="top" height="16"></td>
                </tr>
                <?php } ?>
    <?php if(!empty($sampleAndGuidePapers['guidePaperData'])){ 
            $viewData['viewData'] = $sampleAndGuidePapers['guidePaperData'];
            $viewData['heading'] = 'Prep Guides';
            $viewData['count'] = $sampleAndGuidePapers['guidePaperCount'];
            $viewData['viewAllLink'] = $sampleAndGuidePapers['viewAllLink'];
            $viewData['examName'] = $examName;

      ?>
                <?php $this->load->view('personalizedMailer/widgets/examDigest/samplePapers',$viewData);
                ?>

    <tr>
                 <td valign="top" height="16"></td>
                </tr>
                <?php }?>
		<?php 
		if(!empty($article)){
                	$this->load->view('personalizedMailer/widgets/examDigest/article',$article);?>
     <tr>
                 <td valign="top" height="16"></td>
                </tr>
		<?php } ?>
              <?php
                if(!empty($ana)){ $this->load->view('personalizedMailer/widgets/examDigest/ana' , $ana);?>
                <tr><td valign="top" height="16"></td></tr>
              <?php }?>
              <?php if(!empty($preptipsData)){
                  $viewData['viewData'] = $preptipsData['preptipsData'];
                  $viewData['heading']  = $preptipsData['heading']; 
                  $viewData['count']    = $preptipsData['prepTipsCount'];
                  $viewData['viewAllLink'] = $preptipsData['viewAllLink'];
                  $viewData['examName']    = $examName;
                  $this->load->view('personalizedMailer/widgets/examDigest/prepTips',$viewData);?>
                <tr><td valign="top" height="16"></td></tr>
              <?php }

		if(!empty($similarExams)){
                	$this->load->view('personalizedMailer/widgets/examDigest/similarExams' , $similarExams);
		}
		?>
         
         </table>
        </td>
       </tr>
      </table>
     </td>
    </tr>
