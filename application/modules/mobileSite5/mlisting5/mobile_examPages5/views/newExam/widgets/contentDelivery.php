<?php $findString = '.shiksha.com'; ?>

            <section style="clear:both">
            <div class="data-card">
                <h2 class="color-3 f16 heading-gap font-w6"></h2>
                <div class="lcard color-w f14 color-3">
		<?php foreach($info as $key=>$value){ 
                $checkShikshaUrl = strpos($value['redirection_url'], $findString);
                if($checkShikshaUrl === false){
                    $addNoFollow = 'rel="nofollow"';
                }else{
                    $addNoFollow = '';
                }
            ?>
		    <div class="cd-m10">
                    	<p><strong class="font-w6 block m-5btm"><?=htmlentities($value['heading'])?></strong></p>
	                <p class="f12"><?=htmlentities($value['body'])?></p>
        	        <div class="btn-sec">
                	        <a href="<?=$value['redirection_url']?>" cd_attr = "<?php echo $value['id'];?>" target="_blank" class="btn btn-secondary color-w color-b f14 font-w6 m-15top m-5btm cd-wdt" ga-attr="CD_LINK" <?=$addNoFollow?> ><?=htmlentities($value['CTA_text'])?></a>
                    	</div>
		    </div>
		<?php } ?>
                </div>
                <div class="cd-sponsored">Sponsored</div>
               </div>
          </section>

