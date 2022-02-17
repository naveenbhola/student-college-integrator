<?php 

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: ankurg $:  Author of last commit
$Date: 2011/08/16 09:29:49 $:  Date of last commit

$Id: Seo.php,v 1.205 2011/08/16 09:29:49 ankurg Exp $:

*/

set_time_limit(0);
ini_set('memory_limit', '1800M');

class Seo extends MX_Controller {
	private $abroadSiteMapTypes = array('examAcceptingAbroadCategoryPage','universitiesInCountryPage');
    function createShikshaSitemap($types,$noOfDays = 3,$typeSitemap="inc"){
	    //By default, this function will be called for Incremental Sitemap. If it is Monday or Thursday, we will call again call it for complete Sitemap            
	    $this->validateCron();
	    $appId = 12;
	    $this->load->library('Seo_client');
	    $this->load->library('alerts_client');
	    $Seo_client = new Seo_client();
	    $typeArr = explode(":",$types);
	    $date = date("Y-m-d");

        error_log("============================================================================\n",3,"/tmp/sitemapCreation.log");
	    error_log("Sitemap Generation starts for $typeSitemap sitemap at ".date("Y-m-d:H:i:s")."\n",3,"/tmp/sitemapCreation.log");
            
	    if (SHIKSHA_HOME == 'https://www.shiksha.com')
	    {
			$shikshaHomePath = "/var/www/html/shiksha/";
			$perlPath = $shikshaHomePath."application/config/";
			$enableGoogleUpload = true;
	    }
	    else if(SHIKSHA_HOME == 'https://shikshatest02.infoedge.com'){
			$shikshaHomePath = "/var/www/html/shiksha/";
			$perlPath = $shikshaHomePath."application/config/";
			$enableGoogleUpload = false;
	    }	    
	    else{
			$shikshaHomePath = "/var/www/html/shiksha/";
			$perlPath = $shikshaHomePath."application/config/";
			$enableGoogleUpload = false;
	    }
	    foreach($typeArr as $type)
	    {
                    if($type != 'categoryPages' && $type != 'courseHomePages')
					{
                            $start = 0; $count = 0;
                            //We need to fetch the URLs in small amount so that the system memory doesn't get choked.
                            do{
                                $start = $start+$count;
                                $count = 50000;
                                  
								//Get the URL list for each of the type
								if($type == 'listing')
								{
									$urls1                      = $Seo_client->getURLForSitemap($appId,'institute',$start,$count,$noOfDays,$typeSitemap);
									$urls2                      = $Seo_client->getURLForSitemap($appId,'abroadinstitute',$start,$count,$noOfDays,$typeSitemap);
									$urls_univeristies          = $Seo_client->getURLForSitemap($appId,'university',$start,$count,$noOfDays,$typeSitemap);
									$urls_national_univeristies = $Seo_client->getURLForSitemap($appId,'national_university',$start,$count,$noOfDays,$typeSitemap);
									$urls_national_courses      = $Seo_client->getURLForSitemap($appId,'national_course',$start,$count,$noOfDays,$typeSitemap);
									$urls_all_content_pages     = $Seo_client->getURLForSitemap($appId,'all_content_pages',$start,$count,$noOfDays,$typeSitemap);
									$urls_abroad_courses        = $Seo_client->getURLForSitemap($appId,'abroad_course',$start,$count,$noOfDays,$typeSitemap);
									//$urls                     = $Seo_client->getURLForSitemap($appId,'course',$start,$count,$noOfDays,$typeSitemap);
									
									$urls1["URL"]               = array_merge($urls2["URL"], $urls1["URL"]);
									$urls1["URL"]               = array_merge($urls_univeristies["URL"], $urls1["URL"]);
									$urls1["URL"]               = array_merge($urls_national_univeristies["URL"], $urls1["URL"]);
									$urls1["URL"]               = array_merge($urls_national_courses["URL"], $urls1["URL"]);
									$urls1["URL"]               = array_merge($urls_all_content_pages["URL"],$urls1["URL"]);
									$urls1["URL"]               = array_merge($urls_abroad_courses["URL"],$urls1["URL"]);
								}
								else if($type=='topSearchKeyword' && $typeSitemap!='inc')
								{
									exec("curl ".SHIKSHA_HOME."/search/generateSeoURLForTopKeywordSearch > /tmp/search_topkeywordSearch_url".$date." ");
									$urls = array();
								}else if(in_array($type, $this->abroadSiteMapTypes))
								{
									$this->AbroadCategoryPageSiteMapLib = $this->load->library('categoryList/abroadCategoryPageSiteMapLib');
									$urls['URL'] = $this->AbroadCategoryPageSiteMapLib->getAbroadURLForSiteMap($type);
								}elseif($type == "ranking"){
									$this->load->builder('rankingV2/RankingPageBuilder');
									$builder = new RankingPageBuilder();
									$rankingURLManager = $builder->getURLManager();
									$urls = $rankingURLManager->getAllRankingPageUrls();
								}
								elseif($type == "saStaticSearchUrl"){
									$searchModel  = $this->load->model('SASearch/sasearchmodel');	
									$searchStaticUrls = $searchModel->getAllSearchStaticUrl();
									$urls = array();
									foreach ($searchStaticUrls as $key => $value) {
										$urls[] = SHIKSHA_STUDYABROAD_HOME.$value['url'];
									}									
								}
								else
								{
									$urls = $Seo_client->getURLForSitemap($appId,$type,$start,$count,$noOfDays,$typeSitemap);
								}
								//Next save it as a temp file with newline after each URL
								if($type!='topSearchKeyword')
								  {
									  switch ($type)
									  {
										  case "listing" 		: $this->createTempFile("/tmp/listing_url".$date.$typeSitemap,$urls1,$urls); break;
										  case "question" 		: $this->createTempFile("/tmp/ask_url".$date.$typeSitemap,$urls); break;
										  case "event" 		: $this->createTempFile("/tmp/event_url".$date.$typeSitemap,$urls); break;
										  case "blog" 		: $this->createTempFile("/tmp/blog_url".$date.$typeSitemap,$urls); break;
										  case "saarticles"		: $this->createTempFile("/tmp/saarticles_url".$date.$typeSitemap,$urls); break;
										  case "news" 		: $this->createTempFile("/tmp/news_url".$date.$typeSitemap,$urls); break;
										  case "user" 		: $this->createTempFile("/tmp/user_url".$date.$typeSitemap,$urls); break;
										  case "career" 		: $this->createTempFile("/tmp/career_url".$date.$typeSitemap,$urls); break;
										  case "collegepredictor" 	: $this->createTempFile("/tmp/collegepredictor_url".$date.$typeSitemap,$urls); break;
										  case "rankpredictor"        : $this->createTempFile("/tmp/rankpredictor_url".$date.$typeSitemap,$urls); break;
										  case "comparepage"		: $this->createTempFile("/tmp/comparepage_url".$date.$typeSitemap,$urls); break;
										  case "abroadRankingPage"	: $this->createTempFile("/tmp/abroadRankingPage_url".$date.$typeSitemap,$urls); unset($urls);break;
										  case "exampages"		: $this->createTempFile("/tmp/examPages_url".$date.$typeSitemap,$urls); unset($urls);break;
										  case "abroadExamPage"	: $this->createTempFile("/tmp/abroadExamPage_url".$date.$typeSitemap,$urls); unset($urls);break;
										  case "applyContent"	    : $this->createTempFile("/tmp/applyContent_url".$date.$typeSitemap,$urls); unset($urls);break;
										  case "SAscholarships"	    : $this->createTempFile("/tmp/SAscholarships_url".$date.$typeSitemap,$urls); unset($urls);break;
										  case "countryHome"		: $this->createTempFile("/tmp/countryHome_url".$date.$typeSitemap,$urls); unset($urls);break;
										  case "collegereview"	: $this->createTempFile("/tmp/collegereview_url".$date.$typeSitemap,$urls); break;
										  case "careercompass"	: $this->createTempFile("/tmp/careercompasss_url".$date.$typeSitemap,$urls); break;
										  case "campusconnect"	: $this->createTempFile("/tmp/campusconnect_url".$date.$typeSitemap,$urls); break;
										  case "examAcceptingAbroadCategoryPage"	: $this->createTempFile("/tmp/examAcceptingAbroadCategoryPage_url".$date.$typeSitemap,$urls);unset($urls); break;
										  case "universitiesInCountryPage" : 	$this->createTempFile("/tmp/universitiesInCountryPage_url".$date.$typeSitemap,$urls);unset($urls); break;
										  case 'ranking': $this->createTempFile("/tmp/ranking_url".$date.$typeSitemap,array('URL'=>$urls));break;
										  case 'saStaticSearchUrl': $this->createTempFile("/tmp/static_search_url".$date.$typeSitemap,array('URL'=>$urls));break;
									  }
								}
                            }while(is_array($urls)  && count($urls['URL'])>0 );
                    }
					else
					{  // Generate URLs for Category Pages..                     
							if($type == 'categoryPages') {
								$tempFileURL = "/tmp/categoryPages_url";
								$fileAndDomainInfo = $this->getCategoryPagesURLs($tempFileURL, $date, $typeSitemap);
							} else if($type == 'courseHomePages') {
								$tempFileURL = "/tmp/courseHomePages_url";                    	    	
								$coursepagefileAndDomainInfo = $this->getCoursePagesURLs($tempFileURL, $date, $typeSitemap);
							}
		
					}   // End of if($type != 'categoryPages').
                
                    error_log("\nTemp file generated for '".strtoupper($type)."' at ".date("Y-m-d:H:i:s"),3,"/tmp/sitemapCreation.log");
		
                    //After saving the file, call the perl script to create the Sitemap xml from this file
                    if($typeSitemap=='inc')
					{
                        switch ($type)
						{
							case "listing" : exec("perl ".$perlPath."createSiteMap.pl /tmp/listing_url".$date.$typeSitemap." ".$shikshaHomePath."www_listing_SiteMap_inc 1.0 weekly"); break;
							case "question" : exec("perl ".$perlPath."createSiteMap.pl /tmp/ask_url".$date.$typeSitemap." ".$shikshaHomePath."ask_SiteMap_inc 1.0 always"); break;
							case "event" : exec("perl ".$perlPath."createSiteMap.pl /tmp/event_url".$date.$typeSitemap." ".$shikshaHomePath."events_SiteMap_inc 1.0 weekly"); break;
							case "blog" : exec("perl ".$perlPath."createSiteMap.pl /tmp/blog_url".$date.$typeSitemap." ".$shikshaHomePath."www_blog_SiteMap_inc 1.0 always"); break;
							case "saarticles" : exec("perl ".$perlPath."createSiteMap.pl /tmp/saarticles_url".$date.$typeSitemap." ".$shikshaHomePath."www_saarticles_SiteMap_inc 1.0 weekly"); break;
							case "news" : exec("perl ".$perlPath."createSiteMapForNews.pl /tmp/news_url".$date.$typeSitemap." ".$shikshaHomePath."www_news_SiteMap_inc 1.0 always"); break;
							case "user" : exec("perl ".$perlPath."createSiteMap.pl /tmp/user_url".$date.$typeSitemap." ".$shikshaHomePath."www_user_SiteMap_inc 1.0 weekly"); break;
							case "career" : exec("perl ".$perlPath."createSiteMap.pl /tmp/career_url".$date.$typeSitemap." ".$shikshaHomePath."www_career_SiteMap_inc 1.0 weekly"); break;
							case "applyContent"    : exec("perl ".$perlPath."createSiteMap.pl /tmp/applyContent_url".$date.$typeSitemap." ".$shikshaHomePath."www_applyContent_SiteMap_inc 1.0 weekly"); break;
							case "SAscholarships"    : exec("perl ".$perlPath."createSiteMap.pl /tmp/SAscholarships_url".$date.$typeSitemap." ".$shikshaHomePath."www_SAscholarships_SiteMap_inc 1.0 weekly"); break;
							// case "ranking"    : exec("perl ".$perlPath."createSiteMap.pl /tmp/ranking_url".$date.$typeSitemap." ".$shikshaHomePath."www_applyContent_SiteMap_inc 1.0 weekly"); break;
						}
                    }else{
                          switch ($type){
                                case "listing" : exec("perl ".$perlPath."createSiteMap.pl /tmp/listing_url".$date.$typeSitemap." ".$shikshaHomePath."www_listing_SiteMap_f"); break;
                                case "question" : exec("perl ".$perlPath."createSiteMap.pl /tmp/ask_url".$date.$typeSitemap." ".$shikshaHomePath."ask_SiteMap_f 0.5 always"); break;
                                case "event" : exec("perl ".$perlPath."createSiteMap.pl /tmp/event_url".$date.$typeSitemap." ".$shikshaHomePath."events_SiteMap_f"); break;
                                case "blog" : exec("perl ".$perlPath."createSiteMap.pl /tmp/blog_url".$date.$typeSitemap." ".$shikshaHomePath."www_blog_SiteMap_f 0.5 always"); break;
								case "saarticles" : exec("perl ".$perlPath."createSiteMap.pl /tmp/saarticles_url".$date.$typeSitemap." ".$shikshaHomePath."www_saarticles_SiteMap_f"); break;
                                case "news" : exec("perl ".$perlPath."createSiteMapForNews.pl /tmp/news_url".$date.$typeSitemap." ".$shikshaHomePath."www_news_SiteMap_f 0.5 always"); break;
                                case "user" : exec("perl ".$perlPath."createSiteMap.pl /tmp/user_url".$date.$typeSitemap." ".$shikshaHomePath."www_user_SiteMap_f"); break;
                                case "career" : exec("perl ".$perlPath."createSiteMap.pl /tmp/career_url".$date.$typeSitemap." ".$shikshaHomePath."www_career_SiteMap_f"); break;

                                case 'categoryPages' :

                                    $totalTempFilesforCatPages = count($fileAndDomainInfo);

                                    for($i = 0; $i < $totalTempFilesforCatPages; $i++) {
                                        exec("perl ".$perlPath."createSiteMap.pl ".$fileAndDomainInfo[$i]['filename']." ".$shikshaHomePath."www_".$fileAndDomainInfo[$i]['domain']."_categoryPages_SiteMap_f");
                                        error_log("\n\t Executing the file :".$fileAndDomainInfo[$i]['filename']." for domain: ".$fileAndDomainInfo[$i]['domain'],3,"/tmp/sitemapCreation.log");
                                        sleep(10);
                                    }

                                    break;
                                    
                                case 'courseHomePages' :                                
                                	$count_course_pages = count($coursepagefileAndDomainInfo);                                	
                                	for ($i=0;$i<$count_course_pages;$i++) {
                                		exec("perl ".$perlPath."createSiteMap.pl ".$coursepagefileAndDomainInfo[$i]['filename']." ".$shikshaHomePath."www_".$coursepagefileAndDomainInfo[$i]['domain']."_courseHomePages_SiteMap_f");
                                		error_log("\n\t Executing the file :".$coursepagefileAndDomainInfo[$i]['filename']." for domain: ".$coursepagefileAndDomainInfo[$i]['domain'],3,"/tmp/sitemapCreation.log");
                                		sleep(10);
                                	}
								break;
								case "collegepredictor" :  exec("perl ".$perlPath."createSiteMap.pl /tmp/collegepredictor_url".$date.$typeSitemap." ".$shikshaHomePath."www_collegepredictor_SiteMap_f"); break;
								case "rankpredictor" :  exec("perl ".$perlPath."createSiteMap.pl /tmp/rankpredictor_url".$date.$typeSitemap." ".$shikshaHomePath."www_rankpredictor_SiteMap_f"); break;
								case "comparepage" :  exec("perl ".$perlPath."createSiteMap.pl /tmp/comparepage_url".$date.$typeSitemap." ".$shikshaHomePath."www_comparepage_SiteMap_f"); break;
								case "abroadRankingPage" : exec("perl ".$perlPath."createSiteMap.pl /tmp/abroadRankingPage_url".$date.$typeSitemap." ".$shikshaHomePath."www_abroadRankingPage_SiteMap_f"); break;
								case "abroadExamPage" : exec("perl ".$perlPath."createSiteMap.pl /tmp/abroadExamPage_url".$date.$typeSitemap." ".$shikshaHomePath."www_abroadExamPage_SiteMap_f"); break;
								case "exampages" : exec("perl ".$perlPath."createSiteMap.pl /tmp/examPages_url".$date.$typeSitemap." ".$shikshaHomePath."www_examPages_SiteMap_f 1.0 always"); break;
								case "applyContent"	: exec("perl ".$perlPath."createSiteMap.pl /tmp/applyContent_url".$date.$typeSitemap." ".$shikshaHomePath."www_applyContent_SiteMap_f"); break;
								case "SAscholarships"	: exec("perl ".$perlPath."createSiteMap.pl /tmp/SAscholarships_url".$date.$typeSitemap." ".$shikshaHomePath."www_SAscholarships_SiteMap_f"); break;
								case "countryHome"	: exec("perl ".$perlPath."createSiteMap.pl /tmp/countryHome_url".$date.$typeSitemap." ".$shikshaHomePath."www_countryHome_SiteMap_f"); break;
								case "collegereview" :  exec("perl ".$perlPath."createSiteMap.pl /tmp/collegereview_url".$date.$typeSitemap." ".$shikshaHomePath."www_collegereview_SiteMap_f"); break;
								case "careercompass" :  exec("perl ".$perlPath."createSiteMap.pl /tmp/careercompass_url".$date.$typeSitemap." ".$shikshaHomePath."www_careercompass_SiteMap_f"); break;
								case "campusconnect" :  exec("perl ".$perlPath."createSiteMap.pl /tmp/campusconnect_url".$date.$typeSitemap." ".$shikshaHomePath."www_campusconnect_SiteMap_f"); break;
								case "universitiesInCountryPage" :  exec("perl ".$perlPath."createSiteMap.pl /tmp/universitiesInCountryPage_url".$date.$typeSitemap." ".$shikshaHomePath."www_universitiesInCountryPage_SiteMap_f"); break;
								case "examAcceptingAbroadCategoryPage" : exec("perl ".$perlPath."createSiteMap.pl /tmp/examAcceptingAbroadCategoryPage_url".$date.$typeSitemap." ".$shikshaHomePath."www_examAcceptingAbroadCategoryPage_SiteMap_f"); break;
								case "ranking" : exec("perl ".$perlPath."createSiteMap.pl /tmp/ranking_url".$date.$typeSitemap." ".$shikshaHomePath."www_ranking_SiteMap_f"); break;
								case "saStaticSearchUrl" : exec("perl ".$perlPath."createSiteMap.pl /tmp/static_search_url".$date.$typeSitemap." ".$shikshaHomePath."www_static_search_SiteMap_f"); break;
                          }
                    }
                    error_log("\nXML file generated successsfully for ".strtoupper($type)." at ".date("Y-m-d:H:i:s")."\n",3,"/tmp/sitemapCreation.log");

	    }   // End of foreach($typeArr as $type).
	     

	    $sitemapGeneratedForEntities = str_replace(":", ", ", $types);
	    if($typeSitemap=='inc'){
			//After all the sitemap XML are generated, gzip them
			exec("gzip -f ".$shikshaHomePath."*SiteMap_inc*.xml");
  
			//Create a Master sitemap containing all the Sitemap links
			exec('ls '.$shikshaHomePath.'*_inc*.gz |awk -F"_" -vvar="" \'BEGIN{var="perl '.$perlPath.'createSiteMapIndex.pl";} {var = var" "$0" "$1;}END{ system(var" '.$shikshaHomePath.'sitemap_index_inc.xml");}\'');
  
			//After creating the Sitemap, upload the Master sitemap on Google by making a CURL call
			//This will be done in case of Incremental sitemap only and not in case of SItewide sitemap
			if($enableGoogleUpload){
			  $url = "http://www.google.com/webmasters/tools/ping?sitemap=".urlencode(SHIKSHA_HOME."/sitemap_index_inc.xml");
			  $process = curl_init($url); 
			  curl_setopt($process, CURLOPT_TIMEOUT, 30); 
			  curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
			  curl_setopt($process, CURLOPT_POST, 1); 
			  $response = curl_exec($process); 
			  curl_close($process);
			}
  
			//After Uploading the Sitemap, send a mailer to Product and Tech in case of Incremental sitemap			
			$subject = 'New Incremental sitemap generated on Shiksha';
			$content = 'Hi team,<br/><br/>A new sitemap of Shiksha has been generated for <b>'.$sitemapGeneratedForEntities.'</b> at the following URL:<br/>'.SHIKSHA_HOME.'/sitemap_index_inc.xml<br/><br/>It has also been uploaded on the Google webmaster.<br/><br/>-Shiksha Tech';
	    }
	    else
		{
		  //After all the sitemap XML are generated, gzip them
		  exec("gzip -f ".$shikshaHomePath."*SiteMap_f*.xml");
		  //Create a Master sitemap containing all the Sitemap links
		  exec('ls '.$shikshaHomePath.'*_f*.gz |awk -F"_" -vvar="" \'BEGIN{var="perl '.$perlPath.'createSiteMapIndex.pl";} {var = var" "$0" "$1;}END{ system(var" '.$shikshaHomePath.'sitemap_index.xml");}\'');

			//After creating the Sitemap, upload the Master sitemap on Google by making a CURL call
			if($enableGoogleUpload){
			  $url = "http://www.google.com/webmasters/tools/ping?sitemap=".urlencode(SHIKSHA_HOME."/sitemap_index.xml");
			  $process = curl_init($url); 
			  curl_setopt($process, CURLOPT_TIMEOUT, 30); 
			  curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
			  curl_setopt($process, CURLOPT_POST, 1); 
			  $response = curl_exec($process); 
			  curl_close($process);
			}

		  //In case of Sitewide sitemap, send a mailer to SEO team, Product and tech, so that SEO team can upload it on Google		  
		  $subject = 'New Full sitemap generated on Shiksha';
		  $content = 'Hi Rahul,<br/><br/>A new sitemap of Shiksha has been generated for <b>'.$sitemapGeneratedForEntities.'</b> at the following URL:<br/>'.SHIKSHA_HOME.'/sitemap_index.xml<br/><br/>It has also been uploaded on the Google webmaster.<br/><br/>-Shiksha Tech';
	    }
		
		$userEmail = 'rahul.saraswat@shiksha.com';		  
		$fromAddress="noreply@shiksha.com";		
		
		$AlertClientObj = new Alerts_client();
		$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$content,"html", "0000-00-00 00:00:00", 'n', array(), 'satech@shiksha.com', 'listingstech@shiksha.com');
		$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,'ankur.gupta@shiksha.com',$subject,$content,"html", "0000-00-00 00:00:00", 'n', array(), 'ugctech@shiksha.com', 'teamldb@shiksha.com');
		$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,'anisha.jain@naukri.com',$subject,$content,"html", "0000-00-00 00:00:00", 'n', array(), 'abhinav.k@shiksha.com', 'romil.goel@shiksha.com');
		error_log("\nMailers are sent\n",3,"/tmp/sitemapCreation.log");
		
		
	    error_log("\nSitemap generation Ends at ".date("Y-m-d:H:i:s")."\n",3,"/tmp/sitemapCreation.log");
            error_log("============================================================================\n",3,"/tmp/sitemapCreation.log");
  
    }

    function createShikshaSitemapForNews($noOfDays = 3,$typeSitemap="inc"){
error_log("createShikshaSitemapForNews===");
	    //By default, this function will be called for Incremental Sitemap. If it is Monday or Thursday, we will call again call it for complete Sitemap            
	    $appId = 12;
	    $this->load->library('Seo_client');
	    $this->load->library('alerts_client');
	    $Seo_client = new Seo_client();
	    $typeArr = explode(":",$types);
	    $date = date("Y-m-d");

            error_log("============================================================================\n",3,"/tmp/newsSitemapCreation.log");
	    error_log("Sitemap Generation starts for $typeSitemap sitemap at ".date("Y-m-d:H:i:s")."\n",3,"/tmp/newsSitemapCreation.log");
            
	    if (SHIKSHA_HOME == 'https://www.shiksha.com')
	    {
		$shikshaHomePath = "/var/www/html/shiksha/";
		$perlPath = $shikshaHomePath."application/config/";
		$enableGoogleUpload = true;
	    }
	    else if(SHIKSHA_HOME == 'https://shikshatest02.infoedge.com'){
		$shikshaHomePath = "/var/www/html/shiksha/";
		$perlPath = $shikshaHomePath."application/config/";
		$enableGoogleUpload = false;
	    }	    
	    else{
		$shikshaHomePath = "/var/www/html/shiksha/";
		$perlPath = $shikshaHomePath."application/config/";
		$enableGoogleUpload = false;
	    
	    }
	    $date = date("Y-m-d-h-i-s");
	    $start = 0;
	    $count = 50000;
	    $urls = $Seo_client->getURLForSitemap($appId,'news',$start,$count,$noOfDays,'inc');
	    $this->createTempFileForNews("/tmp/news_url".$date.$typeSitemap,$urls);
	    exec("perl ".$perlPath."createSiteMapForNews.pl /tmp/news_url".$date.$typeSitemap." ".$shikshaHomePath."sitemap_news_inc 1.0 always");
	    if($enableGoogleUpload){
		$url = "http://www.google.com/webmasters/tools/ping?sitemap=".urlencode(SHIKSHA_HOME."/sitemap_news_inc1.xml");
		$process = curl_init($url); 
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($process, CURLOPT_POST, 1); 
		$response = curl_exec($process); 
		curl_close($process);
	  }
	 $fromAddress="noreply@shiksha.com";
         $subject = 'New incremental sitemap for News is generated on Shiksha';
         $userEmail = 'vivek.ahlawat@naukri.com';
         $content = 'Hi Vivek,<br/><br/>A new News sitemap of Shiksha has been generated at the following URL:<br/>'.SHIKSHA_HOME.'/sitemap_news_inc1.xml<br/><br/>It has also been uploaded on the Google webmaster.<br/><br/>-Shiksha Tech';
         $AlertClientObj = new Alerts_client();
         $alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$content,"html");
         $alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,'pranjul.raizada@shiksha.com',$subject,$content,"html");
         $alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,'abhinav.k@shiksha.com',$subject,$content,"html");
         $alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,'shruti.maheshwari@shiksha.com',$subject,$content,"html");
         error_log("\nMailers are sent\n",3,"/tmp/newsSitemapCreation.log");

    }

    function createTempFileForNews($fileName,$urls){
	    if(isset($urls) && is_array($urls)){
		$fp=fopen($fileName,'w+');
		foreach($urls['URL'] as $pageUrl){
		    fputs($fp,$pageUrl."\n");
		}
		fclose($fp);
	    }
    }

    function createTempFile($fileName,$urls,$urls2=array() ){	
	    if(isset($urls) && is_array($urls)){
		$fp=fopen($fileName,'a+');
		foreach($urls['URL'] as $pageUrl){
		    fputs($fp,$pageUrl."\n");
		}
		fclose($fp);
	    }
	    if(isset($urls2) && is_array($urls2) && count($urls2)>0 ){
		$fp=fopen($fileName,'a+');
		foreach($urls2['URL'] as $pageUrl){
		    fputs($fp,$pageUrl."\n");
		}
		fclose($fp);
	    }
    }


    function getCategoryPagesURLs($tempFileURL = "/tmp/categoryPages_url", $date="", $typeSitemap="full"){
            
            $CategoryPagesURLs = array();
            $counter = 0;
            $abroadDomainURLsArray = array();
            
            error_log("\n------------------------------------------\n START COLLECTING CATEGORY PAGES URLS at ".date("Y-m-d:H:i:s")."\n------------------------------------------",3,"/tmp/sitemapCreation.log");

	    /********************* START : Get National Category page URLs ********************/
	    $CategoryPagesURLsForThisCategory 	= $this->getNationalCategoryPageUrls();
            $countForNationalCatPages 		= count($CategoryPagesURLsForThisCategory);
            $CategoryPagesURLs 			= array_merge($CategoryPagesURLs, $CategoryPagesURLsForThisCategory);

        foreach ($CategoryPagesURLsForThisCategory as $name => $urlArr) {
			// Creating the temp file for this Category for National Cat page URLs..
			$filename = $tempFileURL . '_' . $name . '_' . $date . $typeSitemap;

			$urlArrUnique = array_unique($urlArr);
			$this->createTempFile($filename, array('URL' => $urlArrUnique));
			$fileAndDomainInfo[ $counter ]['filename'] = $filename;
			$fileAndDomainInfo[ $counter++ ]['domain'] = $name;
		}

		unset($CategoryPagesURLsForThisCategory);
	    
	    /********************* END : Get National Category page URLs ********************/

	    /********************* START : Get Abroad Category page URLs ********************/

	    $CategoryPagesURLsForThisCategory 	= array();
        $CategoryPagesURLsForThisCategory 	= $this->getAbroadCategoryPageURLs();
	    $countForAbroadCatPages 		= count($CategoryPagesURLsForThisCategory);

	    error_log(", National = ".$countForNationalCatPages.", Abroad = ".$countForAbroadCatPages.", Total URLs = ".($countForNationalCatPages + $countForAbroadCatPages),3,"/tmp/sitemapCreation.log");
            
            // Now arranging the abroad URls within different Abroad Domains..
            foreach($CategoryPagesURLsForThisCategory as $urlKey => $abroadUrl) {
                $pos 	= strpos($abroadUrl, ".");
                $domain = substr($abroadUrl, 8, ($pos-8));
                if($domain == "www")
                    $abroadDomainURLsArray['shiksha'][] = $abroadUrl;
                else
                    $abroadDomainURLsArray[$domain][] = $abroadUrl;

                $CategoryPagesURLs[] = $abroadUrl;
            }

            unset($CategoryPagesURLsForThisCategory);
            
            // Now creating temp file for Abraod URLs..
            foreach($abroadDomainURLsArray as $domain => $abroadUrl) {

                // Writing all Abroad Cat pages URLs into the Temp File for this domain..
                $filename = $tempFileURL.'_'.$domain.'_'.$date.$typeSitemap;
                $this->createTempFile($filename, array('URL' => $abroadDomainURLsArray[$domain]));
                $fileAndDomainInfo[$counter]['filename'] = $filename;
                $fileAndDomainInfo[$counter++]['domain'] = $domain;
            }
	    /********************* END : Get Abroad Category page URLs ********************/

             error_log("\n------------------------------------------\n CATEGORY PAGES URLS (Total ".count($CategoryPagesURLs).") collected successfully at ".date("Y-m-d:H:i:s")."\n------------------------------------------",3,"/tmp/sitemapCreation.log");

             return ($fileAndDomainInfo);
    }

    function getCoursePagesURLs($tempFileURL = "/tmp/courseHomePages_url", $date="", $typeSitemap="full") {
    	//$coursePagesUrlRequest = $this->load->library('coursepages/CoursePagesUrlRequest');
    	//$url_list = $coursePagesUrlRequest->getAllCoursePagesURLNew();
        $this->ChpClient = $this->load->library('chp/ChpClient');
        $this->load->config("chp/chpAPIs");
        $getAllChpUrl = $this->config->item('CHP_GET_ALL_BASIC_INFO');
        $result = $this->ChpClient->makeCURLCall('POST',$getAllChpUrl);
        $result = json_decode($result,true);

    	$url_list = array();
	    if(isset($result['status']) && $result['status'] == "success"){
		    foreach ($result['data'] as $row){
			    $url_list[] = SHIKSHA_HOME.$row['url'];
    		}
	    }

    	$fileAndDomainInfo = array();
		$filename = $tempFileURL.'_shiksha_'.$date.$typeSitemap;
		$this->createTempFile($filename, array('URL' => $url_list));
		$fileAndDomainInfo[0]['filename'] = $filename;
		$fileAndDomainInfo[0]['domain'] = $category_domain; 
    	return $fileAndDomainInfo;
    }
    
    function getURLsForThisRequestAbroad($categoryPageRequest, $locationRepository, $requestData, $regions, $countries) {

                    $CategoryPagesURLs = array();

                    // All Abroad Category pages "all locations" URLs that exist in DB..
                    $countryProcessed = array();
                    // Getting URLs for Regions and thier Countries..
                    foreach($regions as $region) {
                        $requestData['regionId'] = $region->getId();
                        $requestData['countryId'] = 1;
                        $categoryPageRequest->setData($requestData);
                        $computedURLs[] = $categoryPageRequest->getURL();
                        
                        $countriesForThisRegion = $locationRepository->getCountriesByRegion($region->getId());

                        foreach($countriesForThisRegion as $country) {
                            $countryProcessed[] = $requestData['countryId'] = $country->getId();
                            $categoryPageRequest->setData($requestData);
                            $computedURLs[] = $categoryPageRequest->getURL();
                        }
                     }

                     // Getting URLs for Countries without Regions..
                     $requestData['regionId'] = 0;
                     $countryProcessed[] = 2;  // Take India out of the case.
                     foreach($countries as $country) {
                            if(in_array($country->getId(), $countryProcessed))
                            continue;

                            $requestData['countryId'] = $country->getId();
                            $categoryPageRequest->setData($requestData);
                            $computedURLs[] = $categoryPageRequest->getURL();
                     }

                    return ($computedURLs);
    }

    function getURLsForThisRequestNational($categoryPageRequest, $locationRepository, $requestData, $states, $cityList, $cityHavingZonesArray) {

                    $CategoryPagesURLs = array();

                    // All Category pages "all States" URLs that exist in DB..
                    $URLs = array();
                    $URLs = $this->getAllStatesURLs($categoryPageRequest, $requestData, $states);
                    $CategoryPagesURLs = array_merge($CategoryPagesURLs, $URLs);
                    // echo "<hr> States URL .<br> request data: ";_p($requestData); _p($URLs);

                    // All Category pages URLs to be generated for "all India Cities" that exist in DB..
                    $URLs = array();
                    $URLs = $this->getAllCitiesURLs($categoryPageRequest, $requestData, $cityList);
                    $CategoryPagesURLs = array_merge($CategoryPagesURLs, $URLs);
                    // echo "<hr> Cities URL .<br> request data: ";_p($requestData); _p($URLs);
			// Do not prepare URL for zones in case of RnR II URLs
		    if( !($categoryPageRequest->getNewURLFlag()) )
		    {
			// All Category pages URLs to be generated for "all Zones of Indian Metro Cities" that exist in DB..
			$URLs = array();
			$URLs = $this->getAllZonesURLs($categoryPageRequest, $locationRepository, $requestData, $cityHavingZonesArray);
			$CategoryPagesURLs = array_merge($CategoryPagesURLs, $URLs);
			// echo "<hr> Zones URL .<br> request data: ";_p($requestData); _p($URLs);
		    }

                    // All Category pages URLs to be generated for "all Localities of Indian Metro Cities' Zones" that exist in DB..
                    $URLs = array();
                    $URLs = $this->getAllLocalitiesURLs($categoryPageRequest, $locationRepository, $requestData, $cityHavingZonesArray);
                    $CategoryPagesURLs = array_merge($CategoryPagesURLs, $URLs);
                    // echo "<hr> Localities URL .<br> request data: ";_p($requestData); _p($URLs);

                    return ($CategoryPagesURLs);
    }

     function getAllStatesURLs(CategoryPageRequest $categoryPageRequest, $requestData, $allStates) {

         foreach($allStates as $state) {
            $requestData['stateId'] = $state->getId();
            $categoryPageRequest->setData($requestData);
            $computedURLs[] = $categoryPageRequest->getURL();
         }
         return ($computedURLs);
    }

    function getAllCitiesURLs(CategoryPageRequest $categoryPageRequest, $requestData, $allCities) {

         foreach($allCities as $cityArray) {
             foreach($cityArray as $city) {
                $requestData['cityId'] = $city->getId();
                $categoryPageRequest->setData($requestData);
                $computedURLs[] = $categoryPageRequest->getURL();
             }
         }
         return ($computedURLs);
    }

    function getAllZonesURLs(CategoryPageRequest $categoryPageRequest, LocationBuilder $locationRepository, $requestData, $cityHavingZonesArray) {

        foreach($cityHavingZonesArray as $cityId) {
                $requestData['cityId'] = $cityId;
                // Get all zones for this city now..
                $zonesForThisCity = $locationRepository->getZonesByCity($cityId);

                $requestData['cityId'] = $cityId;

                foreach($zonesForThisCity as $zoneId) {
                    $requestData['zoneId'] = $zoneId->getId();
                    $categoryPageRequest->setData($requestData);
                    $computedURLs[] = $categoryPageRequest->getURL();
                }
             }
         return ($computedURLs);
    }


    function getAllLocalitiesURLs(CategoryPageRequest $categoryPageRequest, LocationBuilder $locationRepository, $requestData, $cityHavingZonesArray) {

        foreach($cityHavingZonesArray as $cityId) {
                $requestData['cityId'] = $cityId;
                // Get all zones for this city now..
                $zonesForThisCity = $locationRepository->getZonesByCity($cityId);
                $requestData['cityId'] = $cityId;

                foreach($zonesForThisCity as $zoneId) {
                    $requestData['zoneId'] = $zoneId->getId();
                    $localitiesforThisZone = $locationRepository->getLocalitiesByZone($zoneId->getId());
                    foreach($localitiesforThisZone as $locality) {
                        $requestData['localityId'] = $locality->getId();
                        $categoryPageRequest->setData($requestData);
                        $computedURLs[] = $categoryPageRequest->getURL();
                    }
                }
             }
         return ($computedURLs);
    }

    function getDefaultRequestData (){
                 // Setting the default values..
                $requestData = array();
                $requestData['categoryId'] = 3;
                $requestData['subCategoryId'] = 1;
                $requestData['LDBCourseId'] = 1;
                $requestData['localityId'] = 0;
                $requestData['zoneId'] = 0;
                $requestData['cityId'] = 1;
                $requestData['stateId'] = 1;
                $requestData['countryId'] = 2;
                $requestData['regionId'] = 0;

                return ($requestData);
    }

    function getAbroadCategoryPageURLs()
    {
	    $this->load->builder('LocationBuilder','location');
	    $locationBuilder = new LocationBuilder;
	    $this->locationRepository  = $locationBuilder->getLocationRepository();
	    $this->load->library('categoryList/AbroadCategoryPageRequest');
	    $this->abroadcmsmodel  = $this->load->model('listingPosting/abroadcmsmodel');
	    $this->abroadcategorypagemodel  = $this->load->model('categoryList/abroadcategorypagemodel');
	    $this->abroadCommonLib 	= $this->load->library('listingPosting/AbroadCommonLib');
	    $this->extraWhereClause = " and course_type != 'Certificate/Diploma' ";

	    $LDBCoursePageURLs = $this->_getLDBCoursePageURLs();
	    $LDBCourseSubCatPageURLs = $this->_getLDBCourseSubCatPageURLs();
	    $CatSubCatCourseLevelPageURLs = $this->_getCatSubCatCourseLevelPageURLs();
	    $CatCourseLevelPageURLs = $this->_getCatCourseLevelPageURLs();
	    
	    $abroadCatpageURls = array_unique(array_merge($LDBCoursePageURLs,$LDBCourseSubCatPageURLs, $CatSubCatCourseLevelPageURLs,$CatCourseLevelPageURLs ));
	    return $abroadCatpageURls;
    }
    
    function _getLDBCoursePageURLs()
    {
		$this->abroadCategoryPageRequest = new AbroadCategoryPageRequest;
		$desiredLDBCourses = $this->abroadcmsmodel->getAbroadMainLDBCourses();
		$urls = array();
		
		foreach($desiredLDBCourses as $desiredCourseRow)
		{
			$countries_array = $this->abroadcategorypagemodel->getCountriesForDesiredCourses($desiredCourseRow["SpecializationId"]);
			$countries = array();
			foreach($countries_array as $row)
				$countries[] = $row["country_id"];
			$countries = empty($countries) ? $countries : array_merge($countries, array(1));
			
			foreach($countries as $countryObj)
			{
			$ldbCourse = $desiredCourseRow["SpecializationId"];
			$this->abroadCategoryPageRequest->setData(array("LDBCourseId" => $ldbCourse,
									   "countryId" => array($countryObj)));
			$urls[] = $this->abroadCategoryPageRequest->getURL();
			}
		}
		return $urls;
    }
    
    function _getLDBCourseSubCatPageURLs()
    {
		$this->abroadCategoryPageRequest = new AbroadCategoryPageRequest;
		$desiredLDBCourses = $this->abroadcmsmodel->getAbroadMainLDBCourses();
		
		$urls = array();
		
		foreach($desiredLDBCourses as $desiredCourseRow)
		{
			$ldbCourse = $desiredCourseRow["SpecializationId"];
			$countries_ldbCourseWise = $this->abroadcategorypagemodel->getCoursesCountriesLDBCourseWise($ldbCourse);
			$subCats = $this->abroadcategorypagemodel->getSubCatsForDesiredCourses($ldbCourse);
			foreach($subCats as $subCatRow)
			{
				$countries = $countries_ldbCourseWise[$subCatRow['sub_category_id']];
				$countries = empty($countries) ? $countries : array_merge($countries, array(1));
				foreach($countries as $countryObj)
				{
					//$subCatRow["sub_category_id"];
					$this->abroadCategoryPageRequest->setData(array("LDBCourseId" => $ldbCourse,
											"countryId" => array($countryObj),
											"subCategoryId" => $subCatRow['sub_category_id']));
					$urls[] = $this->abroadCategoryPageRequest->getURL();
				}
			}
		}
		return $urls;
    }
    
    function _getCatSubCatCourseLevelPageURLs()
    {
		$this->abroadCategoryPageRequest = new AbroadCategoryPageRequest;
		$abroadCategories = $this->abroadCommonLib->getAbroadCategories();
		$abroadCourseLevels = $this->abroadCommonLib->getAbroadCourseLevelsForFindCollegeWidgets(); // this one merges all certificate diploma levels as one
		// $snapshot_subcats = $this->abroadcategorypagemodel->getAllSubcategoriesOfSnapshotCourses($this->extraWhereClause);
		// $snapshot_countries = $this->abroadcategorypagemodel->getAllCountriesOfSnapShotCourses($this->extraWhereClause);
		$countries_subCatAndLevelWise = $this->abroadcategorypagemodel->getCoursesCountriesSubCatAndCourseLevelWise();
		$subcats_catAndLevelWise = $this->abroadcategorypagemodel->getAbroadCoursesSubCategories();
		$urls = array();
		
		foreach($abroadCategories as $categoryRow)
		{
			$category_id = $categoryRow["id"];
			foreach($abroadCourseLevels as $courseLevelRow)
			{	
				if( // we dont want redirecting links to be added into the sitemap (SA-1231)
					// master of business
					($category_id == 239 && $courseLevelRow == 'Masters') ||
					// MS  in engg, computers, science
					(in_array($category_id ,array(240,241,242)) !== false && $courseLevelRow == 'Masters') ||
					// be btech in engg,computers
					(in_array($category_id ,array(240,241)) !== false  && $courseLevelRow == 'Bachelors')
				)
				{
					continue;
				}
				
				$courseLevel = $courseLevelRow;
				$subCategories = $subcats_catAndLevelWise[$category_id][$courseLevel];
				$subCategories = is_array($subCategories) ? $subCategories : array();
				$snapshot_subcats[$category_id][$courseLevel] = is_array($snapshot_subcats[$category_id][$courseLevel]) ? $snapshot_subcats[$categoryRow["id"]][$courseLevel] : array();
				$subCategories = array_unique(array_merge($subCategories, $snapshot_subcats[$category_id][$courseLevel]));
			
				foreach($subCategories as $subCatRow)
				{
					$subCat = $subCatRow;
					//$countries_array = $this->abroadcategorypagemodel->getCountriesForParentCatAndCourseLevel($categoryRow["id"], $courseLevelRow, $subCat);
					//$countries = array();
					//foreach($countries_array as $row)
					//    $countries[] = $row["country_id"];
					$countries = $countries_subCatAndLevelWise[$category_id][$courseLevel][$subCat];
					$countries = is_array($countries) ? $countries : array();
				
					$snapshot_countries[$courseLevel][$subCat] = is_array($snapshot_countries[$courseLevel][$subCat]) ? $snapshot_countries[$courseLevel][$subCat] : array();
					$countries = array_unique(array_merge($countries, $snapshot_countries[$courseLevel][$subCat]));
					
					// include all country link
					$countries = empty($countries) ? $countries : array_merge($countries, array(1));
					
					foreach($countries as $countryObj)
					{
						$this->abroadCategoryPageRequest->setData(array("categoryId" => $category_id,
												"countryId" => array($countryObj),
												"subCategoryId" => $subCat,
												"courseLevel" => $courseLevel));
						$urls[] = $this->abroadCategoryPageRequest->getURL();
					}
				}
			}
		}
		return $urls;
    }
    
    function _getCatCourseLevelPageURLs()
    {
		$this->abroadCategoryPageRequest = new AbroadCategoryPageRequest;
		$abroadCategories = $this->abroadCommonLib->getAbroadCategories();
		$abroadCourseLevels = $this->abroadCommonLib->getAbroadCourseLevelsForFindCollegeWidgets(); // this one merges all certificate diploma levels as one
		// $snapshot_subcats = $this->abroadcategorypagemodel->getAllSubcategoriesOfSnapshotCourses($this->extraWhereClause);
		// $snapshot_countries = $this->abroadcategorypagemodel->getAllCountriesOfSnapShotCoursesCatAndLevelWise($this->extraWhereClause);//getAllCountriesOfSnapShotCourses();
		$countries_subCatAndLevelWise = $this->abroadcategorypagemodel->getCoursesCountriesSubCatAndCourseLevelWise();
		$subcats_catAndLevelWise = $this->abroadcategorypagemodel->getAbroadCoursesSubCategories();
		$urls = array();
		
		foreach($abroadCategories as $categoryRow)
		{
			$category_id = $categoryRow["id"];
			
			foreach($abroadCourseLevels as $courseLevelRow)
			{
				if( // we dont want redirecting links to be added into the sitemap (SA-1231)
					// master of business
					($category_id == 239 && $courseLevelRow == 'Masters') ||
					// MS  in engg, computers, science
					(in_array($category_id ,array(240,241,242)) !== false && $courseLevelRow == 'Masters') ||
					// be btech in engg
					(in_array($category_id ,array(240,241)) !== false && $courseLevelRow == 'Bachelors' )
				)
				{
					continue;
				}
				$courseLevel = $courseLevelRow;
				$subCategories = $subcats_catAndLevelWise[$category_id][$courseLevel];
				$subCategories = is_array($subCategories) ? $subCategories : array();
				$snapshot_subcats[$category_id][$courseLevel] = is_array($snapshot_subcats[$category_id][$courseLevel]) ? $snapshot_subcats[$categoryRow["id"]][$courseLevel] : array();
				$subCategories = array_unique(array_merge($subCategories, $snapshot_subcats[$category_id][$courseLevel]));
			
				//$countries = $countries_subCatAndLevelWise[$category_id][$courseLevel][$subCat];
				$countries = array();
				foreach($countries_subCatAndLevelWise[$category_id][$courseLevel] as $countryrow){
					$countries = array_unique(array_merge($countries, $countryrow));
				}
	
				$snapshot_countries[$category_id][$courseLevel] = is_array($snapshot_countries[$category_id][$courseLevel]) ? $snapshot_countries[$category_id][$courseLevel] : array();
				$countries = array_unique(array_merge($countries, $snapshot_countries[$category_id][$courseLevel]));
				
				// include all country link
				$countries = empty($countries) ? $countries : array_merge($countries, array(1));			
				foreach($countries as $countryObj)
				{
					$this->abroadCategoryPageRequest->setData(array("categoryId" => $category_id,
											"countryId" => array($countryObj),
											"courseLevel" => $courseLevel));
					$urls[] = $this->abroadCategoryPageRequest->getURL();
				}
			}
		}
		return $urls;
    }
    
    /*
     * Purpose : Method to get national category pages urls category-wise
     * Author : Romil Goel
    */
    function getNationalCategoryPagesUrls()
    {
	// load required files
	$this->config->load('categoryPageConfig');
	$categoryPageRequest 			= $this->load->library('categoryList/categoryPageRequest');
	$categoryPageZeroResultHandlerLib 	= $this->load->library('categoryList/CategoryPageZeroResultHandler');
	
	// get the non-zero category page data
	$data = $categoryPageZeroResultHandlerLib->getNonZeroCategoryPagesData();

	// get rnr-subcategories
	$RNRSubCategories = array_keys($this->config->item("CP_SUB_CATEGORY_NAME_LIST"));
	$urls 		  = array();

	// get the url for each non-zero category page
	foreach($data as $catPageData)
	{
	    $categoryPageRequest	= new CategoryPageRequest;

	    $urlData['categoryId']      = $catPageData["category_id"];
            $urlData['subCategoryId']   = $catPageData["sub_category_id"];
            $urlData['LDBCourseId']     = $catPageData["LDB_course_id"];
            $urlData['localityId']      = $catPageData["locality_id"];
	    	$urlData['zoneId']          = $catPageData["zone_id"];
            $urlData['cityId']          = $catPageData["city_id"];
            $urlData['stateId']         = $catPageData["state_id"];
            $urlData['countryId']       = $catPageData["country_id"];
            $urlData['regionId']        = $catPageData["region_id"];
            $urlData['affiliation']     = $catPageData["affiliation_value"];
            $urlData['examName']        = $catPageData["exam_value"];
            $urlData['feesValue']       = $catPageData["fees_value"];

	    if(in_array($urlData['subCategoryId'], $RNRSubCategories)){
		    $categoryPageRequest->setNewURLFlag(1);
		    $urlData['naukrilearning'] = (int) 0;
		    $urlData['sortOrder'] = 'none';
	    }

	    $categoryPageRequest->setData($urlData);

	    $urls[$catPageData["category_id"]][] = $categoryPageRequest->getUrl();
	}

	return $urls;
    }


