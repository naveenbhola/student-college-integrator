<?php

class MailerFactory
{
	public static function getWidgetObj($params)
	{
		$CI = &get_instance();
		
		$CI->load->model('mailer/mailermodel');
		$mailerModel = new MailerModel;
		
		$CI->load->model('blogs/articlemodel');
		$articleModel = new ArticleModel;
		
		$CI->load->model('user/userinfomodel');
		$userInfoModel = new UserInfoModel;
		
		$CI->load->model('Online/onlineparentmodel');
		$CI->load->model('Online/onlinemodel');
		$onlineFormModel = new OnlineModel;
		
		$CI->load->library('mailer/MailerWidgets/UserPreferenceManager');
		$userPreferenceManager = new UserPreferenceManager($userInfoModel);
		
		// $CI->load->library('recommendation/profile_based_collaborative_filter_lib');
		// $collaborativeFilter = new profile_based_collaborative_filter_lib;
		
		if ($params == 'nationalinstitutedetails' || $params == 'nationalRecommendation') {

			$CI->load->builder("nationalCourse/CourseBuilder");
			$builder = new CourseBuilder();
        	$courseRepository = $builder->getCourseRepository();

	    	$CI->load->builder("nationalInstitute/InstituteBuilder");
	    	$instituteBuilder = new InstituteBuilder();
	        $instituteRepository = $instituteBuilder->getInstituteRepository();

	    } else if ($params == 'institutedetails') {

			$CI->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$instituteRepository = $listingBuilder->getInstituteRepository();
			$courseRepository = $listingBuilder->getCourseRepository();

		}

		if ($params == 'tuser') {
			$CI->load->library('mailer/MailerWidgets/UserBasicDetailsWidget');
			return new UserBasicDetailsWidget($mailerModel);
		}
		/* else if ($params == 'course') {
			$CI->load->library('mailer/MailerWidgets/UserCourseDetailsWidget');
			return new UserCourseDetailsWidget($mailerModel);
		} */
        else if ($params == 'abroadCourse') {
			$CI->load->library('mailer/MailerWidgets/userAbroadCourseDetailsWidget');
			return new UserAbroadCourseDetailsWidget($mailerModel);
		}
        else if ($params == 'abroadSubCat') {
			$CI->load->library('mailer/MailerWidgets/userAbroadSubCatDetailsWidget');
			return new UserAbroadSubCatDetailsWidget($mailerModel);
		}
		else if ($params == 'country') {
			$CI->load->library('mailer/MailerWidgets/UserCountryDetailsWidget');
			return new UserCountryDetailsWidget($mailerModel);
		}
		/* else if ($params == 'category') {
			$CI->load->library('mailer/MailerWidgets/UserCategoryDetailsWidget');
			return new UserCategoryDetailsWidget($mailerModel);
		} */
        else if ($params == 'categoryAbroad') {
			$CI->load->library('mailer/MailerWidgets/UserCategoryDetailsWidgetSA');
			return new UserCategoryDetailsWidgetSA($mailerModel);
		}
		/* else if ($params == 'recommendation') {
			$CI->load->library('mailer/MailerWidgets/RecommendationWidget');
			return new RecommendationWidget($mailerModel,$collaborativeFilter);
		} */
		else if ($params == 'detailedRecommendation') {
			$CI->load->library('mailer/MailerWidgets/DetailedRecommendationWidget');
			return new DetailedRecommendationWidget($mailerModel,$collaborativeFilter);
		}
		else if ($params == 'nationalRecommendation') {
			$CI->load->library('mailer/MailerWidgets/NationalRecommendationWidget');
			return new NationalRecommendationWidget($mailerModel,$collaborativeFilter);
		}
		else if ($params == 'exampage_content_dates') {
			$CI->load->library('mailer/MailerWidgets/MEAAlertsWidget');
			return new MEAAlertsWidget($mailerModel);
		}
		/* else if ($params == 'article') {
			$CI->load->library('mailer/MailerWidgets/LatestNewsWidget');
			return new LatestNewsWidget($mailerModel,$articleModel,$userPreferenceManager);
		}
		else if ($params == 'mustread') {
			$CI->load->library('mailer/MailerWidgets/MustReadWidget');
			return new MustReadWidget($mailerModel,$articleModel,$userPreferenceManager);
		}
		else if ($params == 'alumniSpeak') {
			$CI->load->library('mailer/MailerWidgets/AlumniSpeakWidget');
			return new AlumniSpeakWidget($mailerModel,$instituteRepository);
		}
		else if ($params == 'completeprofile') {
			$CI->load->library('mailer/MailerWidgets/ProfileCompletionWidget');
			return new ProfileCompletionWidget($mailerModel);
		} */
		else if ($params == 'institutedetails') {
			$CI->load->library('mailer/MailerWidgets/InstituteDetailsWidget');
			return new InstituteDetailsWidget($mailerModel,$instituteRepository,$courseRepository);
		}
		else if ($params == 'nationalinstitutedetails') {
			$CI->load->library('mailer/MailerWidgets/NationalInstituteDetailsWidget');
			return new NationalInstituteDetailsWidget($mailerModel,$instituteRepository,$courseRepository);
		}
		/* else if ($params == 'online') {
			$CI->load->library('mailer/MailerWidgets/OnlineFormWidget');
			return new OnlineFormWidget($mailerModel,$onlineFormModel);
		}
		else if ($params == 'applicationform') {
			$CI->load->library('mailer/MailerWidgets/ApplicationFormWidget');
			return new ApplicationFormWidget($mailerModel,$instituteRepository,$courseRepository,$onlineFormModel);
		}
		else if ($params == 'comparison') {
			$CI->load->library('mailer/MailerWidgets/CompareMailerWidget');
			return new CompareMailerWidget($mailerModel,$instituteRepository,$courseRepository);
		} */
		else if ($params == 'NewsletterArticleList') {
			$CI->load->library('mailer/MailerWidgets/NewsletterArticleList');
			return new NewsletterArticleList($mailerModel);
		}
		else if ($params == 'NewsletterDiscussionList') {
			$CI->load->library('mailer/MailerWidgets/NewsletterDiscussionList');
			return new NewsletterDiscussionList($mailerModel);
		}
		/* else if ($params == 'NewsletterEventList') {
			$CI->load->library('mailer/MailerWidgets/NewsletterEventList');
			return new NewsletterEventList($mailerModel);
		} */
		else if ($params == 'NewsletterNotificationList') {
			$CI->load->library('mailer/MailerWidgets/NewsletterNotificationList');
			return new NewsletterNotificationList($mailerModel);
		}
		/* else if ($params == 'MNewsRegularArticleList') {
			$CI->load->library('mailer/MailerWidgets/MNewsRegularArticleList');
			return new MNewsRegularArticleList($mailerModel);
		}
		else if ($params == 'MNewsFeaturedArticleList') {
			$CI->load->library('mailer/MailerWidgets/MNewsFeaturedArticleList');
			return new MNewsFeaturedArticleList($mailerModel);
		} */
		else if ($params == 'SANewsletterArticleList') {
			$CI->load->library('mailer/MailerWidgets/SANewsletterArticleList');
			return new SANewsletterArticleList($mailerModel);
		}

	}

