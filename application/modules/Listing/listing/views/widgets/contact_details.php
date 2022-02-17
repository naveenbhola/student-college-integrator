<?php if($details['packType']!='1' && $details['packType']!='2'){?>
<div class="wdh100">
    <div class="nlt_head Fnt14 bld mb10">Contact Details</div>
    <div class="nlt_Info">
        <?php if(!empty($details['contact_name'])){?><p><strong>Name of the Person:</strong> <?php echo $details['contact_name'];?></p><?php }?>
        <?php if((!empty($details['contact_main_phone']))||(!empty($details['contact_cell']))||(!empty($details['contact_alternate_phone']))){
        $contact = array();
        if(!empty($details['contact_main_phone'])){
            $contact[] = $details['contact_main_phone'];
        }
        if(!empty($details['contact_cell'])){
            $contact[] = $details['contact_cell'];
        }
        if(!empty($details['contact_alternate_phone'])){
            $contact[] = $details['contact_alternate_phone'];
        }
        ?>
        <p><strong>Contact No.:</strong> <?php echo implode(", ",$contact);?></p>
        <?php }?>
        <?php if(!empty($details['contact_email'])){?><p><strong>Email:</strong> <?php echo insertWbr($details['contact_email'],30);?></p><?php }?>
        <?php if(!empty($details['contact_website'])){?><p><strong>Website:</strong> <?php echo insertWbr($details['contact_website'],30);?></p><?php }?>
         <?php if(isset($details['locations'])){
            $location = array();
            if(!empty($details['locations']['0']['address1'])){
            $location[] =  trim($details['locations']['0']['address1'],",");}
            if(!empty($details['locations']['0']['address2'])){
            $location[] =  trim($details['locations']['0']['address2'],",");}
            if(!empty($details['locations']['0']['locality'])){
            $location[] =  trim($details['locations']['0']['locality'],",");}
	    if($details['institute_id']!='33211' && $details['institute_id']!='32469' && $details['institute_id']!='32383' && $details['institute_id']!='32645'){
            if(!empty($details['locations']['0']['city_name'])){
            $location[] =  trim($details['locations']['0']['city_name'],",");}
            if(!empty($details['locations']['0']['country_name'])){
            $location[] =  trim($details['locations']['0']['country_name'],",");}
	    if(!empty($details['locations']['0']['pincode'])){
            $pincode =  trim($details['locations']['0']['pincode'],",");
	    $pincode = "-".$pincode;
	    }
	    }
            ?><p><strong>Address:</strong> <?php echo implode(",",$location);echo $pincode;?></p><?php }?>
<!--    <p><strong>Also Present at two other locations:</strong></p>
        <p><strong>Delhi:</strong> <a href="#">South Delhi</a> <span class="sepClr">|</span> <a href="#">Kalkaji</a></p>-->
	<?php if(!empty($details['courseDetails']['0']['contact_name'])||(!empty($details['courseDetails']['0']['contact_email']))||(!empty($details['courseDetails']['0']['contact_cell']))||(!empty($details['courseDetails']['0']['contact_main_phone']))||(!empty($details['courseDetails']['0']['contact_alternate_phone']))){?>
	  <div class="ln">&nbsp;</div>
	    <?php if(!empty($details['courseDetails']['0']['title'])){?>
	    <p><strong>For Course:</strong> <?php echo $details['courseDetails']['0']['title'];?></p>
	    <?php }?>
	    <?php if(!empty($details['courseDetails']['0']['contact_name'])){?>
	    <p><strong>Contact Name:</strong> <?php echo $details['courseDetails']['0']['contact_name'];?></p>
	    <?php }?>
	    <?php if((!empty($details['courseDetails']['0']['contact_main_phone']))||(!empty($details['courseDetails']['0']['contact_cell']))||(!empty($details['courseDetails']['0']['contact_alternate_phone']))){
        $contact = array();
        if(!empty($details['courseDetails']['0']['contact_main_phone'])){
            $contact[] = $details['courseDetails']['0']['contact_main_phone'];
        }
        if(!empty($details['courseDetails']['0']['contact_cell'])){
            $contact[] = $details['courseDetails']['0']['contact_cell'];
        }
        if(!empty($details['courseDetails']['0']['contact_alternate_phone'])){
            $contact[] = $details['courseDetails']['0']['contact_alternate_phone'];
        }
        ?>
        <p><strong>Contact No.:</strong> <?php echo implode(",",$contact);?></p>
	<?php }?>
	<?php if(!empty($details['courseDetails']['0']['contact_email'])){?>
	    <p><strong>Email:</strong> <?php echo $details['courseDetails']['0']['contact_email'];?></p>
	<?php }?>
	<?php }?>
    </div>
</div>
 <div class="lineSpace_20">&nbsp;</div>
 <?php }?>
