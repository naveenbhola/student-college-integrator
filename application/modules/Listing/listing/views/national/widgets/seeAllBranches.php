<?php

   if($overlay == "link"){
   	
	$otherLocationExist = 1;
        if(count($otherLocations) == 0)
        {
         $otherLocationExist = 0;

        }

   	$flagOpenLayer = 0;
   	if(count($loctionsWithLocality) <=4 && count($otherLocations) == 0 )
   	{
   		$flagOpenLayer = 1;
   	}
?>
<p class="see-all-link">
   <span> | </span>
      <a href="javascript:void(0);" class="font-12" onclick="showAllBranches<?php echo $listing->getId()?>(<?php echo $listing->getId()?>,<?php echo $flagOpenLayer ; ?>,<?php echo $otherLocationExist; ?>); return false;">See All Branches<i class="sprite-bg branch-icon"></i></a></p>
<div class="other-details-wrap clear-width" id="allLocations<?=$listing->getId()?>" style="display:none;">
    <div class = "multi-loc-layer" >
    <div class="layer-head" style="margin-top: 6px">
                <a href="Javascript:void(0);" class="flRt sprite-bg cross-icon" 
                onclick="hideListingsOverlay(true); return false;" title="Close"></a>
                <div class="layer-title-txt"><?=$listing->getName()?> is available at the following branches</div>
    </div>
    <div class="scrollbar1" id="seeallbranches_layer_link">
        <div class="scrollbar" id="outer_scrollbar_link">
            <div class="track">
                <div class="thumb">
                    <div class="end"></div>
                </div>
            </div>
       </div>
        <div class="viewport"  id="viewport_outer_link" >
        <div class="overview" id ="overview_outer_link" >
    	
<?php   } else {
       if($overlay =="inline")
       {
    
?>
            <div class="other-details-wrap clear-width">
            <h2 class="mb14"><?=html_escape($listing->getName())?> is available at the following branches</h2>
    <div class="multi-loc-layer" id='multi-loc-layer-inline'>
        <div class="scrollbar1" id="seeallbranches_layer_inline">
            <div class="scrollbar" id="outer_scrollbar_inline">
            <div class="track">
                <div class="thumb">
                    <div class="end"></div>
                </div>
            </div>
       </div>
        <div class="viewport" id="viewport_outer_inline">
            <div class="overview" id="overview_outer_inline">
<?php
  }
  
   }
