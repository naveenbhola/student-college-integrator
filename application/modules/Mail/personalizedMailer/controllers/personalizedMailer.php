<?php
class personalizedMailer extends MX_Controller
{
	private $personalizedMailerRepository;

	private function _init(){
		$this->load->builder('personalizedMailerBuilder','personalizedMailer');
		$personalizedMailerBuilder = new personalizedMailerBuilder;
		$this->personalizedMailerRepository = $personalizedMailerBuilder->getPersonalizedMailerRepository();

		$this->cacheLib = $this->load->library('cacheLib');

		$this->load->helper('rankingV2/RankingUtility');
	}

	public function getDataForWidgets($mailerName = '', $customParams = array()) {
		$this->_init();

		// Get Mailer Details by Mailer Name
		$this->load->library('systemMailer/SystemMailerProcessor');

		$mailProcessor = new SystemMailerProcessor($mailerName);
		$mailerDetails = $mailProcessor->getMailerDetailsByName();
		$mailerId = $mailerDetails['mailer_id'];

		if(!empty($mailerId)) {
			// Get Widget List for a mailer
			$processedWidgetsList = $this->_getWidgetListInfoForPage($mailerId);
			
			// Get Widget Names from Widget List
			$widgetNames = $this->getWidgetNamesFromWidgetList($processedWidgetsList);

			/* Code Added by yamini/ankit => for showing this widget based on a condition */
			if(isset($customParams['userRegisteredInApp']) && $customParams['userRegisteredInApp']){
				foreach($widgetNames as $key=>$val){
					if($val == 'ShikshaAsknAnsInterlinkingWidget'){
						unset($widgetNames[$key]);
					}
				}
			}

			// Load Widget Classes
			$this->load->aggregator('WidgetsAggregator','personalizedMailer');
			$this->load->aggregatorClasses($widgetNames,'personalizedMailer');
			
			$widgetAggregator = new WidgetsAggregator();

			// Set Data for widgets in a mailer
			$customParams['mailerDetails'] = $mailerDetails;
			
			$this->_setAggregatorSources($widgetAggregator, $mailerId, $widgetNames, $customParams);

			$allWidgetData = $this->formatWidgetData($widgetAggregator, $processedWidgetsList);

			// Get Data from each Widget Class
			$mailerDetails['leanHeaderFooter'] = 0;
			if(isset($customParams['leanHeaderFooter']) && $customParams['leanHeaderFooter']==1){
				$mailerDetails['leanHeaderFooter'] = $customParams['leanHeaderFooter'];
			}

			$mailerDetails['leanHeaderFooterV2'] = 0;
			if(isset($customParams['leanHeaderFooterV2']) && $customParams['leanHeaderFooterV2']==1) {
				//footer data
				$mailerDetails['leanHeaderFooterV2'] = $customParams['leanHeaderFooterV2'];
				//$mailerDetails['userId'] = $customParams['userId'];
				$mailerDetails['unregisteredUser'] = $customParams['unregisteredUser'];
			}

			$mailerDetails['leanHeaderFooterV3'] = 0;
			if(isset($customParams['leanHeaderFooterV3']) && $customParams['leanHeaderFooterV3']==1) {
				//footer data
				$mailerDetails['leanHeaderFooterV3'] = $customParams['leanHeaderFooterV3'];
			}
			$mailerDetails['leanFooterV4'] = 0;
			if(isset($customParams['leanFooterV4']) && $customParams['leanFooterV4']==1){
				$mailerDetails['leanFooterV4'] = $customParams['leanFooterV4'];
			}

			if(isset($customParams['headerFooterType']) && $customParams['headerFooterType'] != ''){
				$mailerDetails['headerFooterType'] = $customParams['headerFooterType'];
			}
			$data = $this->loadWidgetsViews($allWidgetData, $mailerDetails);
		}


		return $data;
	}

	private function _getWidgetListInfoForPage($mailerId)	{
		$widgetsList = $this->personalizedMailerRepository->getWidgetListForMailer($mailerId);
		
		$personalizedMailerWidgetsLib = $this->load->library('personalizedMailer/personalizedMailerWidgetsLib');
		$processedWidgetsList = $personalizedMailerWidgetsLib->processWidgetsList($widgetsList);

		return $processedWidgetsList;
	}