	public static function getMailerRepository()
	{
		$CI = &get_instance();
		/*
		 * Load dependencies for Institute Repository
		 */
		$CI->load->model('mailer/mailermodel');
		$model = $CI->mailermodel;
		/*
		 * Load the repository
		 */
		$CI->load->repository('MailerRepository','mailer');
		$mailerRepository = new MailerRepository($model);
		return $mailerRepository;
	}

	public static function getMailerTemplateRepository()
	{
		$CI = &get_instance();
		/*
		 * Load dependencies for Institute Repository
		 */
		$CI->load->model('mailer/mailermodel');
		$model = $CI->mailermodel;
		/*
		 * Load the reppository
		 */
		$CI->load->repository('MailerTemplateRepository','mailer');
		$mailerTemplateRepository = new MailerTemplateRepository($model);
		return $mailerTemplateRepository;
	}

	public static function getMailerCriteriaEvaluatorService()
	{
		$CI = &get_instance();
		/*
		 * Load dependencies for Institute Repository
		 */
		$CI->load->model('mailer/mailermodel');
		$model = $CI->mailermodel;
		/*
		 * Load the reppository
		 */
		$CI->load->service('MailerCriteriaEvaluatorService','mailer');
		$mailerCriteriaEvaluatorService = new MailerCriteriaEvaluatorService($model);
		return $mailerCriteriaEvaluatorService;
	}

	public static function getMailerProcessor(Mailer $mailer,$params = array())
	{
		$CI = &get_instance();
		$templateBuilder = self::getTemplateBuilder();
		$parentMailerId = $mailer->getParentMailerId();
		if($mailer->isCSV() && empty($parentMailerId)) {
			$CI->load->library('mailer/MailerProcessors/MailerProcessorCSV');
			return new MailerProcessorCSV($mailer,$templateBuilder);
		}
		else {
			$CI->load->library('mailer/MailerProcessors/MailerProcessorUserSet');
			
			$mailerCriteriaEvaluatorService = self::getMailerCriteriaEvaluatorService();
			$mailerCriteriaEvaluatorService->setExtraCriteria($params);
			
			$CI->load->library('mailer/ProductMailerEventTriggers');
			$productMailerEventTriggers = new ProductMailerEventTriggers();
			
			return new MailerProcessorUserSet($mailer,$templateBuilder,$mailerCriteriaEvaluatorService,$productMailerEventTriggers);
		}
	}

	public static function getTemplateBuilder()
	{
		$CI = &get_instance();
		
		$CI->load->service('MailerTemplateDataValidatorService','mailer');
		$CI->load->service('MailSubjectGeneratorService','mailer');
		$CI->load->service('MailerWidgetPostProcessorService','mailer');
		$CI->load->model('mailer/mailermodel');
		
		$model = $CI->mailermodel;
		$mailSubjectGeneratorService = new MailSubjectGeneratorService($model);

		$CI->load->library('mailer/TemplateBuilder');
		return new TemplateBuilder(new MailerTemplateDataValidatorService(),$mailSubjectGeneratorService,new MailerWidgetPostProcessorService);
	}
}
