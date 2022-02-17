<section class="iim_v1">
	<h1 class="fnt22 hid">CAT <?php echo date('Y');?> Percentile Predictor </h1>
    <h2 class="score_h2">Your CAT Score : <strong><?php echo $catScore;?></strong></h2>

<p class="percentile_v1">Your Predicted Percentile: <span class='iim-prcntile'> <?php echo $percentile;?> </span></p>    <a href="javascript:void(0)" class="reset-btn2">Modify</a>
</section>

<?php $this->load->view('mIIMPredictor5/iimPercentile/iimPercentileOutputTuple')?>