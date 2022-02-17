<div class="ranking-wrapper">
	<div id="printableData">
		<?php 
			$this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingPageTitle');
			$this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingPageFilterSec');
		?>
		<div class="sticky-header" style="visibility:hidden;"  id="_rankingFilterSticky">
			<?php $this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingPageComaritiveRankingTableHeaderSticky'); ?>
		</div>
            
		<?php $this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingPageComparitiveRankingSec'); ?>
	</div>
</div>
<div class="page-break clear-width"></div>
<script>
	isCompareEnable = true;
</script>
<?php
	$this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingPageRelatedRankingSec');
	$this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingPageFooterWidgets');
	$this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/widgets/rankingPageCategoryPageWidget');
?>