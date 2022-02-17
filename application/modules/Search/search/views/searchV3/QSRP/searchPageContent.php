<?php $this->load->view('nationalCategoryList/categoryPageHeader'); ?>

<div class="new_srp_block">
    <div class="srp_new clear_max">
        <!--left panel-->
        <div class="dribble_block">
            <h2 class="block_title">Filters</h2>
            <div class="filter_out">
                <?php if($currentTab == 'answered') {
                    $classAnsActive = 'active';
                } else if ($currentTab == 'topics') {
                    $classTopicActive = 'active';
                } else if ($currentTab == 'unanswered') {
                    $classUnansActive = 'active';
                } ?>

                <?php if(!empty($tabURL['answered'])) { ?>
                    <a tab='answered' class="tabs <?php echo $classAnsActive; ?>" <?php if(empty($classAnsActive)) { ?> href="<?php echo $tabURL['answered']; ?>" <?php } ?> >Answered Questions</a>
                <?php } ?>

                <?php if(!empty($tabURL['topics'])) { ?>
                    <a tab='topics' class="tabs <?php echo $classTopicActive; ?>" <?php if(empty($classTopicActive)) { ?> href="<?php echo $tabURL['topics']; ?>" <?php } ?> >Topics</a>
                <?php } ?>

                <?php if(!empty($tabURL['unanswered'])) { ?>
                    <a tab='unanswered' class="tabs <?php echo $classUnansActive; ?>" <?php if(empty($classUnansActive)) { ?> href="<?php echo $tabURL['unanswered']; ?>" <?php } ?> >Unanswered Questions</a>
                <?php } ?>
            </div>
            
            <?php if(count($tupleData['filters']) >= 1 && in_array($currentTab, array('answered', 'unanswered'))) { ?>
                <div class="sort_order">
                    <h2 class="block_title">By Time</h2>

                    <?php if(empty($tupleData['activeFilter'])) { $class = 'active';  $href="javascript:void(0);"; } else { $class = ''; $href = $tupleData['no_filter']['url']; } ?>
                    <a val="all" currentTab="<?php echo $currentTab; ?>" href="<?php echo $href; ?>" class="filterByVal <?php echo $class; ?>">All Time</a>

                    <?php foreach ($tupleData['filters'] as $filter) { ?>
                        <?php if($tupleData['activeFilter'] == $filter['name']) {
                            $class = 'active';
                            $href = "javascript:void(0);";
                        } else {
                            $class = '';
                            $href = $filter['url'];
                        } ?>
                        <?php switch ($filter['name']) {
                            case 'week': ?>
                                <a val="week" currentTab="<?php echo $currentTab; ?>" class="filterByVal <?php echo $class; ?>" href="<?php echo $href; ?>">Past Week</a>
                                <?php break;
                                        
                            case 'month': ?>
                                <a val="month" currentTab="<?php echo $currentTab; ?>" class="filterByVal <?php echo $class; ?>" href="<?php echo $href; ?>">Past Month</a>
                                <?php break;

                            case 'year': ?>
                                <a val="year" currentTab="<?php echo $currentTab; ?>" class="filterByVal <?php echo $class; ?>" href="<?php echo $href; ?>">Past Year</a>
                                <?php break;
                        } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <!--ends-->

        <?php switch ($currentTab) {
            case 'answered':
                //Answered Question Tab
                $this->load->view('search/searchV3/QSRP/answeredQSRP');
                break;

            case 'topics':
                //Question Topic Tab
                $this->load->view('search/searchV3/QSRP/questionTagSRP');
                break;

            case 'unanswered':
                //Unanswered Question Tab
                $this->load->view('search/searchV3/QSRP/unansweredQSRP');
                break;
        } ?>
    </div>
    
    <?php $this->load->view('search/searchV3/QSRP/searchPagination'); ?>
    <?php $this->load->view('messageBoard/desktopNew/shareLayer'); ?>
</div>
<?php $this->load->view('common/footerNew'); ?>

<script type="text/javascript">
    var retainSearchKeyword = '<?php echo addslashes($keyword); ?>';
    var trackingKeyIds = eval('(' + '<?php echo json_encode($trackingKeyIds);?>' + ')');
    
    initializeQSRP();
</script>