<?php 
if(!empty($contactData['website'])){ ?>
<p class="f13 color-3">
	Official Website : <?php echo $contactData['website'];?>
</p>
<?php } 
if(!empty($contactData['phoneNumber'])){?>
<p class="f13 color-3">
	Phone Number : <a href="tel:<?php echo $contactData['phoneNumber'];?>"><?php echo $contactData['phoneNumber'];?></a>
</p>	
<?php }?>