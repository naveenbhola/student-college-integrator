 <?php 

    $cookieName = 'instContResp';
    if($listing_type == 'course')
    {
        $cookieName = 'courContResp';
        $fromwhere = 'coursepage';
    }else{
        $fromwhere = $listing_type;      
    }

if(!empty($contactDetails['address']) || !empty($contactDetails['admission_contact_number']) || !empty($contactDetails['website_url']) || !empty($contactDetails['generic_contact_number'])){ ?>
 <!--contact-->
         <div class="data-card m-5btm">
             <h2 class="color-3 f16 heading-gap font-w6">Contact Details</h2>
             <div class="card-cmn color-w">
                 <ul class="cntct-list">
                    <?php if($showAllBranches){?>
                     <li>
                         <label class="color-6 f-lt i-block l-18 f14">Location</label>
                         <span class="block color-3 f14"> <?php 
                                $locationName = $city_name;
                                if(!empty($locality_name))
                                  $locationName = $locality_name.", ".$locationName;
                                echo $locationName;                     
                            ?>
                        <a class="color-b f12 block v-arr wd-55" on="tap:change-location" role="button" tabindex="0">Change branch<i class="arw-icn"></i></a>
                     </li>
                     <?php } ?>
                     <?php if(!empty($contactDetails['address'])){?>
                     <li>
                         <label class="color-6 f-lt i-block l-18 f14">Address</label>
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
                        <span class="block color-3 f14"><?php echo nl2br(htmlentities($contactDetails['address']));?><br />
                        <?=$locationName;?><br/><?php if(!empty($contactDetails['latitude']) && !empty($contactDetails['latitude'])){?><a href="http://maps.google.com/?q=<?php echo $contactDetails['latitude'].','.$contactDetails['longitude'];?>" class="color-b f12 ga-analytic" data-vars-event-name="CONTACT_VIEWONMAP" target="_blank">View on Map</a><?php } ?></span>
                     </li>
                     <?php } 

                     if(!empty($contactDetails['website_url'])){
                    ?>
                     <li>
                         <label class="color-6 f-lt i-block l-18 f14">Website</label>
                         <a target="_blank" rel="nofollow" href="<?php echo $contactDetails['website_url']?>" class="block color-b f14"><?php echo $contactDetails['website_url']?></a>
                     </li>
                    <?php }?>
                    <?php 
                       $phoneText = false;
                        $emailText = false;
                        $buttonText = 'Show Phone & Email';
                        $gaEventName = 'CONTACT_SHOW_PHONEEMAIL';
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
                            $gaEventName = 'CONTACT_SHOW_EMAIL';
                        }
                        elseif (!$emailText && $phoneText) {
                            $buttonText = 'Show Phone Number';   
                            $gaEventName = 'CONTACT_SHOW_PHONE';
                        }
                 if($phoneText || $emailText)
                    {?>    
                            <section class="" amp-access="NOT validuser AND NOT contact" amp-access-hide>
                                <li id="brochureClick-li">  
                                  <a class="btn btn-primary color-o color-f f14 font-w7 m-15top ga-analytic" data-vars-event-name="<?php echo $gaEventName;?>" href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingId=<?=$listingId;?>&listingType=<?=$listing_type?>&actionType=contact&fromwhere=<?=$fromwhere;?>"><?=$buttonText;?></a>
                                  </li>
                            </section>              
                            <section class="" amp-access="validuser AND NOT contact" amp-access-hide tabindex="0">
                                <li id="brochureClick-li">  
                              <a class="btn btn-primary color-o color-f f14 font-w7 m-15top ga-analytic" data-vars-event-name="<?php echo $gaEventName;?>" href="<?=$listing_seo_url;?>?actionType=contact&course=<?=$listingId;?>">
                                  <?=$buttonText;?>
                              </a>
                              </li>
                            </section>
                            <section amp-access="contact" amp-access-hide>
                            <?php 
                                $distinctNumbers = false;
                                if(!empty($contactDetails['admission_contact_number']) && !empty($contactDetails['generic_contact_number'])) { 
                                    if(strcmp($contactDetails['admission_contact_number'], $contactDetails['generic_contact_number']) !== 0) 
                                    {   
                                        $distinctNumbers = true; 
                                    }
                                }
                                if(!empty($contactDetails['admission_contact_number']) || !empty($contactDetails['generic_contact_number'])) {?> 
                                    <li>
                                        <label class="color-6 f-lt i-block l-18 f14">Phone</label> 
                                        <?php 
                                            $phoneNumber = '';
                                            if(!empty($contactDetails['generic_contact_number'])) { 
                                                    $phoneNumber = $contactDetails['generic_contact_number'];
                                                }
                                            if(!empty($contactDetails['admission_contact_number']) && empty($phoneNumber)) {
                                                    $phoneNumber = $contactDetails['admission_contact_number'];
                                                }
                                                ?>
                                            <span class="block color-3 f14"><?=$phoneNumber;?>
                                                <?php if($distinctNumbers){?> 
                                                    <span class="block color-3 f14">(For general enquiry)</span>
                                                <?php } ?> 
                                            </span> 
                                        <?php if(!empty($contactDetails['admission_contact_number']) && $distinctNumbers) { ?>
                                            <span class="block color-3 f14"> 
                                                <?=$contactDetails['admission_contact_number'];?> 
                                                <span class="block color-3 f14">(For admission related enquiry)</span> 
                                                </span> 
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
                                        <li class="pos-rl">
                                            <label class="color-6 f-lt i-block l-18 f14">Email</label>
                                            <?php 
                                                $email = '';
                                                if(!empty($contactDetails['generic_email'])) {
                                                    $email = $contactDetails['generic_email']; 
                                                }
                                                if(!empty($contactDetails['admission_email']) && empty($email)) {
                                                        $email = $contactDetails['admission_email']; 
                                                    }
                                                ?> 
                                                <span class="block color-3 f14"><?=$email;?>
                                                <?php if($distinctEmails){?>
                                                    <span class="block color-3 f14">(For general enquiry)</span> 
                                                <?php } ?> 
                                                </span>
                                                <?php if(!empty($contactDetails['admission_email']) && $distinctEmails) { ?>
                                                    <span class="block color-3 f14"><?=$contactDetails['admission_email'];?><span class="block color-3 f14">(For admission related enquiry)</span></span> 
                                                    <?php } ?> 
                                                </li> 
                                             <?php } ?>
                                        </section>
                            <?php  } ?>
                 </ul>
             </div>
         </div>
<?php } ?>
