<header id="page-header" class="clearfix">
        <div class="head-group">
            <a href="javascript:void(0);" data-rel="back"><span class="head-icon-b"><i class="icon-arrow-left"></i></span></a>
            <h3>All Branches</h3>
        </div>
    </header>

<section class="layer-wrap">
        <ul class="layer-list">
        <!-----li class="search-option">
                <form id="searchbox2" action="">
                <span class="icon-search" aria-hidden="true"></span>
                <input id="search" type="text" placeholder="Delhi">
                <i class="icon-close">x</i>
            </form>
        </li----------->
        <?php	if(count($loctionsWithLocality) > 0){
			foreach($loctionsWithLocality as $cityGroup){ ?>
                        <li class="non-click"><a href="javascript:void(0);"><strong><?=$cityGroup[0]->getCity()->getName()?></strong></a></li>
                                 <?php foreach($cityGroup as $key=>$location){
                                        $additionalURLParams = "?city=".$location->getCity()->getId()."&locality=".$location->getLocality()->getId();
                                        $listing->setAdditionalURLParams($additionalURLParams); 
                                        echo '<li onclick="redirectToURL(\''.$listing->getURL().'\');"><a class="sub-branch" href="'.$listing->getURL().'">'.$location->getLocality()->getName().'</a></li>';
                                        }     ?>

                        <li class="non-click">&nbsp;</li>
                <?php }
                }?>
                
                <?php
		if(count($otherLocations) > 0){
			
	?>
		 <li class="non-click"><a href="javascript:void(0);"><strong>Other Cities</strong></a></li>
			<?php
				foreach($otherLocations as $key=>$location){
					$additionalURLParams = "?city=".$location->getCity()->getId()."&locality=";
					$listing->setAdditionalURLParams($additionalURLParams);
					echo '<li onclick="redirectToURL(\''.$listing->getURL().'\');"><a class="sub-branch" href="'.$listing->getURL().'">'.$location->getCity()->getName().'</a></li>';
				}
			?>
		
	<?php
		}
	?> 
       
    </ul>
</section>


<script>function redirectToURL(url){window.location.href=url;}</script>

