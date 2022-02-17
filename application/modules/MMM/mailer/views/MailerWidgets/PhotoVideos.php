<tr>
   <td></td>
   <td bgcolor="#ffffff">
<table border="0" bgcolor="#ffffff" width="92%" cellspacing="0" cellpadding="0" align="center">
<tr>
    <td align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody><tr>
          <td bgcolor="#e1e4e8" width="15" height="30"></td>
          <td bgcolor="#e1e4e8"><font color="#474a4b" face="Arial, Helvetica, sans-serif" style="font-size:16px;">Photos & Videos</font></td>
        </tr>
      </tbody></table>
</td>
</tr>
<tr>
<td height="10">
</td>
</tr>
  <tr>
    <td><table border="0" bgcolor="#ffffff" width="100%" cellspacing="0" cellpadding="0">
        <tbody><tr>
          <td align="center">
	  <?php
	  foreach($photos as $phototraversal){      
		  ?>
			<a href="<?php echo $photoTabUrl."~Photovideos"; ?><!-- #widgettracker --><!-- widgettracker# -->">
			  <img border="0" width="110" vspace="0" align="absmiddle" hspace="0" height="75" style="padding-right:5px; padding-bottom: 5px;"
			  src="<?php echo $phototraversal; ?>"  >
			</a>  
			  <?php  } ?>

			  <?php
			  foreach($videos as $videotraversal){      
				  ?>
			    <a href="<?php echo $photoTabUrl."~Photovideos"; ?><!-- #widgettracker --><!-- widgettracker# -->">
			     <img border="0" width="110" vspace="0" align="absmiddle" hspace="0" height="75" style="padding-right:5px; padding-bottom: 5px;"
			     src="<?php echo $videotraversal; ?>"  >
			</a> 
       				 <?php  } ?>
	    </td>
        </tr>
      </tbody></table></td>
  </tr>
  <tr>
    <td><table border="0" bgcolor="#ffffff" width="100%" cellspacing="0" cellpadding="0">
        <tbody><tr>
          <td height="30" style="font-family:Arial, Helvetica, sans-serif;">
			
			<?php if($totalPhotoCount > 0) { ?>	
				<a style="font-size:13px; text-decoration:none; color:#3465e8;" target="_blank" href="<?php echo $photoTabUrl."~Photovideos"; ?><!-- #widgettracker --><!-- widgettracker# -->">
					View <?php echo $totalPhotoCount; ?> Photo<? if($totalPhotoCount > 1) echo "s"; ?>
				</a>	
			<?php } ?>
	  
			<?php if($totalPhotoCount > 0 && $totalVideoCount > 0) { ?> <font color="#e2e2e2">|</font> <?php } ?>
			
			<?php if($totalVideoCount > 0) { ?>
				<span style="letter-spacing:-1px"></span><font color="#3d3d3d" style="font-size:12px;"></font>
				<a style="font-size:13px; text-decoration:none; color:#3465e8;"  href="<?php echo $photoTabUrl."~Photovideos"; ?><!-- #widgettracker --><!-- widgettracker# -->" >
					View <?php echo $totalVideoCount; ?> Video<? if($totalVideoCount > 1) echo "s"; ?>
				</a>
			<?php } ?>	
			<span style="letter-spacing:-1px">  </span></td>
	  <td width="22"></td>
        </tr>
      </tbody></table>
    </td>
  </tr>
</table>
</td>
   <td></td>
</tr>
<tr>
   <td></td>
   <td bgcolor="#ffffff" height="20"></td>
   <td></td>
</tr>
