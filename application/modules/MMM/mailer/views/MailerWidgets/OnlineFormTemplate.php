
<?php if(trim($onlineFormUserName)) {?>
	Dear <?php echo $onlineFormUserName;  ?><br><br>
		It seems that you started filling up your application form for the institutes mentioned below, but were not able to complete it. Please complete your application form in order to be considered for admission by the institute(s). Click the link below to complete your form:<br><br>
	<?php }  ?>
     
     
     <?php
		$institutescount = count($institute_name);
	       for($i = 0; $i < $institutescount; $i++)
               {    ?>
                         <td height="10"><a style="font-size:20px; text-decoration:none; color:#0065e8; font-family:Georgia, 'Times New Roman', Times, serif;" target="_blank" href="<?php echo $online_form_url[$i]; ?>~onlineform<!-- #widgettracker --><!-- widgettracker# -->">Application for <?php echo $institute_name[$i]." - ".$course_name[$i];   ?></a>
                         </td>
                         <br>
     <?php } ?>



