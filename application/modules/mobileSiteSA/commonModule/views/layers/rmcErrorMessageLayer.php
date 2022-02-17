<div id="rmcErrorMessageLayer" data-role= "page" style = "width:100% !important;" data-enhance="false">
    <?php if($drawLayer === true){ ?>
        <div class="header-unfixed">
            <div class="layer-header">
                <a class="back-box" href="#wrapper" data-transition="slide" data-rel="back">
                    <i class="sprite back-icn"></i></a>
                <p style="/*text-align:center*/" id="rmcErrorUniversityName"><?=htmlentities($courseObj->getUniversityName())?></p>
            </div>
        </div>
        <?php if($rating == 5){?>
            <div id="rmcInactive" class="rmc-layer">
                <strong>Rate my chances</strong>
                <p>Oops! You can not do rate my chance on this course because your application is already under process for this course.</p>
                <p>For any question, Please contact us at <a href="mailto:applyabroad@shiksha.com">applyabroad@shiksha.com</a></p>
                <a class="rmc-ok-btn" href="#wrapper" data-transition="slide" data-rel="back">OK</a>
            </div>
        <?php } ?>
        <?php if($stage === true){?>
            <div id="rmcInactive" class="rmc-layer">
                <strong>Rate my chances</strong>
                <p>Oops! It seems like you have been marked inactive in our system.</p>
                <p>To get it corrected, please contact our counselling team at <a href="mailto:applyabroad@shiksha.com">applyabroad@shiksha.com</a></p>
                <a class="rmc-ok-btn" href="#wrapper" data-transition="slide" data-rel="back">OK</a>
            </div>
        <?php } ?>
        <?php if($limit === true){?>
            <div id="rmcLimit" class="rmc-layer">
                <strong>Rate my chances</strong>
                <p>Oops! You can only check your chances in <?=ABROADRMCLIMIT?> colleges.</p>
                <p>To check your chances on more colleges, contact our counselling team at <a href="mailto:applyabroad@shiksha.com">applyabroad@shiksha.com</a></p>
                <a class="rmc-ok-btn" href="#wrapper" data-transition="slide" data-rel="back">OK</a>
            </div>
        <?php } ?>
        <div class="clearfix"></div>
    <?php } ?>
</div>