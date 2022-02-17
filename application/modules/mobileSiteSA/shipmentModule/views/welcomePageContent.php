<div class="dhl-wrap">
     <!--dhl background image-->
    <div class="dhl-bg">
        <div class="text-col">
            <h1>Save on shipment of application documents</h1>
            <p>Get 40% off on Shiksha DHL Express</p>

            <div class="dhl-btns" data-enhance="false">
                <a data-transition="slide" data-rel="dialog" href="#userAlertLayer" class="btn btn-main bookYourPickup"
                    gaparams="ABROAD_SHIPMENT_WELCOME_PAGE,bookYourPickupTop"
                    trackingkeyId="<?php echo $trackingKeyIdBookYourPickupTop;?>"
                    redirectUrl="<?php echo $schedulePickupPageUrl?>">Book your pickup</a>

                <?php if($alreadySchedulePickups){?>
                    <a  class ="btn btn-opt" href="javascript:void(0)" id="trackOrder" gaparams="ABROAD_SHIPMENT_WELCOME_PAGE,trackOrder" redirectUrl="<?php echo $confirmationPageUrl?>">Track your shipments</a>
                <?php }?>

            </div>
        </div>
        <div class="shipmnt-shadow"></div>
    </div>
    <!--dhl section how it works-->
    <div class="dhl-steps">
        <h2 class="dhl-txt">How It works</h2>
        <div class="dhl-process">
            <div class="dhl-timeline">
                <div class="dhl-box">
                    <div class="dhl-icon">
                        <i class="dhl-sprite dhl-fill"></i>
                    </div>
                    <div class="dhl-content">
                        <h3>1. Fill form online</h3>
                        <p>Signup and share your pickup and delivery address details</p>
                    </div>
                </div>
                <div class="dhl-box">
                    <div class="dhl-icon">
                        <i class="dhl-sprite dhl-call"></i>
                    </div>
                    <div class="dhl-content">
                        <h3>2. Wait for DHL call</h3>
                        <p>DHL will schedule a pickup within next 1-2 working days</p>
                    </div>
                </div>
                <div class="dhl-box">
                    <div class="dhl-icon">
                        <i class="dhl-sprite dhl-pck"></i>
                    </div>
                    <div class="dhl-content">
                        <h3>3. Pickup of packet</h3>
                        <p>During pickup be ready with cash, ID proof, confirmation printout and actual package in open condition</p>
                    </div>
                </div>
                <div class="dhl-box">
                    <div class="dhl-icon">
                        <i class="dhl-sprite dhl-track"></i>
                    </div>
                    <div class="dhl-content">
                        <h3>4. Track your shipment</h3>
                        <p>Track your shipment on Shiksha or DHL website. Delivery within 2-3 working days</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!--dhl-price-comparison-->
    <div class="price-col">
        <h2 class="dhl-txt">Discounted Pricing</h2>
        <table class="price-tble" cellspacing="0" cellpadding="0" border="0">
            <tbody>
                <tr>
                    <th width="30%">Country</th>
                    <th width="30%">Discount</th>
                    <th width="40%">Shiksha discounted price</th>
                </tr>
                <tr>
                    <td>USA, Canada</td>
                    <td rowspan="2" style="border-bottom: 1px solid #e1e1e1; vertical-align: middle;">Flat<br/>40% Off</td>
                    <td><i class="rupe-i">₹</i> <?php echo $shipmentPriceComparision['discountedPrice'];?></td>
                </tr>
                <tr>
                    <td>UK</td>
                    <!--td><i class="rupe-i">₹</i> <?php //echo $shipmentPriceComparision['originalPrice'];?></td-->
                    <td><i class="rupe-i">₹</i> <?php echo $shipmentPriceComparision['discountedPrice'];?></td>
                </tr>
            </tbody>
        </table>
        <div class="cntr-col" data-enhance="false">
            <a href="#userAlertLayer" data-transition="slide" data-rel="dialog" class="btn btn-prime bookYourPickup" gaparams="ABROAD_SHIPMENT_WELCOME_PAGE,bookYourPickupBottom" trackingkeyId="<?php echo $trackingKeyIdBookYourPickupBottom;?>" redirectUrl="<?php echo $schedulePickupPageUrl?>">Book your pickup</a>
        </div>
    </div>

    <?php
        if(count($studentTestimonialsForShipment) >0){
            $this->load->view('shipmentModule/widgets/studentTestimonials');
        }
    ?>

    <!--dhl queries-->
    <div class="query-col">
        <div class="query-sec">
            <p class="addtnl-tt">Have additional questions? Check out our <a href="<?php echo SHIKSHA_STUDYABROAD_HOME.'/shiksha-dhl-express-faqs-articlepage-1110'?>" target="_blank">frequently asked questions</a> page</p>
            <p class="dhl-dis-txt"><span>Disclaimer</span> - Please note that Shiksha.com has negotiated this bilk discount offer directly from DHL India. The information shared by students is sent to DHL system and DHL team will contact you to execute the shipment. In all of this transaction, Shiksha.com is a mere facilitator and is by no means responsible for delivery and shipment of items.
            </p>
            <p class="off-txt">Offer is only applicable for shipment of documents weighing less than 2 kg.</p>
        </div>
    </div>
</div>
