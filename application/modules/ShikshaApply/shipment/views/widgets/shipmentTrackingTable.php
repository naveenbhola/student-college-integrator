<h2 class="f14 f-bold color-3 m-btm">Tracking status of your application documents</h2>
  <table class="inner-table">
    <thead class="thead-dflt">
      <tr>
        <th>S.No</th>
        <th>Waybill Number</th>
        <th>Status of your application document shipment</th>
        <th>Last updated</th>
      </tr>
    </thead>
    <tbody class="tbody-dflt">
      <?php if(count($shipmentTrackingData['tableData']) == 0){ ?>
      <tr>
        <td colspan=4>
          <div class="f12 f-nrml">Unable to fetch status records</div>
        </td>
      </tr>
      <?php } ?>
      <?php foreach($shipmentTrackingData['tableData'] as $k=>$shipment){
        $eventCodesAvailable = $lastUpdateAvailable = false;
        $trackingStatus = $shipmentTrackingData['trackingStatus'][$shipment['AWBNumber']];
        // date time availability
        if($trackingStatus['lastDHLUpdate']['Date'] != "")
        {
          $trackTime = strtotime($trackingStatus['lastDHLUpdate']['Date']." ".$trackingStatus['lastDHLUpdate']['Time']);
        }
        else{
          $trackTime = strtotime($shipment['lastModified']);
        }
        
        if(array_key_exists('eventCodes',$trackingStatus))
        {
          $eventCodesAvailable = true;
        }
        if(array_key_exists('lastDHLUpdate',$trackingStatus))
        {
          $lastUpdateAvailable = true;
        }
        ?> 
      <tr>
        <td>
          <div class="f12 color-3 f-nrml"><?php echo $k+1; ?></div>
        </td>
        <td>
          <div class="m-btm">
            <p class="f12 f-nrml color-3">Waybill Number:</p>
            <p class="f12 f-bold color-3"><?php echo $shipment['AWBNumber']; ?></p>
          </div>
          <div class="">
            <p class="f12 f-nrml color-3">Pickup ID:</p>
            <p class="f12 f-bold color-3 m-btm"><?php echo $shipment['pickUpId']; ?></p>
            <p class="f12 f-nrml color-3"><?php echo $shipment['destinationUniversityName']; ?></p>
          </div>
        </td>
        <td>
          <div class="ps-bar">
            <ul class="progres-bars">
              <li class="<?php echo ($eventCodesAvailable && in_array(PICKUP_BOOKED,$trackingStatus['eventCodes'])?'active-step':''); ?>">
                <span class="step-name">  Pickup booked  </span>
                <span class="step-num"></span>
              </li>
              <li class="<?php echo ($eventCodesAvailable && in_array(PICKUP_SUCCESS,$trackingStatus['eventCodes'])?'active-step':''); ?>">
                <span class="step-name">Pickup Sucessful</span>
                <span class="step-num"></span>
              </li>
              <li class="<?php echo ($eventCodesAvailable && in_array(IN_TRANSIT,$trackingStatus['eventCodes'])?'active-step':''); ?>">
                <span class="step-name">In Transit</span>
                <span class="step-num"></span>
              </li>
              <li class="<?php echo ($eventCodesAvailable && in_array(DELIVERED,$trackingStatus['eventCodes'])?'active-step':''); ?>">
                <span class="step-name">Delivered</span>
                <span class="step-num"></span>
              </li>
            </ul>
            <p class="f12 color-3 update-txt">Last update from DHL as on <?php echo date("j M Y", $trackTime); ?> at <?php echo date("g:i a", $trackTime); ?> IST
            <?php
              if($lastUpdateAvailable && $trackingStatus['lastDHLUpdate']['Description']!=""){
                echo $trackingStatus['lastDHLUpdate']['Description'].($trackingStatus['lastDHLUpdate']['Signatory'] == "" ? "" :" ".$trackingStatus['lastDHLUpdate']['Signatory'])." ".$trackingStatus['lastDHLUpdate']['Location'];
              }
            ?>
            </p>
          </div>
        </td>
        <td>
          <div>
            <p class="f12 color-3 f-nrml"><?php echo date("d-m-y", $trackTime); ?></p>
            <p class="f12 color-3 f-nrml"><?php echo date("g:i a", $trackTime); ?> IST</p>
          </div>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>