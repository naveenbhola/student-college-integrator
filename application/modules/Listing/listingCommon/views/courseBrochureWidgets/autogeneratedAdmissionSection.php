<div class="cmn-card mb2">
    <h2 class="f20 clor3 mb2 f-weight1">Admission Process</h2>
    <?php foreach ($admissions as $stage => $value) { ?>
        <div class="ad-stage">
            <p class="stage-titl"><?=$value['admission_name']?></p>
            <p class="ad-txt"><?=nl2br($value['admission_description'])?></p>
        </div>
    <?php } ?>

</div>