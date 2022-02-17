
<?php 
/*$predictorData = array(
   
        array
        (
            'name' => 'MPPET',
            'url' => 'http://localshiksha.com/b-tech/resources/mppet-college-predictor'
        )   ,
             array
        (
            'name' => 'MPPET',
            'url' => 'http://localshiksha.com/b-tech/resources/mppet-college-predictor'
        )   

  );*/
if(!empty($predictorData)) { $showRankWidget = false;?>

		<div class="data-card m-5btm">
             <div class="card-cmn color-w">
                 <h2 class="f14 color-3 font-w6 m-btm">Want to find out your chances getting into this Collge?</h2>

                 <?php if(count($predictorData) > 1 ) { 
                 		$onAttribute = "on=\"tap:predictor-dtls\"";
                 	}
                 	else{ 
                    if($predictorData[0]['name'] == 'JEE-Mains'){
                     $showRankWidget = true; 
                     $buttonText = 'Predict College'; 
                    }
                    else{
                    $buttonText = 'Find-Out Now';  
                    }
                 		$onAttribute = "href= '".$predictorData[0]['url']."'";
                 	}
                 ?>

                 <a data-vars-event-name="WANT_TO_FINDOUTNOW" class="btn btn-secondary color-w color-b f14 font-w6 ga-analytic" <?=$onAttribute;?>><?=$buttonText?></a>
             </div>
    </div>
  <?php if($showRankWidget){?>
    <div class="data-card m-5btm">
                 <div class="card-cmn color-w">
                     <h2 class="f14 color-3 font-w6 m-btm">Want to find out your chances getting into this Collge?</h2>

                     <?php $onAttribute = "href= '".$predictorData[0]['rank_predictor_url']."'"; ?>

                     <a data-vars-event-name="WANT_TO_FINDOUTNOW" class="btn btn-secondary color-w color-b f14 font-w6 ga-analytic" <?=$onAttribute;?>> Predict Rank</a>
                 </div>
            </div>
<?php } } ?>         

<?php if(count($predictorData) > 1 ) { ?>
	<amp-lightbox id="predictor-dtls" layout="nodisplay" scrollable>
       <div class="lightbox"  >
          <a class="cls-lightbox  color-f font-w6 t-cntr" on="tap:predictor-dtls.close" role="button" tabindex="0">&times;</a>
          <div class="m-layer">
            <div class="min-div color-w catg-lt pad10">
              <div class="m-btm padb">
                <?php             
		            foreach ($predictorData as $data) { ?>
		                <a class="block f14 color-b font-w6" href="<?php echo addslashes($data['url']); ?>"><?php echo htmlentities($data['name']);?></a>
		            <?php
		            }
		            ?>
              </div>
            </div>
          </div>
       </div>
  	</amp-lightbox>
 <?php } ?>


 <?php if(!empty($cutOffData)) { foreach ($cutOffData as $row) { ?>
    <div class="data-card m-5btm">
        <div class="card-cmn color-w">
            <h2 class="f14 color-3 font-w6 m-btm">Check cut-offs for this course and others</h2>
            <a href="<?php echo $row['url']?>" class="btn btn-secondary color-w color-b f14 font-w6 ga-analytic" ><?php echo $row['text']; ?></a>     
        </div>
    </div>
<?php  } } ?>
