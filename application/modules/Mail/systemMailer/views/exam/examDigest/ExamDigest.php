<?php
$examName  = $examInfo['examName'];
$examUrl   = $examInfo['examUrl'];
$examNameForMailer = $examInfo['examNameForMailer'];
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
           <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#5a595c;">Dear <?php echo $firstName;?>, here is everything about <a href="<?php echo SHIKSHA_HOME.$examUrl;?>" target="_blank" style="font-weight:bold; color:#2071b0;"><?php echo $examNameForMailer;?></a> you would want to know:</td>
          </tr>
          <tr>
           <td valign="top" height="12"></td>
          </tr>
          <tr>
           <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #e0dddd;">
		<?php if(!empty($instituteAcceptingSectionData)){ ?>
                <?php $this->load->view('systemMailer/exam/examDigest/instituteSection', $instituteAcceptingSectionData);?>
		<tr>
                 <td valign="top" height="20"></td>
                </tr>
		<?php } ?>
                <?php if($iimcallPredictor['showIIMCallPredictor']){ ?>
                <?php  $this->load->view('systemMailer/exam/examDigest/iimCallPredictor', $iimcallPredictor);?>
		 <tr>
                 <td valign="top" height="10"></td>
                </tr>
                <tr>
                 <td valign="top" height="1" bgcolor="#e0dddd"></td>
                </tr>
		<tr>
                 <td valign="top" height="16"></td>
                </tr>
               <?php } ?>
		<?php if(!empty($samplePapers)){ ?>
                <?php $this->load->view('systemMailer/exam/examDigest/samplePapers',$samplePapers);?>
		<tr>
                 <td valign="top" height="16"></td>
                </tr>
                <?php } ?>
		<?php 
		if(!empty($article)){
                	$this->load->view('systemMailer/exam/examDigest/article',$article);
		 }
                if(!empty($ana)){
			$this->load->view('systemMailer/exam/examDigest/ana' , $ana);
		}
		if(!empty($similarExams)){
                	$this->load->view('systemMailer/exam/examDigest/similarExams' , $similarExams);
		}
		?>
               </table>
              </td>
              <td width="14"></td>
             </tr>
            </table>
           </td>
          </tr>
         </table>
        </td>
        <td width="20"></td>
       </tr>
      </table>
     </td>
    </tr>
