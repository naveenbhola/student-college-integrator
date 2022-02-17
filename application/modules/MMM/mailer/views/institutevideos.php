<tr>
    <td></td>
    <td><table border="0" bgcolor="#ffffff" width="100%" cellspacing="0" cellpadding="0">
        <tbody><tr>
          <td width="30" height="38"></td>
          <td valign="top"><a style="font-size:20px; text-decoration:none; color:#0065e8; font-family:Georgia, 'Times New Roman', Times, serif;" target="_blank" href="#"><?php echo $name; ?></a><font color="#474a4b" face="Arial, Helvetica, sans-serif" style="font-size:16px;">, <?php echo $location; ?></font></td>         
          <td valign="top"><table border="0" bgcolor="#ffcf0d" background="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/butt_mrec_b.gif" width="120" align="right" cellspacing="0" cellpadding="0">
           <?php if($showOFLink) {?>
              <tbody><tr>
                <td align="center" height="28" style="font-family:Arial, Helvetica, sans-serif; color:#333333; font-size:12px;"><a style="text-decoration:none; font-size:12px; color:#333333; line-height:28px; height:28px;" target="_blank" title="Update your profile now!" href="<?php echo $OFlink; ?>"><strong>Apply online now</strong></a></td>
              </tr>
            </tbody>
          <?php }?></table></td>
          <td width="29"></td>
        </tr>
      </tbody></table></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td><table border="0" bgcolor="#ffffff" width="100%" cellspacing="0" cellpadding="0">
        <tbody><tr>
          <td width="30" height="30"></td>
          <td bgcolor="#e1e4e8" width="15"></td>
          <td bgcolor="#e1e4e8"><font color="#474a4b" face="Arial, Helvetica, sans-serif" style="font-size:16px;">Take a visual tour</font></td>
          <td width="29"></td>
        </tr>
      </tbody></table></td>
    <td></td>
  </tr>
  
  <tr>
    <td></td>
    <td><table border="0" bgcolor="#ffffff" width="100%" cellspacing="0" cellpadding="0">
        <tbody><tr>
          <td width="30" height="110"></td>
          <td align="">
	  <?php

	  foreach($photos as $phototraversal){      
		  ?>
			  <img border="0" width="118" vspace="0" align="left" hspace="0" height="80" style="padding-right:9px;"
			  src="<?php echo $phototraversal; ?>" href="<?php echo $photoTabUrl; ?>">
			  <?php  } ?>

			  <?php
			  foreach($videos as $videotraversal){      
				  ?>
					  <object name="" width="118" height="80" uniqueattr="Listing-Video">
					  <param id="param1" name="movie" value="<?php echo $videotraversal; ?>"></param>
					  <param id="param2" name="allowFullScreen" value="true"></param>
					  <param id="param3" name="allowscriptaccess" value="always"></param>
					  <embed id="objembed" wmode="transparent" src="<?php echo $videotraversal; ?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="118" height="80"></embed>
					  </object>
       				 <?php  } ?>
          <td ></td>
        </tr>
      </tbody></table></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td><table border="0" bgcolor="#ffffff" width="100%" cellspacing="0" cellpadding="0">
        <tbody><tr>
          <td width="30" height="40"></td>
          <td valign="top" align="right" style="font-family:Arial, Helvetica, sans-serif;"><a style="font-size:12px; text-decoration:none; color:#3465e8;" target="_blank" href="<?php echo $photoTabUrl; ?>"><?php if($Photoscount > 0){ ?> View <?php echo $Photoscount; ?> Photos <?php } ?><span style="letter-spacing:-1px"></span></a>  <font color="#3d3d3d" style="font-size:12px;"></font>  <a style="font-size:12px; text-decoration:none; color:#3465e8;"  href="<?php echo $photoTabUrl; ?>"><?php if($Videoscount > 0){ ?> | <?php echo $Videoscount; ?> Videos <?php } ?><span style="letter-spacing:-1px">  </span><a href="<?php echo $photoTabUrl; ?>" ></a></td>
          <td width="22"></td>
          <td width="29"></td>
        </tr>
      </tbody></table></td>
    <td></td>
  </tr>