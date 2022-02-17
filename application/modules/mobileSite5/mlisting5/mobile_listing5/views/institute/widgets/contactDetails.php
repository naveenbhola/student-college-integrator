
<?php

    $cookieName = 'instContResp';
    if($listing_type == 'course')
    {
        $cookieName = 'courContResp';
    }

    $GA_Tap_On_Map = 'VIEW_MAP';
    $GA_Tap_On_Change_Branch = 'CHANGE_BRANCH_BOTTOM';
    $GA_Tap_On_Phone = 'SHOW_PHONE_EMAIL';

    if($listing_type == 'course'){
        $pageType = 'courseDetailPage';
        $contactTrackingPageKeyId = 1114;
    }
    else if($listing_type == 'university')
    {
        $pageType = 'universityDetailPage';
        $contactTrackingPageKeyId = 1118;
    }
    else
    {
        $pageType = 'instituteDetailPage';
        $contactTrackingPageKeyId = 1116;
    }
 if(!empty($contactDetails['address']) || !empty($contactDetails['admission_contact_number']) || !empty($contactDetails['website_url']) || !empty($contactDetails['generic_contact_number'])){ ?>
<div class="crs-widget listingTuple" id="contact">
        <h2 class="head-L2">Contact Details</h2>
        <div class="lcard">
            <ul class="cntct-list">
                <?php if($showAllBranches){?>
                <li>
                    <label>Location</label>
                    <span><?php 
                        $locationName = $city_name;
                        if(!empty($locality_name))
                          $locationName = $locality_name.", ".$locationName;                    
                    ?>
                    <p><?=$locationName;?></p>
                            <a href="javascript:void(0);" class="link-blue-small chn" ga-attr="<?=$GA_Tap_On_Change_Branch;?>" onclick="showLocationLayer();">Change branch<i class="arw-icn"></i></a>
                </span>
                </li>
                <?php } ?>
                <?php if(!empty($contactDetails['address'])){?>
                <li>
                    <label>Address</label>
                    <?php 
                        $locationName = $contactDetails['city_name'];
                        if(!empty($contactDetails['locality_name']))
                            $locationName = $contactDetails['locality_name'].", ".$locationName;
                        $compare = strcmp($contactDetails['city_name'], $contactDetails['state_name']);
                        if(strpos($contactDetails['address'], $contactDetails['locality_name']) !== FALSE)
                        {
                            $locationName = $contactDetails['city_name'];
                            if(!empty($compare))
                            {
                                $locationName = $locationName .' ( '.$contactDetails['state_name'].')';
                            }
                        }   
                        elseif(!empty($contactDetails['state_name']) && !empty($compare))
                        {
                            $locationName = $locationName .' ( '.$contactDetails['state_name'].')';
                        }
                      ?>
                    <span><?php echo nl2br(htmlentities($contactDetails['address']));?><br />
                    <?=$locationName;?><br/><?php if(!empty($contactDetails['latitude']) && !empty($contactDetails['latitude'])){?><a href="http://maps.google.com/?q=<?php echo $contactDetails['latitude'].','.$contactDetails['longitude'];?>" class="link-blue-small mp-view" ga-attr="<?=$GA_Tap_On_Map;?>">View on Map</a><?php } ?></span>

                </li>
                <?php } ?>
                
                <?php if(!empty($contactDetails['website_url'])){?>
                <li>
                    <label>Website</label>
                    <a href="<?php echo $contactDetails['website_url']?>" class= "link-blue-small" rel="nofollow"><?php echo $contactDetails['website_url']?></a>
                </li>
                <?php } ?>
                <?php 
                       $phoneText = false;
                        $emailText = false;
                        $buttonText = 'Show Phone & Email';
                        if(!empty($contactDetails['admission_contact_number']) || !empty($contactDetails['generic_contact_number']))
                        {   
                            $phoneText = true;
                        }
                        if(!empty($contactDetails['admission_email']) || !empty($contactDetails['generic_email']))
                        {
                            $emailText = true;
                        }
                        if($emailText && !$phoneText)   
                        {
                            $buttonText = 'Show Email';
                        }
                        elseif (!$emailText && $phoneText) {
                            $buttonText = 'Show Phone Number';   
                        }
                 if($phoneText || $emailText)
                    {
                        $cookieArray = $_COOKIE[$cookieName];
                        $cookieArray = explode(',', $cookieArray);
                        $display = "display:block";
                        $brochureCookie = $_COOKIE['applied_courses'];
                        $brochureCookie = json_decode(base64_decode($brochureCookie));
                        if(!in_array($contact_listing_id, $brochureCookie) || $userId == 0) {
                        if(!in_array($contact_listing_id, $cookieArray) || $userId == 0) {
                            $display = "display:none";
                            ?>
                        <li id="brochureClick-li">
                           <a href="javascript:void(0);" id="showContactDetails" class="btn-mob" cta-type="<?php echo CTA_TYPE_CONTACT_DETAILS;?>" onclick="sendContactInfo('<?php echo $contact_listing_id?>','<?php echo $contactTrackingPageKeyId;?>','<?php echo $listing_type;?>', {'pageType':'<?php echo $pageType;?>','listing_type':'<?php echo $listing_type;?>','callbackFunctionParams':{'pageType':'<?php echo $pageType;?>',thisObj:this}});" ga-attr="<?=$GA_Tap_On_Phone;?>"><?=$buttonText;?></a>
                        </li>
                <?php  } }
                            $distinctNumbers = false;
                            if(!empty($contactDetails['admission_contact_number']) && !empty($contactDetails['generic_contact_number'])) {
                                if(strcmp($contactDetails['admission_contact_number'], $contactDetails['generic_contact_number']) !== 0)
                                {
                                    $distinctNumbers = true;
                                }
                            }
                      if(!empty($contactDetails['admission_contact_number']) || !empty($contactDetails['generic_contact_number'])) {?>
                            <li class="showContact" style="<?=$display;?>">
                                <label>Phone</label>
                                    <?php 
                                    $phoneNumber = '';
                                    if(!empty($contactDetails['generic_contact_number'])) { 
                                            $phoneNumber = $contactDetails['generic_contact_number'];
                                        }
                                    if(!empty($contactDetails['admission_contact_number']) && empty($phoneNumber)) {
                                        $phoneNumber = $contactDetails['admission_contact_number'];
                                    }
                                    ?>
                                    <span class="cal-cont"><a href="tel:<?=$phoneNumber;?>"><?=$phoneNumber;?></a>
                                        <?php if($distinctNumbers){?>
                                            <span class="gn-enq">(For general enquiry)</span>
                                        <?php } ?>
                                    </span>
                                    <?php if(!empty($contactDetails['admission_contact_number']) && $distinctNumbers) { ?>
                                        <span class="cal-cont"><a href="tel:<?=$contactDetails['admission_contact_number'];?>"><?=$contactDetails['admission_contact_number'];?></a><span class="gn-enq">(For admission related enquiry)</span></span>
                                    <?php } ?>
                            </li>
                      <?php } ?>
                        
                         <?php 

                         $distinctEmails = false;
                                if(!empty($contactDetails['admission_email']) && !empty($contactDetails['generic_email'])) {
                                    if(strcmp($contactDetails['admission_email'], $contactDetails['generic_email']) !== 0)
                                    {
                                        $distinctEmails = true;
                                    }
                                }

                      if(!empty($contactDetails['admission_email']) || !empty($contactDetails['generic_email'])) {?>
                            <li class="showContact" style="<?=$display;?>">
                                <label>Email</label>
                                    <?php 
                                    $email = '';
                                    if(!empty($contactDetails['generic_email'])) { 
                                            $email = $contactDetails['generic_email'];
                                        }
                                    if(!empty($contactDetails['admission_email']) && empty($email)) {
                                        $email = $contactDetails['admission_email'];
                                    }
                                    ?>
                                    <span class="adm-enq"><?=$email;?>
                                        <?php if($distinctEmails){?>
                                            <span class="gn-enq">(For general enquiry)</span>
                                        <?php } ?>
                                    </span>
                                    <?php if(!empty($contactDetails['admission_email']) && $distinctEmails) { ?>
                                        <span class="adm-enq"><?=$contactDetails['admission_email'];?><span class="gn-enq">(For admission related enquiry)</span></span>
                                    <?php } ?>
                                </div>
                            </li>
                <?php } } ?>
            </ul>
        </div>
</div>
<?php } ?>
