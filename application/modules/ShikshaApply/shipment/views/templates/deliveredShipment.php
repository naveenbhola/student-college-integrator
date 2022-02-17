<table width='600' border='0' cellspacing='0' cellpadding='0' align='center' style='border:1px solid #D9D9D9;'> 
        <tr> 
          <td colspan='3' background="<?php echo IMGURL ?>/public/images/bg.gif" style='background:url(<?php echo IMGURL ?>/public/images/bg.gif)'> 
        <div style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A1A1A; padding:10px 35px 10px 35px; line-height:20px;'> 
        <br /> 

      Dear <?php echo $name;?>,<br/><br/> 
      Your request for shipment of application document has been successfully delivered by DHL. <br/>
      Waybill number: <?php echo $AWBNumber;?><br/>
      Last update from DHL on <?php echo $lastDHLUpdate;?>: Shipment successfully delivered<br/></br>

      We thank you for using this service and request you to spend 1 minute in giving us feedback for our service. It will help us improve for future students:<br>
      <a href="https://docs.google.com/forms/d/e/1FAIpQLSe7-u8d4viZctT0C6zgAmJankcozyZeD5-yVV9MYrkme8l5TA/viewform" target="_blank"><?php echo SHIPMENT_DHL_FEEDBACK; ?></a></br></br>

      Regards<br/>
      Shiksha Study Abroad<br/></br>

      You can write to us at <a href="mailto:<?php echo SA_ADMIN_EMAIL ?>"><?php echo SA_ADMIN_EMAIL ?></a> or call us on our study abroad student helpline at 011-4046-9621(09:30 AM to 06:30 PM, Monday to Friday).<br/> 
      </div></td> 
        </tr>
    <?php $this->load->view('studyAbroadCommon/mailerFooter',array('whiteBanner' => true)); ?>
      </table> 