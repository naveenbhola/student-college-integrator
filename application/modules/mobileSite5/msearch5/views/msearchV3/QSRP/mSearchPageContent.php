<?php $this->load->view('msearch5/msearchV3/msearchPageHeader'); ?>

<div class="srp-new">
    <div class="srp_wrapper">
        <div class="srp_nav">
            <?php if($currentTab == 'answered') {
                $classAnsActive = 'active';
            } else if ($currentTab == 'topics') {
                $classTopicActive = 'active';
            } else if ($currentTab == 'unanswered') {
                $classUnansActive = 'active';
            } ?>

            <?php if(!empty($tabURL['answered'])) { ?>
                <a tab='answered' class="tabs first-nav <?php echo $classAnsActive; ?>" <?php if(empty($classAnsActive)) { ?> href="<?php echo $tabURL['answered']; ?>" <?php } ?> >Answered Questions <span class="nav_indicator"></span></a>
            <?php } ?>

            <?php if(!empty($tabURL['topics'])) { ?>
                <a tab='topics' class="tabs second-nav <?php echo $classTopicActive; ?>" <?php if(empty($classTopicActive)) { ?> href="<?php echo $tabURL['topics']; ?>" <?php } ?> >Topics <span class="nav_indicator"></span></a>
            <?php } ?>

            <?php if(!empty($tabURL['unanswered'])) { ?>
                <a tab='unanswered' class="tabs last-nav <?php echo $classUnansActive; ?>" <?php if(empty($classUnansActive)) { ?> href="<?php echo $tabURL['unanswered']; ?>" <?php } ?> >Unanswered Questions <span class="nav_indicator"></span></a>
            <?php } ?>
        </div>
        <?php if(count($tupleData['filters']) >= 1 && in_array($currentTab, array('answered', 'unanswered'))) { ?>
            <a href="#searchFilters" data-transition="none" id="filterButton" currentTab="<?php echo $currentTab; ?>" class="nav_main_head">FILTER <i class="msprite filter-icon"></i></a>
        <?php } ?>
    </div>

    <?php switch ($currentTab) {
        case 'answered':
            //Answered Question Tab
            $this->load->view('msearch5/msearchV3/QSRP/answeredQSRP');
            break;

        case 'topics':
            //Question Topic Tab
            $this->load->view('msearch5/msearchV3/QSRP/questionTagSRP');
            break;

        case 'unanswered':
            //Unanswered Question Tab
            $this->load->view('msearch5/msearchV3/QSRP/unansweredQSRP');
            break;
    } ?>

    <!-- Loading Image -->
    <div id="loader" class="srp-loader"><img class="small-loader" id="loadingImage1" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" border=0 alt="" /></div>

    <!-- Pagination -->
    <?php $this->load->view('msearch5/msearchV3/msearchPagination'); ?>
</div>

<?php $this->load->view('msearch5/msearchV3/QSRP/msearchPageFooter'); ?>