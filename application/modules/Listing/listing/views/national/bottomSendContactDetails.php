<?php
    $listingType = "Bottom_".$pageType;
    $widgetName = 'bottom';
    
    $locations = $institute->getLocations();
    $location = $locations[$currentLocation->getLocationId()];
    $title = "Contact details of <span itemprop=\"name\">".html_escape($institute->getName())."</span>, ";
    if($location->getLocality() && $location->getLocality()->getName())
    {
            $title .= $location->getLocality()->getName().", ";
    }
    $title .= $location->getCity()->getName();
?>  

<div id="responseFormBottomNew" class="responseFormContactNew" style="display: none;"></div>

<div class="other-details-wrap clear-width">
<div id itemscope itemtype="http://schema.org/CollegeOrUniversity"> 
    <h2 class="mb14"><?=$title?></h2>
    <div id="contact-box">
        <div class="confirm-msg clear-width" id="message"  style="display:none">
                <i class="sprite-bg confirm-icon flLt"></i>
            <p>Institute contact details have been sent to you on your mobile & email</p>
        </div>
        <?php if($course)
        {
            $listingType = "Bottom_".$pageType;
            $locations = $course->getLocations();
            $location = $locations[$currentLocation->getLocationId()];
            $contactDetail = $location->getContactDetail();
            if(!($contactDetail->getContactNumbers()) || $pageType == 'institute')
            {
                $locations = $institute->getLocations();
                $location = $locations[$currentLocation->getLocationId()];
                $contactDetail = $location->getContactDetail();
            }
            if($contactDetail)
            { ?>
                <ul class="add-sep clear-width">
                    <?php if($pageType == 'course')
                    { ?>
                        <li>
                            <i class="sprite-bg talk-icon flLt" style="top:4px"></i>
                            <p>
                                <strong class="flLt">For Course: </strong>
                                <span class="add-details"><?=$course->getName()?></span>
                            </p>
                        </li>
                    <?php } ?>
                    
                    <?php if($contactDetail->getContactPerson())
                    { ?>
                        <li>
                            <i class="sprite-bg person-icon flLt"></i>
                            <p>
                                <strong class="flLt">Name of the person:</strong>
                                <span class="flLt add-details" style="width:465px">
                                <?=$contactDetail->getContactPerson()?>
                                </span>
                            </p>
                        </li>    
                    <?php } ?>
                     
                    <?php if($location->getAddress())
		    { ?>   
                        <li>
                            <i class="sprite-bg loc-icon flLt"></i>
                            <p>
                                <strong class="flLt">Address:</strong>
                                <span class="flLt add-details">
                                <?=$location->getAddress()?>
                                </span>
                            </p>
                        </li>
                    <?php } ?>
                    
                    <?php if($contactDetail->getContactEmail())
		    		{ ?>
                        <li>
                            <i class="sprite-bg email-icon flLt"></i>
                            <p>
                                <strong class="flLt">Email: </strong>
                                <span class="flLt add-details" itemprop="email">
                                    <a href="mailto:<?=$contactDetail->getContactEmail()?>"><?=$contactDetail->getContactEmail()?></a>
                                    
                                </span> 
                            </p>
                        </li>
                        <?php }?>
                        <?php if($contactDetail->getContactWebsite())
                                    { ?>
                        <li>
                        	 
                                    <i class="sprite-bg web-icon flLt"></i>
                        	<p>
                                       <strong class="flLt">Website: </strong>
                                       <span class="flLt add-details" itemprop="url">
                                        <?php
                                            $URL = $contactDetail->getContactWebsite();
                                            $hostEntry = parse_url($URL);
                                            if(empty($hostEntry['scheme'])) {
                                                $URL = "http://" . $URL;
                                            }
                                       ?>
                                     <a href="<?=$URL;?>" target="_blank" rel="nofollow"><?=$contactDetail->getContactWebsite()?></a>
                                     </span>
                      	</p>
                        </li>
                     <?php } ?>
                             
                    <?php if($contactDetail->getContactNumbers())
                    {
                        $numbers = explode(",", $contactDetail->getContactNumbers());
                        $number_html_array = "";
                        foreach ($numbers as $number)
                        {
                            $number_html_array[]=  $number_html .'<input type="checkbox" name="contact_numbers_'.$widgetName.'" value="'.html_escape($number).'">'.html_escape($number);
                        } ?>
                        <li id="showContactNumber_course" style="display:none">
                            <form id="<?php echo $widgetName."_second";?>">
                                <div>
                                    <i class="sprite-bg contact-icon flLt"></i>
                                    <p>
                                        <strong class="flLt">Contact No: </strong>
                                        <span class="flLt" uniqueattr="listing_report_contact_button_clicked">
                                            <?=$contactDetail->getContactNumbers()?> <a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/REPORT_INVALID_UPDATED" class="manage-contact-btn orange-clr" style="margin-left:10px" onclick="$j(this).parent().hide(); $j(this).parent().next().show(); return false;">Report Incorrect Number</a>
                                        </span>
                                        <span class="flLt" style="display:none">
                                            <span itemprop="telephone"><?=implode(" ", $number_html_array)?></span>
                                            <br/>
                                            <span id="span_<?php echo $widgetName."_second";?>">
                                                <span>Select the number(s) to report as incorrect</span>
                                                <span>
                                                    <button uniqueattr="LISTING_INSTITUTE_PAGES/SUBMIT_REPORT_INVALID_UPDATED" class="gray-button" style="font-size:11px !important;margin-left:3px;" onclick="reportInvalidNumbers('<?php echo $course->getId()?>','course','<?php echo $widgetName."_second";?>'); return false;">Submit</button>
                                                </span>
                                            </span>
                                        </span>
                                    </p>
                                </div>
                            </form>
                        </li>
                        <li id="showContactFax_course" style="display:none">
                            <?php if($contactDetail->getContactFax())
                            { ?>
                                <i class="sprite-bg fax-icon flLt"></i>
								<p>
                                    <strong>Fax No.:</strong> <?=$contactDetail->getContactFax()?>
                                </p>
                            <?php } ?>
                        </li>
                    </span>
                    <?php } ?> 
                    <li id="Button_2">
                        <a href="#" class="manage-contact-btn orange-clr"  uniqueattr="LISTING_INSTITUTE_PAGES/BOTTOM_SEND_CONTACT_DETAILS_UPDATED" onclick="showResponseForm('responseFormBottomNew', '<?=$listingType?>', '<?=$typeId?>', 'listingPageBottomNew'); activatecustomplaceholder(); return false;">
                            <i class="sprite-bg sms-icon2"></i>Get contact details on email/SMS
                        </a>
                    </li>
                </ul>
            <?php }
        } ?>
    </div>
    </div>
</div>
<script>
    showHideSendContactDetailsButton('<?=$typeId?>', '<?=$pageType?>');
</script>
