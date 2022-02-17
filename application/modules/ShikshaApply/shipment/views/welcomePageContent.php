<div class="dhl-wrap">
  <div class="dhl-shadow">
    <div class="dhl-head">
        <h1 class="dhl-title">Save on shipment of<br/> application documents</h1>
        <h2 class="off-txt">Get 40% off on Shiksha DHL Express</h2>
        <div class="dhl-btn">
            <a href="javascript:void(0)" class="bookYourPickup" class="gaTrack" gaparams="ABROAD_SHIPMENT_WELCOME_PAGE,bookYourPickupTop" trackingkeyId="<?php echo $trackingKeyIdBookYourPickupTop;?>" redirectUrl="<?php echo $schedulePickupPageUrl?>">Book your pickup</a>
            <?php if($alreadySchedulePickups){?>
            <a href="javascript:void(0)" id="trackOrder" class="gaTrack" gaparams="ABROAD_SHIPMENT_WELCOME_PAGE,trackOrder" redirectUrl="<?php echo $confirmationPageUrl?>">Track your shipments</a>
        <?php }?>
        </div>
    </div>
    <div class="shipmnt-shadow"> </div>
    <div class="dhl-sec">
        <h2>How it works</h2>
        <div class="dhl-blck">
            <div class="dhl-box">
                <img src="/public/images/onln.jpg" width="114" height="114"/>
                <h3 class="form-titl">1. Fill form online</h3>
                <p>Signup and share your pickup<br/>and delivery address details</p>
            </div>
            <div class="dhl-box">
                <img src="/public/images/phn.jpg" width="114" height="114"/>
                <h3 class="form-titl">2. Wait for DHL call</h3>
                <p>DHL will schedule a pickup <br/>within next 1-2 working days</p>
            </div>
            <div class="dhl-box">
                <img src="/public/images/pckt.jpg" width="114" height="114"/>
                <h3 class="form-titl">3. Pickup of packet</h3>
                <p>During pickup be ready with cash, <br/>ID proof, confirmation printout and <br/> actual package in open condition</p>
            </div>
            <div class="dhl-box">
                <img src="/public/images/loc.jpg" width="114" height="114"/>
                <h3 class="form-titl">4. Track your shipment</h3>
                <p>Track your shipment on Shiksha <br/> or DHL website. Delivery within <br/> 2-3 working days</p>
            </div>
        </div>
    </div>


    <div class="dhl-sec dhl-sec2">
        <h2 class="h2">Discounted Pricing</h2>
        <table class="price-tble" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <th width="30%"><h3>Country</h3></th>
                <th width="30%"><h3>Discount</h3></th>
                <th width="40%"><h3>Shiksha discounted price</h3></th>
            </tr>
            <tr>
                <td>USA, Canada</td>
                <td rowspan="2" style="border-bottom: 1px solid #e1e1e1;vertical-align: middle;">Flat<br/>40% Off</td>
                <td><i class="rupe-i">&#x20B9;</i> <?php echo $shipmentPriceComparision['discountedPrice'];?></td>
            </tr>
            <tr>
                <td>UK</td>
                <!--td><i class="rupe-i">&#x20B9;</i> <?php //echo $shipmentPriceComparision['originalPrice'];?></td-->
                <td><i class="rupe-i">&#x20B9;</i> <?php echo $shipmentPriceComparision['discountedPrice'];?></td>
            </tr>
        </table>
        <p class="price-tble">Prices are inclusive of all taxes and applicable for package weighing up to 0.5 kg. <br/> Similar discount also offered for all other major countries and also for large package size.</p>
        <div class="pck-btn">
            <a href="javascript:void(0)" class="bookYourPickup" class="gaTrack" gaparams="ABROAD_SHIPMENT_WELCOME_PAGE,bookYourPickupBottom" trackingkeyId="<?php echo $trackingKeyIdBookYourPickupBottom;?>" redirectUrl="<?php echo $schedulePickupPageUrl?>">Book your pickup</a>
        </div>
    </div>

    <?php
        if(count($studentTestimonialsForShipment) >0){
            $this->load->view('shipment/widgets/studentTestimonials');
        }
    ?>

    <div class="dhl-sec dhl-pd">
        <div class="dhl-blck ml244">
            <h2 class="addtnl-tt">Have additional questions? Check out our <a href="<?php echo SHIKSHA_STUDYABROAD_HOME.'/shiksha-dhl-express-faqs-articlepage-1110'?>" target="_blank">frequently asked questions</a> page</h2>
            <p><span>Disclaimer</span> - Please note that Shiksha.com has negotiated this bulk discount offer directly from DHL India. The information shared by students is sent to DHL system and DHL team will contact you to execute the shipment. In all of this transaction, Shiksha.com is a mere facilitator and is by no means responsible for delivery and shipment of items.
            <p>Offer is only applicable for shipment of documents weighing less than 2 kg.</p>
        </div>

    </div>
</div>
