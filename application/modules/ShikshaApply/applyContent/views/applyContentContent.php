<div itemscope itemtype="http://schema.org/Article">
    <div class="applyContentBreadcrumb clearfix">
        <?php
        $this->load->view('studyAbroadCommon/breadCrumbs');
        ?>
    </div>
<div id="main-wrapper">
    <div class="content-wrap clearfix">
    <?php $this->load->view('widget/applyContentTopNavigation'); ?>
    </div>
	<div class="apply-content-div clearfix">
		<?php $this->load->view('applyContent/widget/applyContentLeftColumn'); ?>
		<?php $this->load->view('applyContent/widget/applyContentRightColumn'); ?>
	</div>
</div>
<?php
$this->load->view('applyContent/widget/structureDataMarkupMeta');
?>
</div>
