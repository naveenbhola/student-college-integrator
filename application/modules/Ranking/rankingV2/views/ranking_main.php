<?php
	$this->load->view('ranking/ranking_header');
?>

<div id="ranking-content">
	<?php $this->load->view('ranking/ranking_banner');
		$subCatId = $ranking_page->getSubCategoryId();
		/*if($subCatId == 23) {
			$dataArray = array();
			//$dataArray['CUSTOMIZED_TABS_BAR'] = array('Home', 'Institutes', 'Faq', 'AskExperts', 'Rankings', 'Exams'); // New Exam Page link : uncomment this line and comment below line.
			$dataArray['CUSTOMIZED_TABS_BAR'] = array('Home', 'Institutes', 'Faq', 'AskExperts', 'Rankings', 'News');
			$dataArray['AUTOSCROLL'] = 1;			
			echo '<div style="padding:0px;">';
				echo Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', $subCatId, "Rankings", TRUE, $dataArray);
			echo '</div>';
		}*/
	?>
	<div id="ranking-header">
		<?php $this->load->view('ranking/ranking_headline'); ?>
		<?php $this->load->view('ranking/ranking_filters'); ?>
	</div>
	<?php
		$ranking_table_data = array();
		$ranking_table_data['ranking_page'] 	= $ranking_page;
		$ranking_table_data['filters'] 	    	= $filters;
		$ranking_table_data['sorter'] 	    	= $sorter;
		$ranking_table_data['resultType'] 		= "main";
		$ranking_table_data['request_object'] 	= $page_request;
		$this->load->view('ranking/ranking_table_container', $ranking_table_data);
		
		$this->load->view('ranking/ranking_extra_results');
        $this->load->view('ranking/ranking_featured_inst_table');
		$this->load->view('ranking/ranking_request_ebrochure');
		$this->load->view('ranking/ranking_see_more_links');
		$this->load->view('ranking/rankingpage_left_col');
		$this->load->view('ranking/rankingpage_right_col');
	?>
	<div class="clearFix"></div>
</div>
<?php
	$this->load->view('common/footerNew',array('loadJQUERY' => 'YES'));
?>