?>    
            <ul class="branch-list">                            
                <?php
                 $index = 0;
                 $otherLocationExists = 1;
                 if(count($otherLocations) == 0)
                 {
                 	$otherLocationExists = 0 ;
                 }
                 
                  if(count($loctionsWithLocality) > 0){
                        foreach($loctionsWithLocality as $cityGroup){
                           
                ?>
                <li>
                    <strong><?=$cityGroup[0]->getCity()->getName()?></strong>
                   <?php
        if($overlay == "link"){
       
       ?>          <?php if($flagOpenLayer) { ?>
                    <p><a href="javascript:void(0);" id = "anchor_id<?php echo $cityGroup[0]->getCity()->getId()?>"> <?=count($cityGroup)?> Locality(s)
                    <i class="sprite-bg blue-rt-icon"></i>
                    </a>
                   </p>
                   
                   
                   
                   <!-- code moved to prevent event bubbling :start -->
 

        <div class="locality-layer layer_link_scroll scrollbar1" id="multilocation_layer_link<?php echo $cityGroup[0]->getCity()->getId()?>">
        <div class="scrollbar"  id="scrollbar_link<?php echo $cityGroup[0]->getCity()->getId()?>">
         <div class="track"> 
                <div class="thumb">
            <div class="end"></div>
        </div>
   		</div>
		</div>
		<div class="viewport" id="viewport_link<?php echo $cityGroup[0]->getCity()->getId()?>" style="height: 120px">
    	<div class ="overview" id="overview_link<?php echo $cityGroup[0]->getCity()->getId()?>">
            
	 	 <p><?php echo count($cityGroup); ?> Locality(s)<i class="sprite-bg gray-dwn-icon"></i></p>
       
  		<ul>
        <?php
        
				foreach($cityGroup as $key=>$location){
					$additionalURLParams = "?city=".$location->getCity()->getId()."&locality=".$location->getLocality()->getId();
					$listing->setAdditionalURLParams($additionalURLParams);
			
			foreach($instituteCourses as $instituteCourse)
			{	//check if the institutes' courses are valid coursepages to show coursepage header.
				if(count($COURSE_PAGES_SUB_CAT_ARRAY[$instituteCourse])>0 )
				{
					if($_REQUEST['cpgs'])
					 $cpgs_append = '&cpgs='.$_REQUEST['cpgs'];
					else if($googleRemarketingParams['subcategoryId'][0]==$instituteCourse)
					 $cpgs_append="&cpgs=".$googleRemarketingParams['subcategoryId'][0];

				}
			}
			echo '<li><a href="'.$listing->getURL().$cpgs_append .'">'.$location->getLocality()->getName().'</a></li>';
					
				}
			?>
        </ul>
  
		</div>
		</div>
        </div>
                   <?php } else { ?> 
                      <?php $index++; ?>
                     <p><a href="javascript:void(0);" id = "anchor_id<?php echo $cityGroup[0]->getCity()->getId()?>" onclick="showMultiLayerContent('<?php echo $otherLocationExists; ?>','<?php echo $index; ?>','link','anchor_id<?php echo $cityGroup[0]->getCity()->getId()?>','multilocation_layer_link<?php echo $cityGroup[0]->getCity()->getId()?>',<?=count($cityGroup)?>,<?php echo $cityGroup[0]->getCity()->getId()?>, 'scrollbar_link<?php echo $cityGroup[0]->getCity()->getId()?>', 'viewport_link<?php echo $cityGroup[0]->getCity()->getId()?>','overview_link<?php echo $cityGroup[0]->getCity()->getId()?>')"> <?=count($cityGroup)?> Locality(s)
                    <i class="sprite-bg blue-rt-icon"></i>
                    </a>
                    </p>
                    <?php }?>
                    
             <?php 
        }
        ?>
                    <?php
        if($overlay == "inline"){
       
       ?>
              <?php $index++; ?>
               <p><a href="javascript:void(0);"  id = "inline_anchor_id<?php echo $cityGroup[0]->getCity()->getId()?>"  onclick="showMultiLayerContent('<?php echo $otherLocationExists; ?>','<?php echo $index; ?>','inline','inline_anchor_id<?php echo $cityGroup[0]->getCity()->getId()?>','multilocation_layer_inline<?php echo $cityGroup[0]->getCity()->getId()?>',<?=count($cityGroup)?>,'<?php echo $cityGroup[0]->getCity()->getId()?>','scrollbar_inline<?php echo $cityGroup[0]->getCity()->getId()?>','viewport_inline<?php echo $cityGroup[0]->getCity()->getId()?>','overview_inline<?php echo $cityGroup[0]->getCity()->getId()?>')"> <?=count($cityGroup)?> Locality(s)
                    <i class="sprite-bg blue-rt-icon"></i>
                    </a>
                    </p>     
                    <?php
        }
                    ?>

                </li>
                <?php
                    }
                 }
                 ?>
              </ul> 
            <ul class="branch-list">
              <?php
              if( count($otherLocations) > 0){
                  
              ?>
              
                                <p>Other Cities</p>
                                	
                                        
			<?php
				foreach($otherLocations as $key=>$location){
					$additionalURLParams = "?city=".$location->getCity()->getId()."&locality=";
					$listing->setAdditionalURLParams($additionalURLParams);
					$cityName = explode("-",$location->getCity()->getName());
					if($location->getCustomLocalityName()!="" &&  $location->getLocality()->getId() ==''){
		                echo '<li><a href="'.$listing->getURL().'">'.$cityName[0]." - ".$location->getCustomLocalityName().'</a></li>';
        		    }elseif($location->getCity()->getName()!= ""){
                        echo '<li><a href="'.$listing->getURL().'">'.$location->getCity()->getName().'</a></li>';
                    }
                }
              }
			?>
            </ul>
                                    
                               
            </div>
         </div>
   </div>
   		<div class="clearFix"></div>
    </div>

   </div>

  <!-- code moved to prevent event bubbling :start -->
     <?php
            if(count($loctionsWithLocality) > 0){
              foreach($loctionsWithLocality as $cityGroup){
                           
    	?>
  
     <?php
        if($overlay == "link"){
       
       ?>
        <div style="display:none" onmouseleave="(this.style.display='none');" class="locality-layer scrollbar1" id="multilocation_layer_link<?php echo $cityGroup[0]->getCity()->getId()?>">
            <div class="scrollbar" style="display: none" id="scrollbar_link<?php echo $cityGroup[0]->getCity()->getId()?>">
                      
 <?php
        }
        ?>
            <?php
        if($overlay == "inline"){
       
       ?>
            <div style="display:none" onmouseleave="(this.style.display='none');" class="locality-layer scrollbar1" id="multilocation_layer_inline<?php echo $cityGroup[0]->getCity()->getId()?>">
<div class="scrollbar" style="display: none" id="scrollbar_inline<?php echo $cityGroup[0]->getCity()->getId()?>">
                   
 <?php
        }
        ?>
               <div class="track"> 
                <div class="thumb">
            <div class="end"></div>
        </div>
    </div>
</div>

    <?php 
    if($overlay=="link")
    {
        ?>
     <div class="viewport" id="viewport_link<?php echo $cityGroup[0]->getCity()->getId()?>">
    <div class ="overview" id="overview_link<?php echo $cityGroup[0]->getCity()->getId()?>">
        
    <?php }
    ?>
     <?php 
    if($overlay=="inline")
    {
        ?>
    <div class="viewport" id="viewport_inline<?php echo $cityGroup[0]->getCity()->getId()?>">
    <div class ="overview" id="overview_inline<?php echo $cityGroup[0]->getCity()->getId()?>">
    <?php }
    ?>
<p><?php echo count($cityGroup); ?> Locality(s)<i class="sprite-bg gray-dwn-icon"></i></p>
       
  <ul>
        <?php
        
				foreach($cityGroup as $key=>$location){
					$additionalURLParams = "?city=".$location->getCity()->getId()."&locality=".$location->getLocality()->getId();
					$listing->setAdditionalURLParams($additionalURLParams);
			
			foreach($instituteCourses as $instituteCourse)
			{	//check if the institutes' courses are valid coursepages to show coursepage header.
				if(count($COURSE_PAGES_SUB_CAT_ARRAY[$instituteCourse])>0 )
				{
					if($_REQUEST['cpgs'])
					 $cpgs_append = '&cpgs='.$_REQUEST['cpgs'];
					else if($googleRemarketingParams['subcategoryId'][0]==$instituteCourse)
					 $cpgs_append="&cpgs=".$googleRemarketingParams['subcategoryId'][0];

				}
			}
			echo '<li><a href="'.$listing->getURL().$cpgs_append .'">'.$location->getLocality()->getName().'</a></li>';
					
				}
			?>
        </ul>
  
</div>
</div> 
        </div>
 <?php  }  
      }  ?>
  <!-- code moved to prevent event bubbling : ends -->
    
   
