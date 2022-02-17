<?php

include_once '../WidgetsAggregatorInterface.php';

class ImportantDatesMailerWidget implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {

		$params = $this->_params['customParams'];

		$templateId = $params['templateId'];
		$this->CI->load->model('mailer/mailermodel');
		$newsletterParams = $this->CI->mailermodel->getNewsletterParams($templateId);

		if(!trim($newsletterParams['articleIds'])) {
			return array();
		}
		$articleIds = explode(',',$newsletterParams['articleIds']);
		$articleIds = array_slice($articleIds, 0, 3);
		
		$this->CI->load->model('article/articlemodel');
		$articleData = $this->CI->articlemodel->getArticlesData($articleIds,FALSE);
		
		$articles = array();
		foreach($articleIds as $articleId) {
			foreach($articleData as $article) {
				if($article['blogId'] == $articleId) {
					$articles[] = $article;
					break;
				}
			}
		}
		
		$data = array();
		$new_article_list = array();
       	foreach($articles as $article_data) {
			if(!empty($article_data['blogImageURL'])) {
				$blog_img_url = $article_data['blogImageURL'];
				$blog_img_url = str_replace("https://","",$blog_img_url);
				$url_array = explode("/",$blog_img_url);                
            	$image_file = $url_array[count($url_array)-1]; 
            	$image_file_array = explode(".",$image_file);
            	$image_name = $image_file_array[0];
                $original_name = $image_file_array[0];			
				$image_name = str_replace("_s","",$image_name);

                $image_name = str_replace($original_name,$image_name,$article_data['blogImageURL']);
				$article_data['blogImageURL'] = $image_name;
			}
        	$new_article_list[] = $article_data;
        }
                
        $articles = $new_article_list;
		$data['articles'] = $articles;
		$data['articleIds'] = $articleIds;
		
		$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/ImportantDatesMailer", $data, true);
		
		return array('key'=>'ImportantDatesMailerWidget','data'=>$widgetHTML);
	}
}