<div class="crs-widget listingTuple <?php if($showCampusReps){echo 'mb15';} ?>" style="overflow:hidden;">
        <h2 class="head-L2 view-font">Ask your queries to current students</h2>
	<div class="ana-slider">
        <ul class="curr-stuList">
	    <?php foreach ($campusReps as $rep){ ?>
            <li>
                <div class="lcard" style="min-height:auto;">
		  <?php
                    if($rep['imageURL'] != '' && strpos($rep['imageURL'],'photoNotAvailable') === false){
                        $rep['imageURL'] = addingDomainNameToUrl(array('url' => $rep['imageURL'] , 'domainName' => MEDIAHOSTURL));
                        ?>
                    <div class="curr-stuFig" style="margin-bottom:8px;">
                        <img src="<?=getSmallImage($rep['imageURL'])?>" style="height: 61px;width: 100%;"/>
                    </div>
		  <?php }else{ ?>
                    <div class="curr-stuFig user-i-card">
                        <?= ucwords(substr($rep['displayName'],0,1)); ?>
                    </div>

		  <?php } ?>
                    <p class="para-L3" style="height:35px;">
                        <?php
                                if(strlen($rep['displayName']) <= 30){
                                        echo $rep['displayName'];
                                }
                                else{
                                        echo substr($rep['displayName'],0,27).'...';
                                }
                        ?>
		    </p>
                </div>
            </li>
	    <?php } ?>
        </ul>
	</div>
	
	<?php if(!$showCampusReps){
		?>
        <div class="ask-cta-wrp">
            <?php $this->load->view("institute/widgets/AskNowCTA");  ?>
        </div>
        <?php
	} ?>
</div>

<script>
<?php foreach ($campusReps as $rep){ ?>
$('p[id="currentStudent_<?=$rep['userId']?>"]').show();
<?php } ?>
</script>

