<?php if(count($dataArray) > 0){ ?>
<div class="crs-widget mb15" style="overflow:hidden;width:100%;">
    <h2 class="head-L2 view-font">You may be interested in <?=$pageType?> on</h2>
    <div class="rec-clg-list recommendation-slider">
    <ul class="curr-stuList">
        <?php
            $i = 0;
            foreach ($dataArray as $recommendation){ ?>
        <li>
	<a href="<?=$recommendation['URL']?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Link;?>','<?php echo $GA_userLevel;?>');">
            <div class="lcard">
                <div class="clg-fig"><img src="<?=$recommendation['listingImage']?>" width=100 height=80></div>
                <p class="para-L3">
			<?php
				if(strlen($recommendation['listingName']) <= 50){
					echo $recommendation['listingName'];
				}
				else{
					echo substr($recommendation['listingName'],0,47).'...';
				}
			?>
		</p>
            </div>
	</a>
        </li>
        <?php
            $i++;
            if($i == 5)
                break;
            } ?>
    </ul>
    </div>
</div>
<?php } ?>
