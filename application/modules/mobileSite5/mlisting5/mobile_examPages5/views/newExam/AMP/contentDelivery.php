<?php $findString = '.shiksha.com'; ?>
          <section>
            <div class="data-card">
                <div class="heading-gap"></div>
                <div class="card-cmn color-w f14 color-3 l-12">
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
                        	<a href="<?=$value['redirection_url']?>" data-vars-event-name="CD_LINK" target="_blank" class="btn btn-secondary color-w color-b f14 font-w6 m-15top custom-click-analytic ga-analytic" data-vars-custom-event-name="<?=$value['id'];?>" <?=$addNoFollow?> ><?=htmlentities($value['CTA_text'])?></a>
	                    </div>
			</div>
                <?php } ?>
                </div>
                <div class="cd-sponsored">Sponsored</div>
              </div>
          </section>