	private function _setAggregatorSources($object, $mailerId, $widget_list, $customParams) {
					
		$params = array("mailerId" => $mailerId, "customParams" => $customParams);
		if(count($widget_list)>0) {
			foreach ($widget_list as $widget_class) {
				if(class_exists($widget_class)) {
					$object->addDataSource(new $widget_class($params));
				}
			}			
		}
	}

	private function getWidgetNamesFromWidgetList($processedWidgetsList) {
		$widgetNames = array();
		if(count($processedWidgetsList)>0) {
			foreach ($processedWidgetsList as $value) {
				foreach ($value as $widget_object) {
					$widgetNames[] = $widget_object->getWidgetName();						
				}
			}
		}
		return $widgetNames;
	}

	private function loadWidgetsViews($allWidgetData, $mailerDetails) {
		
		if($mailerDetails['mailerType'] == 'mmm') {
			$data = $this->loadWidgetsViewsMMM($allWidgetData, $mailerDetails);
		} else {
			$data = $this->loadWidgetsViewsShikshaSite($allWidgetData, $mailerDetails);
		}

		return $data;
	}

	private function loadWidgetsViewsShikshaSite($allWidgetData, $mailerDetails) {

		global $isMobileApp;
		$data = ''; 
		$mailerType = ''; 
		$params = array();
		$mailerType = $mailerDetails['mailerType'];
		$params['mailHeading'] = $mailerDetails['mailHeading'];
		$params['unregisteredUser'] = $mailerDetails['unregisteredUser'];

		if($mailerDetails['leanHeaderFooter']=='1'){
			$data = $this->load->view('personalizedMailerLeanHeader', $params, true);
		}elseif($mailerDetails['leanHeaderFooterV2'] == 1) {
			$params['profileUrl'] = SHIKSHA_HOME.'/userprofile/edit';
			$data = $this->load->view('personalizedMailerLeanHeaderV2', $params, true);
		}elseif($mailerDetails['leanHeaderFooterV3'] == 1) {
			$data = $this->load->view('personalizedMailerLeanHeaderV3', $params, true);
		}else if($isMobileApp){
			$data = $this->load->view('personalizedMailerAppHeader', $params, true);
		}else{
			$data = $this->load->view('personalizedMailerHeader', $params, true);
		}

		$contentData = array();
		foreach ($allWidgetData as $widgetData) {
			$contentData['widgetData'] .= $widgetData;
		}

		if($mailerDetails['leanHeaderFooter']=='1'){
                $data .= $contentData['widgetData'];
        }elseif($mailerDetails['leanHeaderFooterV2'] == 1) {
			$data .= $this->load->view('personalizedMailerLeanContentV2', $contentData, true);
        }elseif($mailerDetails['leanHeaderFooterV3'] == 1) {
			$data .= $this->load->view('personalizedMailerLeanContentV3', $contentData, true);
        }else{
            $data .= $this->load->view('personalizedMailerContent', $contentData, true);
        }

        if($mailerDetails['leanFooterV4'] =='1')
        {
        	$params = $this->getleanFooterV4Data();
			$data .= $this->load->view('personalizedMailerLeanFooterV4', $params, true);
        }
		elseif($mailerDetails['leanHeaderFooter']=='1'){
			$data .= $this->load->view('personalizedMailerLeanFooter', '', true);
		}elseif($mailerDetails['leanHeaderFooterV2'] == 1) {
			$params['footer'] = $this->getleanFooterV2Data();
			$data .= $this->load->view('personalizedMailerLeanFooterV2', $params, true);
		}elseif($isMobileApp){
			$data .= $this->load->view('personalizedMailerAppFooter', '', true);
		}elseif($mailerDetails['leanHeaderFooterV3'] == 1) {
			$params['footer'] = $this->getleanFooterV2Data();
			$data .= $this->load->view('personalizedMailerLeanFooterV3', $params, true);
		}else{
			$data .= $this->load->view('personalizedMailerFooter', '', true);
		}
		
		return $data;
	}
 	private function getleanFooterV4Data()
 	{
 		$this->load->library('messageBoard/AnALibrary');
         $statsParam['footer'] = $this->analibrary->getAnAStats();
         return $statsParam;
 	}
	private function loadWidgetsViewsMMM($allWidgetData, $mailerDetails) {

		$allMMMHeaderFooterTypes = array('MMMV3');
		$data = ''; 
		$mailerType = ''; 
		$params = array();
		$mailerType = $mailerDetails['mailerType'];
		$params['mailHeading'] = $mailerDetails['mailHeading'];
		$params['unregisteredUser'] = $mailerDetails['unregisteredUser'];
		
		$contentData = array();
		foreach ($allWidgetData as $widgetData) {
			$contentData['widgetData'] .= $widgetData;
		}
		
		if($mailerDetails['leanHeaderFooterV2'] == 1) {
			$params['profileUrl'] = SHIKSHA_HOME.'/userprofile/edit';
			$params['type'] = 'MMM';
			$params['footer'] = $this->getleanFooterV2Data();
			$data = $this->load->view('personalizedMailerLeanHeaderV2', $params, true);
			$data .= $this->load->view('personalizedMailerLeanContentV2', $contentData, true);
			$data .= $this->load->view('personalizedMailerLeanFooterV2', $params, true);
		} else if( ($mailerDetails['headerFooterType'] != '') && (in_array($mailerDetails['headerFooterType'], $allMMMHeaderFooterTypes)) ) {
			$data = $this->load->view('personalizedMailerHeader'.$mailerDetails['headerFooterType'], $mailerDetails, true);
			$data .= $this->load->view('personalizedMailerContent'.$mailerDetails['headerFooterType'], $contentData, true);
			$data .= $this->load->view('personalizedMailerFooter'.$mailerDetails['headerFooterType'], $mailerDetails, true);
		} else {
			$data = $this->load->view('personalizedMailerHeaderMMM', $params, true);
			$data .= $this->load->view('personalizedMailerContentMMM', $contentData, true);
			$data .= $this->load->view('personalizedMailerFooterMMM', '', true);
		}

		return $data;
	}

