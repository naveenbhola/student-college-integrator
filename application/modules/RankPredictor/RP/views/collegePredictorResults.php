
<?php $this->load->view('resultWidget');?>	

<div class="result-container" id="predictor-container" <?php if(count($getCookei)>0 && isset($validateuser[0]['userid']) && $validateuser[0]['userid']>0){?> style="display:none;" <?php }else{?> style="display:block" <?php }?>>
<?php if($rpConfig[$examName]['isTopCollegesWidget'] == 'YES'){?>
	<h2 class="rankPredictorTitle" id="_container-heading">Closing <?=$seoName?> Rank of Top Engineering Colleges</h2>
	<p id="_nocollege" style="display: none; font-size: 16px;color: #999;"></p>
	<div id="predictor-result">
		<?php if($examName == 'jee-main'){
			$exam = 'JEE-Mains';
			}else{
			$exam = $examName;
			}
			echo Modules::run('RP/RankPredictorController/showCollegePredictorData',$exam);
		?>
	</div>
	<?php }?>
</div>
<?php if($rpConfig[$examName]['isStaticCollgePredictorWidget'] == 'YES'){?>
<div class="predictor-rank-fullWidget clearFix" id="predictor_staticWidget" style="display: none;cursor:pointer;float: left;margin: 15px 0 10px; width:100%; -moz-box-sizing:border-box;-webkit-box-sizing:border-box; box-sizing:border-box;cursor: default;" onClick="trackEventByGA('RANK_PREDICTOR_COLLEGE_PREDICTOR_WIDGET_CLICK','RANK_PREDICTOR_COLLEGE_PREDICTOR_CLICK');">
                	<div class="flLt">	
                    	<i class="predictor-sprite college-predictor-icon"></i>
                    </div>
                    <div class="collge-predictor-rnk-info">
                    	<p><?php echo $rpConfig[$examName]['staticWidgetHeading'];?></p>
                        <p style="color:#747474;" class="font-12"><a class="font-14" href="<?php echo $rpConfig[$examName]['staticWidgetUrl'];?>" style=" text-decoration: none;" title="Click"><?php echo $rpConfig[$examName]['staticWidgetLinkText'];?></a></p>
                    </div>
                    <div class="clear_B"></div>
</div>
<?php }?>