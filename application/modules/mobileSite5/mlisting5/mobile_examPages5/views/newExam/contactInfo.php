<?php 
if(!empty($contactData['website'])){ ?>
<p class="f14__clr3">
	Official Website : <?php echo $contactData['website'];?>
</p>
<?php } 
if(!empty($contactData['phoneNumber'])){?>
<p class="f14__clr3">
	Phone Number : <a href="tel:<?php echo $contactData['phoneNumber'];?>" ga-attr="PHONE_CONTACT"><?php echo $contactData['phoneNumber'];?></a>
</p>	
<?php }?>