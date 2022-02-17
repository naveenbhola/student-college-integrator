<?php
include_once('MailerWidgetAbstract.php');

class MNewsRegularArticleList extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}
	
	public function getData($userIds, $processingParams)
	{
		$templateId = $processingParams['templateId'];
		
		$newsletterParams = $this->mailerModel->getNewsletterParams($templateId);
		if(!trim($newsletterParams['articleIds'])) {
			return array();
		}
		$articleIds = explode(',',$newsletterParams['articleIds']);
		$articleIds = array_slice($articleIds, 0, 3);
		for($i=1; $i<count($articleIds); $i++){
			$regularArticleIds[] = $articleIds[$i];
		}
		
		if(!empty($regularArticleIds)) {
			if($templateId == 4757){
				$this->CI->load->model('blogs/sacontentmodel');
				$articleData = $this->CI->sacontentmodel->getMultipleContentUrlAndTitleForMailer($regularArticleIds,FALSE);
				
				$articles = array();
				foreach($regularArticleIds as $articleId) {
					foreach($articleData as $article) {
						if($article['content_id'] == $articleId) {
							$articles[] = $article;
							break;
						}
					}
				}
				
				$data = array();
				$new_article_list = array();
				foreach($articles as $article_data) {
					if(!empty($article_data['contentImageURL'])) {
						$blog_img_url = $article_data['contentImageURL'];
						$blog_img_url = str_replace("https://","",$blog_img_url);
						$url_array = explode("/",$blog_img_url);                
						$image_file = $url_array[count($url_array)-1]; 
						$image_file_array = explode(".",$image_file);
						$image_name = $image_file_array[0];
						$original_name = $image_file_array[0];			
						$image_name = str_replace("_s","",$image_name);
						$image_name = str_replace($original_name,$image_name,$article_data['contentImageURL']);
						$article_data['blogImageURL'] = $image_name;
					}
					$article_data['url'] = $article_data['contentURL'];
					$article_data['blogTitle'] = $article_data['strip_title'];
					$new_article_list[] = $article_data;
				}
			} else {
				$this->CI->load->model('article/articlemodel');
				$articleData = $this->CI->articlemodel->getArticlesData($regularArticleIds,FALSE);
				
				$articles = array();
				foreach($regularArticleIds as $articleId) {
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
			}
			$articles = $new_article_list;
			$data['articles'] = $articles;
			$widgetHTML = $this->CI->load->view('mailer/MailerWidgets/MNewsRegularArticleList',$data,TRUE);
			$widgetData = array();
			foreach($userIds as $userId) {
				$widgetData[$userId]['MNewsRegularArticleList'] = $widgetHTML;
			}
			return $widgetData;
		}
		else {
			return null;
		}
	}
}