// TODO Review and add documentation
	private function getNationalCategoryPageUrls()
	{
		$categoryPageSEOLib = $this->load->library('nationalCategoryList/CategoryPageSeoLib');
		$data                     = $categoryPageSEOLib->getCategorySitemapURLs();
        $urls                     = array();
        
		if ($data['data']['found'] == 'yes') {

			$this->load->builder('ListingBaseBuilder', 'listingBase');
			$listingBaseBuilder   = new ListingBaseBuilder();
			$streamRepository     = $listingBaseBuilder->getStreamRepository();
			$basecourseRepository = $listingBaseBuilder->getBaseCourseRepository();

			foreach ($data['data']['result'] as $catPageData) {
				if (isset($catPageData['stream_id']) && $catPageData['stream_id'] != '0') {
					$name                       = $streamRepository->find($catPageData['stream_id']);
					$urls[ $name->getUrlName(1) ][] = SHIKSHA_HOME . "/" . $catPageData['url'];
				} else if (isset($catPageData['base_course_id']) && $catPageData['base_course_id'] != '0') {
					$name                       = $basecourseRepository->find($catPageData['base_course_id']);
					$urls[ $name->getUrlName(1) ][] = SHIKSHA_HOME . "/" . $catPageData['url'];
				} else {
                    $urls['location'][] = SHIKSHA_HOME . "/" . $catPageData['url'];
                }
			}
		}

		return $urls;
	}
	
	function createRealTimeSitemap($types, $count){
		$this->validateCron();
		$model = $this->load->model('seomodel');
		$date = date("Y-m-d");
    
		error_log("============================================================================\n",3,"/tmp/sitemapCreationRealTime.log");
		error_log("Sitemap Generation starts for $typeSitemap sitemap at ".date("Y-m-d:H:i:s")."\n",3,"/tmp/sitemapCreationRealTime.log");
		
		if (SHIKSHA_HOME == 'https://www.shiksha.com')
		{
			    $shikshaHomePath = "/var/www/html/shiksha/";
			    $perlPath = $shikshaHomePath."application/config/";
			    $enableGoogleUpload = true;
		}
		else{
			    $shikshaHomePath = "/var/www/html/shiksha/";
			    $perlPath = $shikshaHomePath."application/config/";
			    $enableGoogleUpload = false;
		}
    
		switch($types)
		{
			case 'ANA':
				if($count == '' || $count == 0){
					$count = 1000;
				}
				$urls = $model->getANAURLForSitemap($count);
				
				$fileName = "/tmp/realtime_ana_url".date("Y-m-d-H-i-s");	
				$this->createTempFile($fileName,$urls);

				exec("perl ".$perlPath."createRealTimeSitemap.pl $fileName ".$shikshaHomePath."latestdiscussions 1.0 always");
				//exec("gzip -f latestdiscussions.xml");

				//After creating the Sitemap, upload the Master sitemap on Google by making a CURL call
				if($enableGoogleUpload){
					$url = "http://www.google.com/webmasters/tools/ping?sitemap=".urlencode(SHIKSHA_HOME."/latestdiscussions.xml");
					$process = curl_init($url); 
					curl_setopt($process, CURLOPT_TIMEOUT, 30); 
					curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
					curl_setopt($process, CURLOPT_POST, 1); 
					$response = curl_exec($process); 
					curl_close($process);
				}

				break;
			case 'ArticleExam':
				if($count == '' || $count == 0){
					$count = 150;
				}

				$urls = $model->getArticleExamURLForSitemap($count);

				$fileName = "/tmp/realtime_others_url".date("Y-m-d-H-i-s");
				$this->createTempFile($fileName,$urls);
				exec("perl ".$perlPath."createRealTimeSitemap.pl $fileName ".$shikshaHomePath."updates 1.0 always");
				//exec("gzip -f updates.xml");

				//After creating the Sitemap, upload the Master sitemap on Google by making a CURL call
				if($enableGoogleUpload){
					$url = "http://www.google.com/webmasters/tools/ping?sitemap=".urlencode(SHIKSHA_HOME."/updates.xml");
					$process = curl_init($url); 
					curl_setopt($process, CURLOPT_TIMEOUT, 30); 
					curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
					curl_setopt($process, CURLOPT_POST, 1); 
					$response = curl_exec($process); 
					curl_close($process);
				}

				break;
		}		
		
		error_log("\nXML file generated successsfully for ".strtoupper($type)." at ".date("Y-m-d:H:i:s")."\n",3,"/tmp/sitemapCreationRealTime.log");
		error_log("\nSitemap generation Ends at ".date("Y-m-d:H:i:s")."\n",3,"/tmp/sitemapCreationRealTime.log");
		error_log("============================================================================\n",3,"/tmp/sitemapCreationRealTime.log");
      
	}	
	
} ?>
