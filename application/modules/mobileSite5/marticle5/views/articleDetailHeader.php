<header id="page-header2" class="clearfix" data-role="header">
    <div class="head-group" data-enhance="false">
        <div>
            <?=$breadcrumbHtml?>
            <h1 class="pageHeading">
                <?=$blogObj->getTitle();?>
            </h1>
        </div>
    </div>
    <!--This is against ticket UI-47-->
    <?php $this->load->view('articleDetailsOtherInfo');?>
</header>
<div id="askLink" onclick="gotoAsk()"><a><i class="chatIcon"></i><span id="askInner">Ask or Comment</span></a></div>
<div class="social-wrapper-top social-wrapper">
<?php $this->load->view("mcommon5/socialSharingBand", array('widgetPosition' => 'ADP_Top')); ?>
</div>

