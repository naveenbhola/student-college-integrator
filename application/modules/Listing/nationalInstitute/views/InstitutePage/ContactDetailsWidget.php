<?php 
if(!empty($contactDetails['address']) || !empty($contactDetails['admission_contact_number']) || !empty($contactDetails['admission_email']) || !empty($contactDetails['website_url']) || !empty($contactDetails['generic_contact_number']) || !empty($contactDetails['generic_email'])){

    $GA_Tap_On_Map = 'VIEW_MAP';
    $GA_Tap_On_Change_Branch = 'CHANGE_BRANCH_BOTTOM';
    $GA_Tap_On_CTA = 'SHOW_PHONE_EMAIL';

    $cookieName = 'instContResp';
    if($listing_type == 'course')
    {
        $cookieName = 'courContResp';
        $sendContactDetailTrackingKeyId = 1113;
    }

    else if($listing_type == 'university')
    {
      $sendContactDetailTrackingKeyId = 1117;
    }else{
      $sendContactDetailTrackingKeyId = 1115;
    }
 ?>
            <div class="new-row">
                <div class="group-card no__pad gap listingTuple" id="contact">
                <h2 class="head-1 gap">Contact Details 
                            <?php 
                                $locationName = $city_name;
                                if(!empty($locality_name))
                                  $locationName = $locality_name.", ".$locationName;
                             ?>
                              <?php if($showAllBranches){?>
                                    <span class="cn-info">Showing info for <strong><?php 
                                    echo $locationName;
                                    ?></strong></span> <a ga-attr="<?=$GA_Tap_On_Change_Branch?>" onclick   ="showMultilocationLayer();" class="link">Change Branch</a>        
                                  <?php } ?>
                            </h2>
                    <div class="tbl">
                        <div class="cell contact" id="contact-widget">
                            
                            <div class="contact">
                                <ul class="contact-ul">
                                    <?php if(!empty($contactDetails['address'])){?>
                                        <li>
                                            <div class="admision-query">
                                                <p class="query-head">Address</p>
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
                                                <p class="c-num"><?=nl2br(htmlentities($contactDetails['address']))?></p>
                                                <p class="c-num loc"><?=$locationName;?></p>
                                            </div>
                                        </li>
                                        <?php } ?>
                                            <?php if(!empty($contactDetails['website_url'])){?>
                                             <li>
                                                <div class="general-query admision-query">
                                                    <p class="query-head">Website</p>
                                                    <p class="c-num"><a href="<?php echo $contactDetails['website_url'];?>" target="_blank" class="link" rel="nofollow"><?=$contactDetails['website_url']?></a></p>
                                                </div>
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
                                            $display = "display:table-cell";
                                            $brochureCookie = $_COOKIE['applied_'.$contact_listing_id];
                                            if(empty($brochureCookie) || $userId == 0)
                                            {
                                            if(!in_array($contact_listing_id, $cookieArray)|| $userId == 0) {
                                                $display = "display:none";
                                                ?>
                                            <li id="brochureClick-li">
                                               <a href="javascript:void(0);" id="showContactDetails" class="button button--orange" cta-type="<?php echo CTA_TYPE_CONTACT_DETAILS;?>" onclick="ajaxDownloadEBrochure(this,<?php echo $contact_listing_id;?>,'<?php echo $listing_type;?>','<?php echo addslashes(htmlentities($instituteName_contact));?>','listingContactDetails','<?php echo $sendContactDetailTrackingKeyId;?>','','','','')" customCallBack="contactDetailCallback" customActionType="listingContactDetail" ga-attr="<?=$GA_Tap_On_CTA;?>"><?=$buttonText;?></a>
                                            </li>
                                            <?php
                                                } }
                                                    $distinctNumbers = false;
                                                    if(!empty($contactDetails['admission_contact_number']) && !empty($contactDetails['generic_contact_number'])) {
                                                        if(strcmp($contactDetails['admission_contact_number'], $contactDetails['generic_contact_number']) !== 0)
                                                        {
                                                            $distinctNumbers = true;
                                                        }
                                                    }
                                              if(!empty($contactDetails['admission_contact_number']) || !empty($contactDetails['generic_contact_number'])) {?>
                                                    <li class="showContact" style="<?=$display;?>">
                                                        <div class="admision-query">
                                                            <p class="query-head">Phone Number </p>
                                                            <?php 
                                                            $phoneNumber = '';
                                                            if(!empty($contactDetails['generic_contact_number'])) { 
                                                                    $phoneNumber = $contactDetails['generic_contact_number'];
                                                                }
                                                            if(!empty($contactDetails['admission_contact_number']) && empty($phoneNumber)) {
                                                                $phoneNumber = $contactDetails['admission_contact_number'];
                                                            }
                                                            ?>
                                                            <p class="c-num ad-gap"><?=$phoneNumber;?>
                                                                <?php if($distinctNumbers){?>
                                                                    <span class="gn-enq">(For general enquiry)</span>
                                                                <?php } ?>
                                                            </p>
                                                            <?php if(!empty($contactDetails['admission_contact_number']) && $distinctNumbers) { ?>
                                                                <p class="c-num"><?=$contactDetails['admission_contact_number'];?><span class="gn-enq">(For admission related enquiry)</span></p>
                                                            <?php } ?>
                                                        </div>
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
                                                        <div class="admision-query">
                                                            <p class="query-head">Email</p>
                                                            <?php 
                                                            $email = '';
                                                            if(!empty($contactDetails['generic_email'])) { 
                                                                    $email = $contactDetails['generic_email'];
                                                                }
                                                            if(!empty($contactDetails['admission_email']) && empty($email)) {
                                                                $email = $contactDetails['admission_email'];
                                                            }
                                                            ?>
                                                            <p class="c-num ad-gap"><?=$email;?>
                                                                <?php if($distinctEmails){?>
                                                                    <span class="gn-enq">(For general enquiry)</span>
                                                                <?php } ?>
                                                            </p>
                                                            <?php if(!empty($contactDetails['admission_email']) && $distinctEmails) { ?>
                                                                <p class="c-num"><?=$contactDetails['admission_email'];?><span class="gn-enq">(For admission related enquiry)</span></p>
                                                            <?php } ?>
                                                        </div>
                                                    </li>
                                  <?php } } ?>
                                </ul>
                            </div>
                        </div>
                        <?php if(!empty($contactDetails['latitude']) && !empty($contactDetails['longitude']) && !empty($contactDetails['google_url'])) {?>
                                <div class="cell right-text google-staticmap">
                                    <a href="http://www.google.com/maps/place/<?php echo $contactDetails['latitude'];?>,<?php echo $contactDetails['longitude'];?>/@<?php echo $contactDetails['latitude'];?>,<?php echo $contactDetails['longitude'];?>,19z" target="_blank" ga-attr="<?=$GA_Tap_On_Map?>">
                                        <img class="lazy" data-original="<?php echo $contactDetails['google_url'];?>">
                                        <p class="mp-vw"><span>View on Map</span></p>
                                    </a>
                                </div>
                        <?php  }              ?>
                    </div>
                </div>
            </div>
    <?php } ?>