<script>
 var overlayParentListings;
function showAllBranches<?=$listing->getId()?>(id,layerOpenFlag,otherLocationExist){
	$j('#allLocations'+id).show();
    var overviewHeight = $j('#seeallbranches_layer_link').find(".overview").height();
    if(overviewHeight < 365)
     {  
         //no scroll condition  
        //increasing viewport height slightly to get no scroll condition and set height of viewport whatever  is of overview.
         //$j('#seeallbranches_layer_link').find(".viewport").height(overviewHeight+2);
		if($j('#seeallbranches_layer_link').find("#outer_scrollbar_link").length >0)
        	{ 	
        	   if(layerOpenFlag)
        	   { 
				var viewportHeight = $j('#seeallbranches_layer_link').find("#overview_outer_link").height();	
				$j('#seeallbranches_layer_link').find("#overview_outer_link").height(viewportHeight+60);
        	   }
        	   else if(otherLocationExist == 0)
               {
                        var viewportHeight = $j('#seeallbranches_layer_link').find("#overview_outer_link").height();
                                $j('#seeallbranches_layer_link').find("#overview_outer_link").height(viewportHeight+35);
              	}

        	   
				$j('#seeallbranches_layer_link').find("#outer_scrollbar_link").remove();
        		$j('#seeallbranches_layer_link').find("#viewport_outer_link").removeClass("viewport");
        		$j('#seeallbranches_layer_link').find("#overview_outer_link").removeClass("overview");
           	}	
     }else
     {
        //scroll condition and fix viewport to 365 height.
         $j('#seeallbranches_layer_link').find(".viewport").height(365);   
     }       
     $j('#allLocations'+id).hide(); 
    var content = $('allLocations'+id).innerHTML;
   if(typeof(overlayParentListings)!='undefined')
   { 
	showListingsOverlay(665,'auto','',overlayParentListings);
   }else
   {
    overlayParentListings = $('allLocations' + id).innerHTML;
    showListingsOverlay(665,'auto','',content);
   }
    $('allLocations'+id).innerHTML = "";
    if($j('#seeallbranches_layer_link').find("#outer_scrollbar_link").length >0)
    {
    $j("#seeallbranches_layer_link").tinyscrollbar();
    }
    if(layerOpenFlag) {
        
    $j(".layer_link_scroll").tinyscrollbar();
    }
    
    }

function showMultiLayerContent(isOtherLcoationExists,index,layer_type,anchor_id,div_id,count, city_id,scrollbar_id, viewport_id,overview_id){

var div_position = $j("#"+anchor_id).offset();
//document.getElementById(div_id).style.display = "block";
$j("#"+div_id).position(div_position);
$j("#"+div_id).css({'top':div_position.top,'left':div_position.left,'z-index':1000005 });
$j("#"+div_id).show();

if(count>4)
{

document.getElementById(overview_id).style.position = "absolute";
 document.getElementById(viewport_id).style.height = "120px";

$j("#"+scrollbar_id).show();
$j("#"+div_id).tinyscrollbar();

    }else{
       
        document.getElementById(viewport_id).style.height = "auto";
        document.getElementById(overview_id).style.position = "static";
    }

 if(isOtherLcoationExists == 0 && index > 4 && layer_type == 'link')
 {   
$j("#"+div_id).css({'top':div_position.top-$j("#"+div_id).height()+5,'left':div_position.left,'z-index':1000005 });    
 }
}

</script>
