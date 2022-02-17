<script>hide_bottom_date_add_remove_link=false;</script>
<?php
 $showHighlighted = false;
 if (isset($resultSet))
    $prev_paymentId = null;

             foreach ($resultSet as $res){
                if ($prev_paymentId != null && $prev_paymentId != $res['paymentId']) {?>
                <td valign="bottom">
                    <button onclick="javascript:savePaymentStatus(<?php echo $prev_paymentId ?>);return false;" name="save_form" id="<?php echo $prev_paymentId; ?>" class="orangeButton">Save</button>
                </td>
               <?php
               $showHighlighted = ($showHighlighted)?false:true;
               }else{ ?>
                <td valign="bottom">
                </td>
               <?php } ?>
         <?php if($res['paymentId']!=null){ ?>
            <tr class="<?php if($showHighlighted) echo 'alt-row';?>">
                 <td><?php echo $res['paymentId'] ;?></td>
                 <td><?php echo $res['orderId']; ?></td>
		 <td><?php echo $res['date']; ?></td>
                 <td>
			<?php
				$receiptDate = date('d/m/Y',strtotime($res['receiptDate']));
			?>
                       <span class="calenderBox">
                               <input type="text" name="<?=$res['paymentId']."--".$res['date']?>" value="<?php echo $receiptDate; ?>" class="calenderFields"  id="<?=$res['paymentId']."--".$res['date']?>"  readonly onchange="setStatusOfOrder(this, '<?php echo $res['orderId'];?>', '<?php echo $res['paymentId'];?>','<?php echo $res['date'];?>','<?php echo $receiptDate;?>');" />
                               <a href="#" class="pickDate" title="Calendar" id="<?=$res['paymentId']."--".$res['date']?>_img"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('<?=$res['paymentId']."--".$res['date']?>'),'<?=$res['paymentId']."--".$res['date']?>_img','dd/mm/yyyy'); return false;">&nbsp;</a>
                       </span>

		 </td>

                 <td> <select name="<?php echo $res['paymentId']; ?>" id="<?php echo $res['paymentId']."--".$res['date']."_status" ?>" style="width:200px" onchange="setStatusOfOrder(this, '<?php echo $res['orderId'];?>', '<?php echo $res['paymentId'];?>','<?php echo $res['date'];?>','<?php echo $receiptDate;?>');">
                                  <option value="null" disabled="true">Select</option>
                                  <option value="Started" <? if(($res['status']=='Started')) echo 'selected="selected"' ?> disabled="true">Started</option>
                                  <option value="Success" <? if(($res['status']=='Success')) echo 'selected="selected"'; ?> <?php if ($res['disabled']) { echo 'disabled="true"'; }?>>Success</option> 
                                  <option value="Failed" <? if(($res['status']=='Failed')) echo 'selected="selected"' ?>>Failed</option>
                                  <option value="Cancelled" <? if(($res['status']=='Cancelled')) echo 'selected="selected"' ?>>Cancelled</option>
				  <option value="Refunded" <? if(($res['status']=='Refunded')) echo 'selected="selected"' ?>>Refunded</option>
				  <option value="Chargeback" <? if(($res['status']=='Chargeback')) echo 'selected="selected"' ?>>Chargeback</option>
                      </select>
                   <?php     $prev_paymentId = $res['paymentId']; ?>
                   <td valign="bottom">&nbsp;</td>
                 </td>
            
           <?php }?>
          <?php } ?>
           </tr> 
           
          
          
