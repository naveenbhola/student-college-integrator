<html>
<head>
<title>Shiksha-Ebrochure-<?php echo $details['title']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="602" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #CCCCCC">
<tr><td valign="top">
<table width="582" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
		<td colspan="9">
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/mailer_01.gif" width="582" height="8" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2" valign="top">
			<a href="http://www.shiksha.com"><img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/mailer_02.gif" alt="" width="252" height="102" border="0"></a></td>
		<td colspan="4" valign="top">
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/mailer_03.gif" width="131" height="40" alt=""></td>
		<td colspan="3" valign="top">
			<a href="http://www.naukri.com"><img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/mailer_04.gif" alt="" width="199" height="40" border="0"></a></td>
	</tr>
	<tr>
		<td colspan="7" valign="top">
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/mailer_05.gif" width="330" height="62" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" valign="top">
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/mailer_06.gif" width="252" height="29" alt=""></td>
		<td colspan="7" valign="top">
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/mailer_07.gif" width="330" height="29" alt=""></td>
	</tr>
    <tr>
    	<td colspan="9" valign="top" background="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/bg_1.gif" style="background:url(http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/bg_1.gif) repeat-y left top">
        	<table cellpadding="0" cellspacing="5" border="0" width="100%">
                <tr>
                    <?php if(isset($details['institute_logo']) && ($details['institute_logo'] != ""))
                    {
                        $instituteLogoUrl = '<img src="'.$details['institute_logo'].'" alt="">';
                    }
                    else
                    {
                        $instituteLogoUrl = '&nbsp;';
                    }
                    ?>
                    <td width="346" valign="top"><?php echo $instituteLogoUrl; ?></td>
                    <td align="left" valign="top" style="padding-left:25px">
                    	<font face="Arial, Helvetica, sans-serif" size="2">
                        <?php if(isset($details['certification']) && $details['certification'] !="") { ?>
                        <b>Affiliated to:</b> <?php echo $details['certification'];?><br>
                        <?php } ?>
                        <?php if(isset($details['establish_year']) && $details['establish_year'] !="") { ?>
                        <b>Year of Establishment:</b> <?php echo $details['establish_year']; ?></font>                   
                        <?php } ?>
                        </font>
                    </td>
              </tr>
                <tr>
                    <td valign="top"><font face="Arial, Helvetica, sans-serif" size="3"><b><?php echo $details['title']; ?></b></font></td>
                    <td align="right" valign="top">&nbsp;</td>
                </tr>
        	</table>		</td>
    </tr>
	
	<tr>
		<td colspan="9" valign="top"  bgcolor="#a2ccf2"><img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/blk.gif" width="1" height="3" alt=""></td>
	</tr>
	<tr>
		<td colspan="9" valign="top"><img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/blk.gif" width="1" height="10" alt=""></td>
	</tr>
    <tr>
		<td colspan="9" valign="top">
        	<table cellpadding="0" cellspacing="0" border="0" width="582">
  <tr>
                	<td width="290" valign="top"><table width="281" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td colspan="2" valign="top"><img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/mailer_14.gif" width="280" height="27"></td>
                      </tr>
                      <tr>
                        <td colspan="2" valign="top"><img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/blk.gif" width="1" height="11"></td>
                      </tr>
                        <?php 
                        $optionalArgs = array();
                        $optionalArgs['location'] = $details['locations'][0]['city_name']."-".$details['locations'][0]['country_name'];
                        $optionalArgs['institute'] = $details['title'];
                        $i = 0;
                        foreach(unserialize(base64_decode($details['courseList'])) as $course)
                        {
                        $courseUrl = getSeoUrl($course['course_id'],'course',$course['courseTitle'],$optionalArgs);
                        $courseSubHead = '';
                        if($course['course_type'] != "")
                        $courseSubHead = $course['course_type'];
                        if($course['approvedBy'] != "")	
                        {
                        if($courseSubHead != "")
                        {
                        $courseSubHead = $courseSubHead.", ".$course['approvedBy'];
                        }
                        else
                        {
                        $courseSubHead = $course['approvedBy'];
                        }
                        }
                        echo '<tr>';
                        echo '<td width="19" valign="top"><img src="http://'.SHIKSHACLIENTIP.'/public/images/mailer/dDot.gif"></td>';
                        echo '<td width="262" valign="top" style="font-size:12px;font-family:Arial, Helvetica, sans-serif"><a href="'.$courseUrl.'" style="color:#0164d9;text-decoration:none">'.$course['courseTitle'].'</a><br/>';
                        echo $courseSubHead;
                        echo '<div style="line-height:5px">&nbsp;</div></td>
                        </tr>';
                        $i++;
                        if($i==4)
                        break;
                        }?>
                        <tr> 
                        <td colspan="2" valign="top" style="font-size:12px;font-family:Arial, Helvetica, sans-serif">
                        <?php
                        $optionalArgs = array();
                        $optionalArgs['location'] = $details['locations'][0]['city_name']."-".$details['locations'][0]['country_name'];
                        $instituteUrl = getSeoUrl($details['institute_id'],'institute',$details['title'],$optionalArgs);
                        ?>
                        <a href="<?php echo $instituteUrl; ?>" style="color:#0164d9;text-decoration:none">Show All Courses</a>                        </td>
                      </tr>
                    </table></td>
                <td valign="top">&nbsp;</td>
                    <td width="292" align="center" valign="top" bgcolor="#F6F4F5" style="border:1px solid #d8d8d8"><table width="277" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="27" valign="middle" style="font-size:12px;font-family:Arial, Helvetica, sans-serif;border-bottom:1px solid #dfddde"><b>Contact Details:</b></td>
                      </tr>
                      <tr>
                        <td valign="top" style="font-size:12px;font-family:Arial, Helvetica, sans-serif;line-height:20px">
                            <?php if(isset($details['contact_name']) && $details['contact_name'] != "") { 
                            echo "<div><b>Name of Person:</b> ".$details['contact_name']."</div>";
                            } 
                            $phoneToDisplay = "";
                            if($details['contact_main_phone'] != "")
                            {
                            $phoneToDisplay = $details['contact_main_phone'];
                            }
                            if($details['contact_cell'] != "")
                            {
                            $phoneToDisplay = $phoneToDisplay == "" ? $details['contact_cell']: $phoneToDisplay.",".$details['contact_cell'];
                            }
                            if($details['contact_cell'] != "")
                            {
                            $phoneToDisplay = $phoneToDisplay == "" ? $details['contact_cell']: $phoneToDisplay.",".$details['contact_cell'];
                            }
                            if($phoneToDisplay != "")
                            {
                            echo "<div><b>Contact No.:</b> ".$phoneToDisplay."</div>";
                            }		
                            ?>
                            <?php if(isset($details['contact_email']) && $details['contact_email'] != "") { 
                            echo "<div><b>Email:</b>".$details['contact_email']."</div>";
                            }
                            $contactAddress = '';
                            if(isset($details['locations'][0]['address']) && $details['locations'][0]['address'] != "") {
                            $contactAddress = $details['locations'][0]['address'];
                            }
                            if(isset($details['locations'][0]['city_name']) && $details['locations'][0]['city_name'] != "") {
                            if($contactAddress != "")
                            {
                            $contactAddress .= ", ";
                            }
                            $contactAddress = $contactAddress.$details['locations'][0]['city_name'].", ".$details['locations'][0]['country_name'] ;
                            }
                            if(isset($details['locations'][0]['pincode']) && $details['locations'][0]['pincode'] != "") {
                            if($contactAddress != "")
                            {
                            $contactAddress .= "- ";
                            }
                            $contactAddress = $contactAddress. $details['locations'][0]['pincode']; 
                            }
                            if($contactAddress != "")
                            {
                            echo "<div><b>Address:</b></div>";
                            echo "<div style='line-height:16px'>".$contactAddress."</div>";
                            }
                            ?>                        </td>
                      </tr>
                    </table></td>
              </tr>
            	<tr>
            	  <td valign="top">&nbsp;</td>
            	  <td valign="top">&nbsp;</td>
            	  <td valign="top">&nbsp;</td>
          	  </tr>
				<?php if(count($photoList) > 0) { ?>              
                <tr>
	                <td height="25" colspan="3" valign="middle" bgcolor="#E2F0FD" style="padding-left:10px;border:1px solid #c2e0fa"><font size="2" face="Arial, Helvetica, sans-serif"><b>Gallery:</b></font></td>
                </tr>
                <tr>
                    <td colspan="3" valign="top">
                    <font face="Arial, Helvetica, sans-serif" style="font-size:12px">
                    <table width="582" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="70" valign="top">&nbsp;</td>
                        <td width="100" valign="top">&nbsp;</td>
                        <td width="70" valign="top">&nbsp;</td>
                        <td width="100" valign="top">&nbsp;</td>
                        <td width="70" valign="top">&nbsp;</td>
                        <td width="100" valign="top">&nbsp;</td>
                        <td width="70" valign="top">&nbsp;</td>
                      </tr>
                      <tr>
                        <?php
                        $i=0;
                        for($i=0;$i<3;$i++) { 
                        if(isset($photoList[$i]))
                        {
                        $photo = $photoList[$i];
                        $thumbUrl = "<img src=\"".$photo['thumburl']."\" width=\"78\" height=\"78\" border=\"0\">";
                        $photoName = "<a href=\"".$photo['thumburl']."\" style=\"color:#0164d9;text-decoration:none\">".$photo['name']."</a>";
                        }
                        else
                        {
                        $thumbUrl = "&nbsp;";
                        $photoName = "&nbsp;";
                        } 	
                     	?>
                        <td valign="top">&nbsp;</td>
                        <td align="center" valign="top" style="border:1px solid #CCCCCC">
                        <table width="80%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                        <td valign="top"><img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/blk.gif" width="1" height="9"></td>
                        </tr>
                        <tr>
                        <td valign="top"><?php echo $thumbUrl; ?></td>
                        </tr>
                        <tr>
                        <td height="25" align="center" valign="middle"><font size="2" face="Arial, Helvetica, sans-serif"><?php echo $photoName; ?></font></td>
                        </tr>
                        </table>                        </td>
                        <?php 
                        } 
                        ?>                                                                        
                      </tr>
                      
                      <tr>
                        <td valign="top">&nbsp;</td>
                        <td valign="top">&nbsp;</td>
                        <td valign="top">&nbsp;</td>
                        <td valign="top">&nbsp;</td>
                        <td valign="top">&nbsp;</td>
                        <td valign="top">&nbsp;</td>
                        <td valign="top">&nbsp;</td>
                      </tr>
                    </table>                    
                    </font>                    </td>
                </tr>                
                <tr>
	                <td colspan="3" valign="top">&nbsp;</td>
                </tr>
                <? } ?> 

				<?php foreach($detailPageComponents as $listingDetailComponent)  { ?>
                <tr>
	                <td height="25" colspan="3" valign="middle" bgcolor="#E2F0FD" style="padding-left:10px;border:1px solid #c2e0fa">
                    <font size="2" face="Arial, Helvetica, sans-serif"><b><?php echo $listingDetailComponent['title']; ?></b></font>                    
                    </td>
                </tr>
                <tr>
                    <td colspan="3" valign="top" style="padding-left:10px;padding-top:7px">
                    <font face="Arial, Helvetica, sans-serif" style="font-size:12px">
                    <p><?php echo $listingDetailComponent['value']; ?></p>                    
                    </font>                    
                    </td>
                </tr>
                <tr>
	                <td colspan="3" valign="top">&nbsp;</td>
                </tr>
                <?php } ?>
                <?php if(count($docList) > 0) { ?>
                <tr>
	                <td height="25" colspan="3" valign="middle" bgcolor="#E2F0FD" style="padding-left:10px;border:1px solid #c2e0fa"><font size="2" face="Arial, Helvetica, sans-serif"><b>Brochure, Presentations &amp; Other Documents:</b></font></td>
                </tr>
                <tr>
                    <td colspan="3" valign="top">
                    <div style="line-height:15px">&nbsp;</div>
                    <font face="Arial, Helvetica, sans-serif" style="font-size:12px">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="font-size:12px">
                        <tr>
                        <?php 
                        $j=0;
                        for($i=0;$i<((int)(count($docList)/3))*3 + 3 ; $i++) {
                        if(isset($docList[$i]))
                        {
                            $document = $docList[$i];
                            //$docContent='<img src="'.SHIKSHACLIENTIP.'/public/images/mailer/wordIcon.gif" align="absmiddle">&nbsp; &nbsp;<span style="color:#999999">Title:</span> <a href="'.$document['url'].'">'.$document['name'].'</a>'; 
                            $docContent='<span style="color:#999999">Title:</span> <a href="'.$document['url'].'">'.substr($document['name'],0,15).'</a>'; 
                        }
                        else
                        {
                            $docContent = '&nbsp;';
                        }
                        echo  '<td width="10" height="30px" valign="top"><img src="'.SHIKSHACLIENTIP.'/public/images/mailer/blk.gif" width="10" height="1"></td>';
                        echo  '<td valign="top">'.$docContent.'</td>';
                        $j++;
                        if($j==3)
                        {
                        $j=0;
                        echo "</tr><tr>";
                        }
                        }
                        ?>
		                </tr>                        
                    </table>                  
                    </font>
                    </td>
                </tr>
                <tr>
	                <td colspan="3" valign="top">&nbsp;</td>
                </tr>
                <?php } ?>
          </table>        </td>
	</tr>
	<tr>
		<td height="102" colspan="9" valign="top" align="center"><font size="1" color="#999999" face="Arial, Helvetica, sans-serif">
        For question, advertising information, or to provide feedback, email: <a href="mailto:support@shiksha.com">support@shiksha.com</a><br><br>Copyright &copy; 2009 Shiksha.com. All rights reserved.<br><u>Shiksha.com</u> is located at A-88, Sector-2, NOIDA, UP 201302, INDIA</font></td>
	</tr>
	<tr>
		<td>
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/spacer.gif" width="11" height="1" alt=""></td>
		<td>
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/spacer.gif" width="241" height="1" alt=""></td>
		<td>
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/spacer.gif" width="28" height="1" alt=""></td>
		<td>
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/spacer.gif" width="9" height="1" alt=""></td>
		<td>
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/spacer.gif" width="68" height="1" alt=""></td>
		<td>
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/spacer.gif" width="26" height="1" alt=""></td>
		<td>
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/spacer.gif" width="192" height="1" alt=""></td>
		<td>
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/spacer.gif" width="6" height="1" alt=""></td>
		<td>
			<img src="http://<?php echo SHIKSHACLIENTIP; ?>/public/images/mailer/spacer.gif" width="1" height="1" alt=""></td>
	</tr>
</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
</body>
</html>
