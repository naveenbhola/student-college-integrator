<style>.disabled {pointer-events: none;cursor: default;}</style>
    <div class="wrapperFxd">
    	<div id="content-child-wrap">
			
            <div id="predictor-wrap">
                <?php
                    $displayData['typeOfPredictor'] = 'rank';
                    $this->load->view('CP/V2/breadcrumb', $displayData);
                ?>
                <?php $this->load->view('headerMsg');?>
		
		<?php
		$form = $rpConfig[$examName]['requiredForm'];
		if(count($getCookei)>0 && isset($validateuser[0]['userid']) && $validateuser[0]['userid']>0){?>
		<div style="display: none;" id="restForm-box">
		    <?php $this->load->view($form);?>
		</div>
		<?php } else if(count($getCookei)<=0 && isset($validateuser[0]['userid']) && $validateuser[0]['userid']>0){?>
		<div  id="restForm-box">
		    <?php $this->load->view($form);?>
		</div>
		<?php } else{?>
                <div id="addForm">
		    <div id='loaderDiv' style='height: 450px; text-align: center;'><img style='padding-top: 120px;' src='/public/images/loader.gif' border=0></img></div>
		</div>
		<?php }?>
                <?php $this->load->view('collegePredictorResults');?>
                               
            </div>
	</div>
    </div>