<?php
	/**
	 * Article crons
	 */
	class ArticleCron extends MX_Controller
	{
		
		function __construct(){
			parent::validateCron();
		}

		/**
		* below function is used for updating msgcount with sum comment and reply count on all artciles in messageTable
		*/

		public function updateArticleDiscussionCount(){
			$articlenewmodel = $this->load->model('articlenewmodel');
			$result = $articlenewmodel->getDiscucssionIdsFromBlogTable();

			$threadIds = array();


			foreach ($result as $key => $value) {
				if(!empty($value)){
					$threadIds[] = $value;
				}
			}
			if(!empty($threadIds))
			{
				$chunks = array_chunk($threadIds, 500);
				foreach ($chunks as $key => $value) {
					$anamodel = $this->load->model('messageBoard/anamodel');
					$disCommentCount = $anamodel->getAllChildCountBasedOnThreadId($value);
					$updatedFlag = $anamodel->updateChildCountBasedOnThreadId($disCommentCount);

					if($updatedFlag === false){
						error_log("updateArticleDiscussionCount child count failed for batch:".($key+1));
					}
				}	
			}
			
		}
	}
?>