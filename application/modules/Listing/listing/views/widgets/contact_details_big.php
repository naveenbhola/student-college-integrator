<div class="wdh100 mt10">
    <div class="raised_greenGradient_ww">
        <b class="b1"></b><b class="b2"></b><b class="b3" style="background:#f6f4f5"></b><b class="b4" style="background:#f6f4f5"></b>
        <div class="boxcontent_greenGradient_ww" style="background:#f6f4f5">
            <div class="mlr10">
                <h2><div class="Fnt14 bld">Contact Details of <?php echo $details['title'];?></div></h2>
                <div class="ln_1">&nbsp;</div>
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
        <p><strong>Contact No.: </strong><a href="#" onclick="showPhoneNumber(this,'contactInstituteBottom'); return false;"><img align="absmiddle" src="/public/images/click-view-btn.gif" alt="Click to View"/></a><span id="contactInstituteBottom" style="display:none"><?php echo implode(",",$contact);?></span></p>
        <?php }?>
        <?php if(!empty($details['contact_email'])){?><p><strong>Email:</strong> <?php echo insertWbr($details['contact_email'],115);?></p><?php }?>
        <?php if(!empty($details['contact_website'])){?><p><strong>Website:</strong> <?php echo insertWbr($details['contact_website'],115);?></p><?php }?>
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
<!--        <p><strong>Also Present at two other locations:</strong></p>
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
        <p><strong>Contact No.: </strong><a href="#" onclick="showPhoneNumber(this,'contactCourseBottom'); return false;"><img align="absmiddle" src="/public/images/click-view-btn.gif" alt="Click to View"/></a><span id="contactCourseBottom" style="display:none"><?php echo implode(",",$contact);?></span></p>
	<?php }?>
	<?php if(!empty($details['courseDetails']['0']['contact_email'])){?>
	    <p><strong>Email:</strong> <?php echo $details['courseDetails']['0']['contact_email'];?></p>
	<?php }?>
	<?php }?>
    </div>
            </div>
        </div>
        <b class="b4b" style="background:#f6f4f5"></b><b class="b3b" style="background:#f6f4f5"></b><b class="b2b" style="background:#f6f4f5"></b><b class="b1b"></b>
    </div>
</div>
