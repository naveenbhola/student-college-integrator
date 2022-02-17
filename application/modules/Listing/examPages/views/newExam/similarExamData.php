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
        <li>
             <section class="ps__rl tbl-str" onClick="window.open('<?=$redirectUrl;?>');">
               <aside class="i__block">
                 <p class="f14__clr3 fnt__sb">
			<a href="<?=$redirectUrl;?>" target="_blank" <?=$addNoFollow?> ga-attr="SIMILAR_EXAM">	
			<?php 
                    echo htmlentities($examValue['examName']);?><?=$year?>
			</a>
		</p>
                <?php if(!empty($examValue['examFullName'])) { ?>
                 <p class="f14__clr3 fnt__sb"><?php 
                    echo htmlentities($examValue['examFullName']);?></p>
                <?php } ?>
                <?php if(!empty($examValue['conductedBy'])) { ?>
                    <p class="f12__clr9 ">Conducted by <?php 
                    echo htmlentities($examValue['conductedBy']);?></p>
                <?php } ?>
                <?php if(!empty($examValue['CTAText']) && !empty($examValue['redirectUrl'])){?>
                    <p class="f12__clrb" ><?php echo htmlentities($examValue['CTAText']);?></p>
                <?php } ?>
                </aside>
            </section>
        </li>
<?php } ?>
