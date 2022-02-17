<?php
if ($courseId > 0) {
    ?>
    <div id="footerNavBox">

        <div>|&nbsp;<a href="javascript:void(0);" onclick="showCustomerSupport();" id="showCustomerSupport" title="Customer Support">Customer Support</a>&nbsp;|
            <div class="customerSupportMain" id="customerSupportMain" style="display: none;bottom:86px!important">
                <div class="customerSupport">
                    <div class="figure"></div>
                    <div class="details">
                        <p>
                            For online form Assistance<br />
                            <span>Call : 011-4046-9621</span>
                            (between 09:30 AM to 06:30 PM, Monday to Friday)
                        </p>
                    </div>
                </div>
            </div>
            <?php if (isset($instituteInfo[0]['instituteInfo'][0]['institute_name']) && $instituteInfo[0]['instituteInfo'][0]['institute_name'] != '') { ?>
                <div class="footerApproval">
                    <p>This application process allows you to apply directly on <?php echo $instituteInfo[0]['instituteInfo'][0]['institute_name']; ?>'s
                        college website
                    </p>
                </div>

            <?php } ?>
        </div>
    <?php } ?>

    <input id="courseId" type="hidden" value="<?php echo $courseId; ?>"/>
    </div>
