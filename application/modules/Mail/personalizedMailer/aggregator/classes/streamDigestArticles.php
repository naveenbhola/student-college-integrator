<?php 

include_once '../WidgetsAggregatorInterface.php';

class streamDigestArticles implements WidgetsAggregatorInterface{
	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}

	public function getWidgetData() {
		$customParams = $this->_params['customParams'];
		$streamId     = $customParams['streamId'];
		$source       = $customParams['source'];

		// get top 3 articles in all articles page
		$articleLib = $this->CI->load->library('article/ArticleUtilityLib');
		$articlesData = $articleLib->getArticleBasedOnEntity(array('entityType' =>'stream', 'streamId' => $streamId));

		foreach ($articlesData['articleDetail'] as $row) {
			$customParams['instituteRecommendedArticles']['articlesDetail'][] = array('url' => add_query_params($row['url'],array('utm_term' => $source)),'blogTitle' => $row['title']);
		}
		
		if(!empty($articlesData['articleDetail'])){

			$utmTerm = 'TopArticles';
			if(!empty($source)){
				$utmTerm = $utmTerm."_".$source;
			}
			// get all articles page url and count
			$countUrlData = $articleLib->getAllArticlePageUrlAndCount('stream',array('stream_id' => $streamId));
			$customParams['instituteRecommendedArticles']['articleCount'] = $countUrlData['count'];
			$customParams['instituteRecommendedArticles']['allArticleURL'] = add_query_params($countUrlData['url'],array('utm_term' => $utmTerm));

			$customParams['instituteName'] = $customParams['streamName'];

			$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/instituteRecommendedArticles", $customParams, true);
		}
		return array('key' => 'streamDigestArticles', 'data' => $widgetHTML);
	}
}

?>