<?php foreach($similarExams['similarExams'] as $examKey => $examValue) {
		if(!empty($examValue['CTAText']) && !empty($examValue['redirectUrl'])){
	       	$redirectUrl = $examValue['redirectUrl'];
	        $findString = '.shiksha.com';
	        $checkShikshaUrl = strpos($examValue['redirectUrl'], $findString);
	        if($checkShikshaUrl === false){
	          $addNoFollow = 'rel="nofollow"';
	        }else{
	          $addNoFollow = '';
	        }
	    }else{
	    	 $redirectUrl = $examValue['url'].'?course='.$examValue['groupId'];
	    	 $addNoFollow = '';
	    }

	    $year = (!empty($examValue['year']))?' '.$examValue['year']:'';
      ?>
		<li onClick="window.location='<?=$redirectUrl;?>';">
			<section>
			<a class="f14 color-3 font-w6" href="<?=$redirectUrl;?>" ga-attr="SIMILAR_EXAM" <?=$addNoFollow?>>
			<?php echo htmlentities($examValue['examName']);?><?=$year?>
			</a>
	            <?php if(!empty($examValue['examFullName'])) { ?>
	            	<p class="f14 color-3 font-w6"><?php echo htmlentities($examValue['examFullName']);?></p>
	            <?php } ?>
	            <?php if(!empty($examValue['conductedBy'])) { ?>
	            	<span class="color-6 f11">Conducted by <?=htmlentities($examValue['conductedBy']);?></span>
	            <?php } ?>
	            <?php if(!empty($examValue['CTAText']) && !empty($examValue['redirectUrl'])){?>
                    <p class="f11 color-b font-w6"><?php echo htmlentities($examValue['CTAText']);?></p>
                <?php } ?>
            </section>
        </li>
<?php } ?>
