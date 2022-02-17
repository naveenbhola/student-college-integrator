<h1 class="color-3 f30 f-nrml ordr-titl"><i class="dhlsprite chck-ico"></i>Your order has been sucessfully placed with DHL</h1>
        <p class="f16 color-3 f-nrml">Your order will be complete when it gets picked up by DHL representative within next 1-3 working days</p>
<div class="top-col">

           <div class="book-a-pickup tab-cell">
             <div class="">
               <div class="i-block v-top1 col-lt">
                 <p class="title-txt f14 color-3">Your DHL Waybill Number</p>
                 <p class="barcode-img"><?php echo $shipmentTrackingData['topSection']['AWBNumber']; ?></p>
               </div>
                <div class="i-block v-top1 col-rt m-top">
                  <p class="color-3 f14 trck-num">Payable amount</p>
                  <p class="color-3 f30 f-nrml trck-num">Rs <?php echo $shipmentTrackingData['topSection']['price']; ?>/-</p>
                  <p class="color-6 f12">Collected at the time of pickup</p>
                </div>
            </div>
            <div class="">
              <div class="i-block v-top1 col-lt">
                 <a class="book-btn way-no gaTrack dlAWB" gaparams="ABROAD_SHIPMENT_CONFIRMATION_PAGE,downloadAWB,<?php echo $validateuser[0]['userid']; ?>" pdfUrl="<?php echo base64_encode(MEDIAHOSTURL.$shipmentTrackingData['topSection']['attachmentPDFUrl']); ?>">Download Waybill No.</a>
              </div>
               <div class="i-block v-top1 col-rt">
                 <p class="color-3 f14 f-bold">Pickup ID:<?php echo $shipmentTrackingData['topSection']['pickUpId']; ?></p>
                 <p class="color-6 f12">use this number for pickup request & scheduling</p>
               </div>
            </div>
               <p class="f14 f-bold color-3">Printed copy of waybill is required at the time of pickup</p>
               <p class="f12 color-6">Use Waybill number for tracking, a copy also sent to: <?php echo $email; ?></p>
           </div>
           <div class="tab-cell">
               <h3 class="color-3 f16 f-bold m-btm">Next Steps</h3>
               <p class="color-6 f14 f-nrml m-btm">DHL Person will contact you for confirmation of your booking and document pickup & timings</p>
               <h3 class="f-bold f14 color-3">Keep the following items ready at the time of pickup:</h3>
               <ul class="dat-li">
                <li>Print a copy of your Waybill Number</li>
                <li>Your documents in open format for DHL person to check</li>
                <li>Payable amount: Rs <?php echo $shipmentTrackingData['topSection']['price']; ?>/- in cash</li>
                <li>A copy of ID proof (Voter Card, Driving license, Passport)</li>
              </ul>
              <p class="color-6 f12 f-nrml">Please ensure smooth pickup, DHL representative is under no obligation to wait and has the right to reject the pickup in case it violates any policies of DHL agreement.</p>
           </div>

         </div>
        <p class="f14 color-6 desc-txt">if you have any questions on your order please contact DHL customer care directly on <span class="f-bold color-3">1800-111-345</span> you will have to quote your Waybill number or Pickup ID, so keep them handy.</p>
