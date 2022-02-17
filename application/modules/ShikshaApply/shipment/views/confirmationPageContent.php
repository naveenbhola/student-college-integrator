<div class="start-container">
<div class="extrn-wrap">
    <div class="cnr-col">
        
<?php
if(array_key_exists('topSection',$shipmentTrackingData))
{
    $this->load->view('widgets/confirmationMessage');
}
if(count($shipmentTrackingData['tableData']) > 0){ 
    $this->load->view('widgets/shipmentTrackingTable');
}
?>
<div class="book-a-pickup">
             <p class="f14 color-3 f-nrml">You can book another pickup for sending application document to more universities</p>
             <a class="book-btn nrml bookAnotherPickup gaTrack" href="javascript:void(0)" gaparams="ABROAD_SHIPMENT_CONFIRMATION_PAGE,bookAnotherPickup"  trackingKeyId="1133" redirecturl="<?php echo $redirectUrl; ?>">Book another pickup</a>
          </div>
      </div>
   </div>
</div>   