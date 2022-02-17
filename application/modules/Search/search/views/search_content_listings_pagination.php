<?php
$contentData = $content;
$pageId = calculatePageId($general['rows_count']['content_rows'], $solr_content_data['start']);

$displayRowCount = 1;
foreach($contentData as $contentRow){
	$rowType = $contentRow->getFacetype();
	$viewData = array(
				'data' 		=> $contentRow,
				'rowType' 	=> $rowType,
				'pageId' 	=> $pageId,
				'count' 	=> $displayRowCount,
				);
	switch($rowType){
		case 'article':
			$this->load->view('search/search_article_snippet', $viewData);
			break;
		
		case 'question':
			$this->load->view('search/search_question_snippet', $viewData);
			break;
		
		case 'discussion':
			$this->load->view('search/search_discussion_snippet', $viewData);
			break;
		
	}
	$displayRowCount++;
}
?>