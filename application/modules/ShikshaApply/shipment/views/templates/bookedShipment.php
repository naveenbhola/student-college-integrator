<table width='600' border='0' cellspacing='0' cellpadding='0' align='center' style='border:1px solid #D9D9D9;'> 
        <tr> 
          <td colspan='3' background="<?php echo IMGURL ?>/public/images/bg.gif" style='background:url(<?php echo IMGURL ?>/public/images/bg.gif)'> 
        <div style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A1A1A; padding:10px 35px 10px 35px; line-height:20px;'> 
        <br /> 

      Dear <?php echo $name;?>,<br/><br/> 
      Your request for shipment of application document has been successfully processed.<br/><br/>

	  Please note the following important information:</br>
      Waybill number: <?php echo $AWBNumber;?></br>
      Pickup ID: <?php echo $pickUpId;?></br>
      Pickup time: <?php echo $pickUpDate;?> <?php echo $pickUpTime;?></br></br>

      A DHL representative will contact you for scheduling your pickup.
      <ul>
      	<li>Print out of waybill number (refer attachment in this email)</li>
		<li>A copy of photo ID proof (voter card, adhaar card, driving license or passport)</li>
		<li>Your documents in an open format for inspection by DHL representative</li>
		<li>Cash amount of Rs <?php echo $price;?>/- for payment towards courier</li>
	  </ul>

	  Please ensure smooth pickup. DHL representative is under no obligation to wait and has the right to reject the pickup in case it violates any policies of DHL agreement.<br/></br>
	  
	  If you have any questions on your order, you can read more on our <a href="<?php echo SHIKSHA_STUDYABROAD_HOME ?>/shiksha-dhl-express-faqs-articlepage-1110" target="_blank">Frequently Asked Questions</a> page or contact the DHL customer care directly on 1800 111 345. You will have to quote your waybill number or pickup id, so keep them handy.<br/></br>

      Regards<br/>
      Shiksha Study Abroad<br/></br>

      You can write to us at <a href="mailto:<?php echo SA_ADMIN_EMAIL ?>"><?php echo SA_ADMIN_EMAIL ?></a> or call us on our study abroad student helpline at 011-4046-9621(09:30 AM to 06:30 PM, Monday to Friday).<br/> 
      </div></td> 
        </tr>
    <?php $this->load->view('studyAbroadCommon/mailerFooter',array('whiteBanner' => true)); ?>
      </table> 