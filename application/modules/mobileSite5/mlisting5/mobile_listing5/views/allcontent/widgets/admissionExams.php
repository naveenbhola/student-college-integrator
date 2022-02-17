<?php if(!empty($examList) && count($examList) > 0){ ?>
	<div class="gap">	
            <h2 class="head-L2 head-gap"><?=ucfirst($listingType);?> Exams</h2>
            <div class="lcard end-col noMrgn">
                <ul class="max-ul">
		<?php foreach($examList as $examKey => $examValue) { 
			$examYear = ($examValue['year']!='')?' '.$examValue['year']:'';
			?>
                    <li>
                        <a class="" href="<?=$examValue['url']?>" ga-attr="VIEW_EXAM">
                            <div class="">
                                <p class="inst-title"><?=$examValue['name']?><?=$examYear?></p>
                                <?php if(trim($examValue['description'])) {
                                       $examValue['description'] = trim($examValue['description']);
                                       if(strlen($examValue['description']) > 72 ){
                                           $examValue['description'] = substr($examValue['description'], 0,69).'...';
                                       } ?>
	                               <p class="period"><?=$examValue['description']?></p>
				<?php } ?>
                            </div>
                        </a>
                    </li>
		<?php } ?>
                </ul>
            </div>
        </div>
<?php } ?>

