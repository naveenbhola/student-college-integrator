<?php if(!empty($currentLocationObj)){ ?>
    <div class="crs-widget">
        <h2 class="head-L2">Contact details</h2>
        <div class="lcard">
            <ul class="cntct-list">
                <li>
                    <label>Address</label>
                    <span>
                        <?php echo htmlentities($currentLocationObj->getContactDetail()->getAddress()); ?>
                        <br />
                        <a href="#" class="link-blue-small mp-view">View on map</a>
                    </span>
                </li>
                <li>
                    <label>Main Branch</label>
                    <span><?php echo $currentLocationObj->getStateName();?> <a href="#" class="link-blue-small" ga-attr="VIEWALLBRANCHES_BOTTOM_COURSEDETAIL_MOBILE">Change Branch</a></span>
                </li>
                <li>
                    <label>Website</label>
                    <span><?php echo htmlentities($currentLocationObj->getContactDetail()->getWebsiteUrl()); ?></span>
                </li>
                <li>
                    <label>Contact</label>
                    <span class="cal-cont">
			<?php $number = $currentLocationObj->getContactDetail()->getGenericContactNumber(); ?>
                        <?php if(!empty($number)){ ?>
                            <span>+91 </span><?php echo $currentLocationObj->getContactDetail()->getGenericContactNumber();?><br>
                        <?php } ?>
			<?php $number = $currentLocationObj->getContactDetail()->getAdmissionContactNumber(); ?>
                        <?php if(!empty($number)){ ?>
                            <span>+91 </span><?php echo $currentLocationObj->getContactDetail()->getAdmissionContactNumber();?><br>
                        <?php } ?>
                    </span>
                    <span class="cal-cont fltryt">
                        <a href="tel:9599928475"><i class="clg-sprite clg-contct"></i></a>
                    </span>
                </li>
            </ul>
            <div>
                <a class="btn-mob">Call</a>
                <a class="btn-mob">Send enquiry to college</a>
            </div>
        </div>
    </div>
<?php } ?>
