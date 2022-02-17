<?php if(count($recommendations)>0){?>
		<ul class="list-items recom-items">
                <li class="recom-title"><strong>Recommended Colleges (<?php echo count($recommendations);?>)</strong>Recommended based on Colleges you Viewed</li>
		<?php foreach($recommendations as $reco){?>
                <li><a onclick="trackEventByGAMobile('MOBILE_RECOMMENDED_COLLEGES_CLICK'); window.location='<?php echo $reco['courseURL']?>';" href="javascript:void(0);"><strong><?php echo $reco['instituteFullName']?></strong> <?php echo $reco['mainCityName']?></a></li>
		<?php }?>	
		</ul>
<?php }?>