	private function getleanFooterV2Data() {
		$cntKey = md5('nationalHomepageCounters_json');
		$hpCounterResult = $this->cacheLib->get($cntKey);
		if($hpCounterResult != 'ERROR_READING_CACHE'){
			$hpCounterResult = json_decode($hpCounterResult, true);
			foreach ($hpCounterResult['national'] as $key => $value) {
				$data[$key] = str_replace(' ', '', formatAmountToNationalFormat($value, 0));
			}
			return $data;
		}
	}

	private function formatWidgetData ($widgetAggregator, $processedWidgetsList) {
	
		if(count($processedWidgetsList)>0) {

			$allWidgetData = $widgetAggregator->getData();

			$olddisplayOrder = 0;$oldwidgetData = '';$oldWidgetName = '';

			foreach ($processedWidgetsList as $value) {

				foreach ($value as $widget_object) {

					$displayOrder = 0;$widgetName='';$widgetData='';
					
					$displayOrder = $widget_object->getDisplayOrder();	

					$widgetName = $widget_object->getWidgetName();	

					$widgetData = $allWidgetData[$widgetName];

					
					if($displayOrder == $olddisplayOrder) {

						if((empty($oldwidgetData)) || (empty($widgetData))) {
							if(!empty($oldwidgetData)) {
								$allWidgetData[$oldWidgetName] = "<tr><td style='mso-line-height-rule:exactly;'>".str_replace('##WIDGET_ALIGNMENT##', 'center', $oldwidgetData)."</td></tr>";
							} else if(!empty($widgetData)) {
								$allWidgetData[$widgetName] = "<tr><td style='mso-line-height-rule:exactly;'>".str_replace('##WIDGET_ALIGNMENT##', 'center', $widgetData)."</td></tr>";
							}
						} else {
							$allWidgetData[$oldWidgetName] = "<tr><td style='mso-line-height-rule:exactly;'>".str_replace('##WIDGET_ALIGNMENT##', 'left', $oldwidgetData);
							$allWidgetData[$widgetName] = str_replace('##WIDGET_ALIGNMENT##', 'left', $widgetData)."</td></tr>";
						}
						$olddisplayOrder = 0;$oldwidgetData = '';$oldWidgetName = '';
					} else {
						$olddisplayOrder = $displayOrder;
						$oldwidgetData = $widgetData;	
						$oldWidgetName = $widgetName;
					}
				}
			}
			return $allWidgetData;
		}
	}
}
