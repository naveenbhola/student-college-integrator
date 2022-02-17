  <tr>
    <!--td width="18"><img src="<?php //echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="8" height="1" vspace="0" hspace="0" align="left"></td-->
    <td width="564" bgcolor="#ffffff" align="center">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#585c5f;">
          <tr>
            <td colspan="3" height="20"></td>
          </tr>
          <tr>
            <td width="20"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
            <td width="524" align="center">
            	<table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:18px; text-align:left;" width="100%">
                  <tr>
                    <td>Hi <strong><?php echo $firstname;?>!</strong></td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td width="524">You showed interest in a career as <?php echo checkifvowel($mainCareerName);?>&nbsp;<strong><?php echo $mainCareerName;?></strong>. We thought you might consider these career options as well:</td>
                  </tr>
                </table>
			</td>
            <td width="20"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
          </tr>
          <tr>
            <td></td>
            <td valign="top">
            <?php if(!empty($finalArr)){
                    foreach($finalArr as $key=>$value){
                            $minSalInLacs= $value['minSalInLacs'];
                            $maxSalInLacs= $value['maxSalInLacs'];
                            $minSalInThousand= $value['minSalInThousand'];
                            $maxSalInThousand= $value['maxSalInThousand'];
                            $difficulityLevel = $value['difficulityLevel'];
                            $autoLoginURL = $value['autoLoginURL'];
                            $careerName = $value['careerName'];
                            $imageUrl = $value['imageUrl'];
			    $shortDescription = $value['shortDescription'];
                    ?>
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="display:block;">
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td width="524" valign="top">
                    	<table width="140" border="0" cellspacing="0" cellpadding="0" align="left">
                          <tr>
                            <td><img src="<?php echo $imageUrl;?>" width="120" height="139" vspace="0" hspace="0" align="left" /></td>
                          </tr>
                          <tr>
                            <td height="5">&nbsp;</td>
                          </tr>
                        </table>
						<table border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial; font-size:13px; color:#474a4b; max-width:377px;">
                          <tr>
                            <td><a href="<?php echo $autoLoginURL;?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Georgia, 'Times New Roman', Times, serif; color:#0065e8; font-size:20px; text-decoration:none;"><font color="#0065e8"><?php echo $careerName;?></font></a></td>
                          </tr>
                          <tr>
                            <td width="377"><?php echo substr($shortDescription,0,170);?> ...</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
			  <?php
			    if(!empty($minSalInLacs) || !empty($maxSalInLacs) || !empty($minSalInThousand) || !empty($maxSalInThousand))
			    {
				    $minSalToDisplay = '';
				    if($minSalInLacs>1){$minLakh = ' Lakhs ';}else{$minLakh = ' Lakh ';}
				    if($maxSalInLacs>1){$maxLakh = ' Lakhs ';}else{$maxLakh = ' Lakh ';}
				    if(!empty($minSalInLacs)){$minSalToDisplay=$minSalInLacs+($minSalInThousand/100).$minLakh; }else{$minSalToDisplay=$minSalInThousand.' Thousand ';}
				    $maxSalToDisplay = '';
				    if(!empty($maxSalInLacs)){$maxSalToDisplay=$maxSalInLacs+($maxSalInThousand/100).$maxLakh; }else{$maxSalToDisplay=$maxSalInThousand.' Thousand ';}
			    ?>
                          <tr>
                            <td style="font-size:12px; color:#525252;"><img src="<?php echo SHIKSHA_HOME;?>/public/images/careers/rupeeIC.gif" width="18" height="16" vspace="0" hspace="0" align="absmiddle" /><font color="#7e7e7e">Salary:</font>
			    <strong>
				<?php if((!empty($minSalInLacs) || !empty($minSalInThousand)) && (!empty($maxSalInLacs) || !empty($maxSalInThousand))){
				echo $minSalToDisplay;?> to <?php echo $maxSalToDisplay;?> per annum
				<?php } ?>

				<?php if((empty($minSalInLacs) && empty($minSalInThousand)) && (!empty($maxSalInLacs) || !empty($maxSalInThousand))){
				?> Up to <?php echo $maxSalToDisplay;?> per annum
				<?php } ?>

				<?php if((!empty($minSalInLacs) || !empty($minSalInThousand)) && (empty($maxSalInLacs) && empty($maxSalInThousand))){
				?> Starts from <?php echo $minSalToDisplay;?> per annum
				<?php } ?>	
				
			    </strong></td>
                          </tr>
			   <?php 
			    }
			    ?>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
			    <?php if($difficulityLevel=='High'){
				$color = "color:#b40000";
			    }else if($difficulityLevel=='Medium'){
				$color = "color:#169406";
			    }else{
				$color = "color:#169406";
			    }
			    ?>
                            <td style="font-size:12px;"><img src="<?php echo SHIKSHA_HOME;?>/public/images/careers/acadmicIC.gif" width="18" height="16" vspace="0" hspace="0" align="absmiddle" /><font color="#7e7e7e">Academic Difficulty:</font> <font style="<?php echo $color;?>"><strong><?php echo $difficulityLevel;?></strong></font></td>
                          </tr>
                          <tr>
                            <td height="8"></td>
                          </tr>
                          <tr>
                            <td>
                            	<table width="100" cellspacing="0" cellpadding="0" border="0" style="font-family:Arial;font-size:13px;color:rgb(71,74,75);display:block">
    <tbody><tr>
      <td width="100" bgcolor="#ffda3e" align="center" height="28" style="border:1px solid #e8b363;border-radius:2px"><a target="_blank" style="text-decoration:none;color:rgb(75,75,75);display:block;font-size:14px;line-height:26px" title="Read more" href="<?php echo $autoLoginURL;?><!-- #AutoLogin --><!-- AutoLogin# -->"><b>Read more</b></a></td>
    </tr>
    </tbody></table>
                            </td>
                          </tr>
                        </table>

                    </td>
                  </tr>
                  <tr>
                    <td height="8" style="border-bottom:1px solid #e4e4e4;"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="1" height="8" vspace="0" hspace="0" align="left" /></td>
                  </tr>
                </table>
       <?php }} ?>
      </td>
            <td></td>
          </tr>
        </table>
    </td>
    <!--td width="18"><img src="<?php //echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="8" height="1" vspace="0" hspace="0" align="left"></td-->
  </tr>
  <tr>
    <td></td>
    <td bgcolor="#ffffff" height="28"></td>
    <td></td>
  </tr>