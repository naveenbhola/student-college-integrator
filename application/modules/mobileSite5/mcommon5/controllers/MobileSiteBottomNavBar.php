<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MobileSiteBottomNavBar extends ShikshaMobileWebSite_Controller
{
	function __construct()
	{
	    parent::__construct();
	}

	function bottomNavBar($pageType = 'homepage', $subCatId = '')
	{
		$displayData = array();
		$displayData['pageType'] = $pageType;
		$displayData['subCategoryId'] = $subCatId;
		
		$articleCountOnSubCategory = 0;
		$minimunArticle = 5;
		$loadView = true;
		
		$config = array(
				'23' => array('colleges','rankings','exams','career-compass','examCalendarMba','collegeReviewsMBA','articles'),
				'56' => array('colleges','predictor','rankPredictor','rankings','exams','examCalendarEngg','collegeReviewsEngg','articles'),
				'25' => array('colleges','rankings','articles'),
				'26' => array('colleges','rankings','articles'),
				'33' => array('colleges','rankings','articles'),
				'other' => array('colleges','articles'),
				'none' => array('colleges','articles')
				);
		
		$labels = array('colleges'=>'Colleges',
				'predictor'=>'College Predictor',
				'rankPredictor'=>'Rank Predictor',
				'rankings'=>'Ranking',
				'exams'=>'Entrance Exams',
				'career-compass'=>'CAREER COMPASS',
				'articles'=>'News & Articles',
				'examCalendarEngg'=>'Engineering Exam Calendar',
				'examCalendarMba'=>'MBA Exam Calendar',
				'collegeReviewsMBA'=>'MBA College Reviews',
				'collegeReviewsEngg'=>'Engineering College Reviews');
		
		$imageClass = array('colleges'=>'icon-colleges',
				'predictor'=>'icon-college-predictor',
				'rankPredictor'=>'icon-rank-predictor',
				'rankings'=>'icon-ranking',
				'exams'=>'icon-entrance-exam',
				'career-compass'=>'icon-career-compass',
				'articles'=>'icon-news-article');
		
		$pagesArray = array('colleges'=>'categoryPage',
				    'predictor'=>'collegePredictorPage',
				    'rankPredictor'=>'rankPredictorPage',
				    'rankings'=>'rankingPage',
				    'exams'=>'examPage',
				    'articles'=>'articlePage',
				    'examCalendarEngg'=>'examCalendarPage',
				    'examCalendarMba'=>'examCalendarPage');
		
 		$trackingCodes = array('colleges'=>'Tab_Colleges',
				    'predictor'=>'Tab_CollegePredictor',
				    'rankPredictor'=>'Tab_RankPredictor',
				    'rankings'=>'Tab_Rankings',
				    'exams'=>'Tab_Exams',
				    'articles'=>'Tab_Articles',
				    'career-compass'=>'MOBILE5_TAB_MENU_CAREER_COMPASS',
				    'examCalendarEngg'=>'Tab_examCalendar_Engineering',
				    'examCalendarMba'=>'Tab_examCalendar_MBA',
				    'collegeReviewsMBA'=>'Tab_MBA_Colleges_Reviews',
					'collegeReviewsEngg'=>'Tab_Engineering_Colleges_Reviews');
		
		$articleURL = SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList';
		if($subCatId != '')
		{
			$articleURL = SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList?category='.$subCatId;
			$this->load->model('articlemodel');
			$infoArr = $this->articlemodel->getArticlesCountForSubcategories(array($subCatId)); // API call for count
			$articleCountOnSubCategory = $infoArr[$subCatId];
		}
		
		$rankingURL = array('23' => SHIKSHA_HOME . '/' . trim('top-mba-colleges-in-india-rankingpage-2-2-0-0-0', '/'),
				    '25' => SHIKSHA_HOME . '/' . trim('top-executive-mba-colleges-in-india-rankingpage-18-2-0-0-0', '/'),
				    '26' => SHIKSHA_HOME . '/' . trim('top-part-time-mba-colleges-in-india-rankingpage-26-2-0-0-0', '/'),
				    '33' => SHIKSHA_HOME . '/' . trim('top-llb-colleges-in-india-rankingpage-56-2-0-0-0', '/'),
				    '56' => SHIKSHA_HOME . '/' . trim('top-engineering-colleges-in-india-rankingpage-44-2-0-0-0', '/')
				    );
		
		$finalTabs = array();
		if($subCatId != '') //onclick - appropriate layer or goto specified links
		{
			$this->load->library('categoryList/CategoryPageRequest');
			$requestURL = new CategoryPageRequest();
			$location_id = isset($_COOKIE['selectedLocation'])?$_COOKIE['selectedLocation']:'';
			if(!empty($location_id))
			{
				$requestURL->setData(array('subCategoryId'=>$subCatId,'countryId' => '2','cityId' => $location_id));
			}
			else
			{
				$requestURL->setData(array('subCategoryId'=>$subCatId,'countryId' => '2'));
			}
			$categoryPageURL = $requestURL->getURL();
			
			switch($subCatId)
			{
				case 23: // Full time MBA
					$finalTabs[$config[$subCatId][0]] = array('link',$categoryPageURL);
					$finalTabs[$config[$subCatId][1]] = array('link',$rankingURL[$subCatId]);
					$finalTabs[$config[$subCatId][2]] = array('layer','#mbaEntranceExamHamburgerDiv');
					$finalTabs[$config[$subCatId][3]] = array('link',SHIKSHA_HOME.'/mba/resources/mba-alumni-data');
					$finalTabs[$config[$subCatId][4]] = array('link',SHIKSHA_MBA_CALENDAR);
					$finalTabs[$config[$subCatId][5]] = array('link',SHIKSHA_HOME.'/mba/resources/college-reviews');
					if($articleCountOnSubCategory >= $minimunArticle){
						$finalTabs[$config[$subCatId][6]] = array('link',$articleURL);
					}
				break;
			
				case 56: // BE/Bech
					$finalTabs[$config[$subCatId][0]] = array('link',$categoryPageURL);
					$finalTabs[$config[$subCatId][1]] = array('layer','#collegePredictorHamburgerDiv');
					$finalTabs[$config[$subCatId][2]] = array('layer','#rankPredictorHamburgerDiv');
					$finalTabs[$config[$subCatId][3]] = array('link',$rankingURL[$subCatId]);
					$finalTabs[$config[$subCatId][4]] = array('layer','#engineeringExamHamburgerDiv');
					$finalTabs[$config[$subCatId][5]] = array('link',SHIKSHA_HOME.'/engineering-exams-dates');
					$finalTabs[$config[$subCatId][6]] = array('link',SHIKSHA_HOME.'/engineering-colleges-reviews-cr');
					if($articleCountOnSubCategory >= $minimunArticle){
						$finalTabs[$config[$subCatId][7]] = array('link',$articleURL);
					}
				break;
			
				case 25: // Executive MBA
				case 26: // Part time MBA
				case 33: // Law
					$finalTabs[$config[$subCatId][0]] = array('link',$categoryPageURL);
					$finalTabs[$config[$subCatId][1]] = array('link',$rankingURL[$subCatId]);
					if($articleCountOnSubCategory >= $minimunArticle){
						$finalTabs[$config[$subCatId][2]] = array('link',$articleURL);
					}
				break;
			
				default: // Case for all other sub-categories
					$subCatId = 'other';
					$finalTabs[$config[$subCatId][0]] = array('link',$categoryPageURL);
					if($articleCountOnSubCategory >= $minimunArticle){
						$finalTabs[$config[$subCatId][1]] = array('link',$articleURL);
					}
					else
					{
						$loadView = false;
					}
				break;
			}
		}
		else //onclick - throw the sub-category layer when NO sub-category is provided
		{
			$subCatId = 'none';
			$finalTabs[$config[$subCatId][0]] = array('layer','#subcategoryDiv');
			$finalTabs[$config[$subCatId][1]] = array('link',$articleURL);
		}
		$displayData['finalTabs'] = $finalTabs;
		$displayData['labels'] = $labels;
		$displayData['imageClass'] = $imageClass;
		$displayData['pagesArray'] = $pagesArray;
		$displayData['trackingCodes'] = $trackingCodes;
		
		if($loadView)
		{
			$this->load->view('bottomNavBar',$displayData);
		}
	}
}
?>
