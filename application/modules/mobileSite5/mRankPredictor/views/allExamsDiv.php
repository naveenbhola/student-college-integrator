<?php
$this->load->config('RP/RankPredictorConfig',TRUE);
$settings = $this->config->item('settings','RankPredictorConfig');
$exams = $settings['RPEXAMS'];
?>
<header id="page-header" class="clearfix">
    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
        <a id="rankPredictorOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   	
        <div class="title-text" id="rankPredictorOverlayHeading">Rank Predictor</div>
    </div>
</header>

<section class="layer-wrap fixed-wrap" style="height: 100%">
    <ul class="layer-list">
        <?php foreach ($exams as $examName => $examData):?>
            <li><a href="<?php echo $examData['url'];?>"><?php echo $examData['name'];?></a></li>
        <?php endforeach; ?>
    </ul>
</section>
<a href="javascript:void(0);" onclick="$('#rankPredictorOverlayClose').click();" class="cancel-btn">Cancel</a>