<!--amp view aminities lighthbox-->
<?php 
foreach ($facilityInfo as $facilityKey => $facilityValue) {
?>
<amp-lightbox class="" id="view-am<?=$facilityValue['facility_id']?>" layout="nodisplay" scrollable>
 <div class="lightbox">
   <a class="cls-lightbox f25 color-f font-w6" on="tap:view-am<?=$facilityValue['facility_id']?>.close" role="button" tabindex="0">&times;</a>
   <div class="m-layer">
      <div class="min-div color-w pad10">
         <strong class="block color-3 f14 font-w6"><?=$facilityKey?></strong>
         <?php 
         if(!empty($facilityValue['description']))
         {
         ?>
          <p class="color-3 l-18 f12 title-cn"><?php echo nl2br(makeURLAsHyperlink(htmlentities($facilityValue['description']),true));?></p>
         <?php 
         }if(!empty($facilityValue['childFacility']['Mandatory Hostel']['has_facility'])){?>
          <p class="head-8 note">note : Staying in hostel is mandatory</p>
         <?php 
         }if(!empty($facilityValue['childFacility']) && count($facilityValue['childFacility']) > 0)
                        { ?>
                            <?php if($facilityValue['facility_name'] != 'Hostel' ) {?>
                            <span class="ament-info f12 block color-6" id="avail-fac_<?php echo $facilityValue['facility_id'];?>">Available Facilities</span>
                            <?php } ?>
                                <ul class="flex-ul childFac-ul_<?php echo $facilityValue['facility_id'];?>">
                                        <?php 
                                    foreach ($facilityValue['childFacility'] as $childKey => $childValue) {
                                        if($childKey != 'Mandatory Hostel' && (!empty($childValue['has_facility']) || in_array($facilityKey, array('Sports Complex','Labs')))) {
                                        ?>
                                    <li>
                                       <div class="mlst">
                                        <strong class="amen-txt"><?php echo nl2br(makeURLAsHyperlink(htmlentities($childValue['facility_name']),true));?></strong>
                                        
                                        <?php foreach ($childValue['additionalInfo'] as $addkey => $addValue) { 
                                                if($addkey == 0)
                                                { 
                                                    $lastElement = count($childValue['additionalInfo']);?>
                                                    <ul>

                                               <?php  }
                                            
                                                $dispayValue = '';
                                                if(!empty($addValue['name']))
                                                {
                                                    $dispayValue .= $addValue['name'];
                                                }
                                                if(in_array($addValue['name'], array('Number of Rooms','Number of beds')))
                                                {
                                                    if(!empty($dispayValue))
                                                    {
                                                        $dispayValue .= ' - ';
                                                    }

                                                    if(!empty($addValue['value']))
                                                        $dispayValue .= $addValue['value'];

                                                

                                                }
                                                ?>
                                                        <li class="child-ul"><?php echo !empty($dispayValue)?nl2br(makeURLAsHyperlink(htmlentities($dispayValue),true)):'';?></li>
                                                        <?php 
                                                         if($addkey == $lastElement)
                                                            { ?>
                                                                </ul>
                                                           <?php  } ?>
                                                <?php }?>

                                               </div>
                                              </li>     
                                           
                                        <?php } }
                                        ?>
                                </ul>
                                                
                        <?php } ?>
      </div>
   </div>
 </div>
</amp-lightbox>
<?php 
}
?>
