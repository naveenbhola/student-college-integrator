<!--multi location layer-->
<div class="layer-common" id="colg-loc-layer" >
   <div class="group-card pop-div">
      <a class="cls-head" id="cl-s" onclick="hideMultilocationLayer();"></a>
      <div class="caraousal-slider loc-layr">

      <?php
         $masterUrl = $obj->getUrl();
         foreach ($seeAllBranchesData['loctionsWithLocality'] as $locObjects) {
            $firstEle = reset($locObjects);
            $cityId   = $firstEle->getCityId();
      ?>
         <div class="loc-sub-layer scrollbar1" id="mll_<?php echo $cityId;?>">
            <div class="scrollbar">
                <div class="track"> 
                  <div class="thumb">
                        <div class="end"></div>
                  </div>
                </div>
            </div>
            <div class="viewport">               
               <div class="overview sub">
                  <p><?php echo count($locObjects);?> Locality(s)<i class="sprite-bg gray-dwn-icon"></i></p>
                  <ul>
                  <?php
                     foreach ($locObjects as $locObj) {
                        $localityId = $locObj->getLocalityId();
                        $cityId     = $locObj->getCityId();

                        $locationUrl = $masterUrl."?city=".$cityId;
                        if($localityId)
                           $locationUrl.="&locality=".$localityId;
                  ?>
                     <li><a href="<?php echo $locationUrl;?>"><?php echo $locObj->getLocalityName();?></a></li>
                  <?php
                     }
                  ?>
                  </ul>
               </div>
            </div>
         </div>
      <?php
         }
      ?>

         <h3 class="head-3"><?php echo htmlentities($heading);?> is available at the following branches</h3>
         <div class="scrollbar1" id="inst-loc-lyr">
         <div class="scrollbar">
                   <div class="track"> 
                     <div class="thumb">
                           <div class="end"></div>
                     </div>
                   </div>
         </div>
         <div class="viewport">               
            <div class="overview" id="overview_outer_link" style="width:100%;">
         <?php
            if($seeAllBranchesData['loctionsWithLocality']){
         ?>
            <ul class="branch-list">
            <?php
               foreach ($seeAllBranchesData['loctionsWithLocality'] as $locObject) {
                  $firstEle = reset($locObject);
                  $cityId   = $firstEle->getCityId();
            ?>
               <li>
                  <strong><?php echo $seeAllBranchesData['cityData'][$cityId]['name'];?></strong>
                  <p><a class="locality-lyr-lnk" onclick="showMultiLocLayer(this);" cityId="<?php echo $cityId;?>"> <?php echo count($locObject);?> Locality(s)
                     <i class="sprite-bg blue-rt-icon"></i>
                     </a>
                  </p>
               </li>
            <?php
               }
            ?>
            </ul>
      <?php
         }
         if($seeAllBranchesData['otherLocations']){
      ?>
            <ul class="branch-list">
               <p><strong>Other Cities</strong></p>
            <?php
               foreach ($seeAllBranchesData['otherLocations'] as $otherLocObj) {

                  $localityId  = $otherLocObj->getLocalityId();
                  $cityId      = $otherLocObj->getCityId();
                  $locationUrl = $masterUrl."?city=".$cityId;
                  if($localityId)
                     $locationUrl.="&locality=".$localityId;
            ?>
               <li><a href="<?php echo $locationUrl;?>"><?php echo $otherLocObj->getCityName();?></a></li>
            <?php
               }
            ?>
            </ul>
      <?php
         }
      ?>
         </div>
         </div>
         </div>
      </div>
   </div>
</div>
<!--multi location layer ends-->
