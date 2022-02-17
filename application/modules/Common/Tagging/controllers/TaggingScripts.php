<?php

class TaggingScripts extends MX_Controller {

	function __construct(){
		$this->load->model('Tagging/taggingscriptsmodel');
		$this->scriptModel = new TaggingScriptsModel();
		// die;
	}


	function newtagsCreation(){
		$newTagNames = array("MHCET","GITAM GAT","TANCET","IPU CET","KEE","VTUEEE","IMUCET","IEMJEE","ATIT","Amity JEE","Alliance AUEET","BSAUEEE","AISEEE","REVA EET","SBAIEE","Nerist Entrance Exam","Assam CEE","TJEE","SUAT","GEEE","AMIE Exam","HPCET","GAT-UGTP","AUCET","IMU CET","DAIICT Exam","Karnataka PGCET","IIITD Exam","BITS HD Exam","WBUT PGET","SRMGEET","UKSEE","UPES MEET","PDPU M.Tech Exam","CEED","NID Entrance Exam","UCEED","AICET","AIEED","AIFD WAT","CEPT Entrance Exam","FDDI AIST","GD Goenka DAT","GLS Institute of Design DAT","IIAD Entrance Exam","IICD Entrance Exam","ISDI Challenge","MDAT","MITID DAT","NICC Entrance Exam","NIIFT Entrance Exam","Pearl Academy Entrance Exam","SEAT Exam","SID Exam","SOFT CET","TDV Entrance Exam","UID Aptitude Test","UPES DAT","WWI Entrance Exam","AILET","ACLAT","AIBE","LAT","AMU Law Entrance Exam","APLAWCET","BHU UET Law","DU LLB Entrance Exam","ILICAT","ITLA","KLEE","LFAT","Lloyd Entrance Test (LET)","REVA CLAT","RULET","TSLAWCET","ULSAT","TSPGLCET","APPGLCET","PU UGLAW Exam","SSC Exam","TNPSC Exam","UPSSSC Exam","KPSC Exam","Kerala PSC Exam","RPSC Exam","UPPSC Exam","MPSC Exam","TSPSC Exam","UGC NET","MPPSC Exam","JSSC Exam","BPSC Exam","TANGEDCO Exam","SSC CGL","LIC ADO Exam","HPPSC Exam","UKPSC Exam","LIC AAO Exam","WBPSC Exam","GPSC Exam","JKPSC Exam","TSGENCO Exam","AFCAT","APPSC Exam","UPPCS Exam","OSSSC Exam","NPSC Exam","APSC Exam","SSC CHSL Exam","UPSC IFS Forest","UPSC Civil Exam","MPPEB Exam","KELTRON Exam","UPSC CSAT","SSC CPO Exam","CGPSC Exam","ESE","ARS NET","BSNL JTO Exam","HPSC Exam","JPSC Exam","SSC SI Exam","GPSC Goa Exam","UPSC IES","UPSC ISS","UPSC CMS Exam","Bank clerk exam","Maharashtra PSC ","Punjab PSC","Meghalaya PSC","Odisha PSC","CISF Exam","LIC DO Exam","Tripura PSC","Divisional Accountants Exam","Auditors Exam","UDC Exam","Mizoram PSC","Sikkim PSC","CISF AC EXE LDCE","MTNL JTO Exam","UPSC Main Examination","MPSC Tax Assistant Exam","Arunachal PSC","RTCE ","CES Exam","SSC Stenographer Grade C and D Exam","Indian Navy Sailor Exam","CTET","HTET","TSTET","PTET","AP TET","AP Ed CET","KTET","WBTET","KARTET","AP Ed.CET","Jharkhand TET","TS Ed.CET","ICAI Exam","CPT","CA IPCC","CFA Exam","ICWAI exam","ICSI exam","CS Foundation Exam","CS Executive exam","CS Examination","ICMAI exam","CA Final","RBI Assistant Exam","SBI SO Exam","ICICI PO","IBPS RRB Exam","SBI Associate Clerk Exam","SBI Associate PO Exam","CUSAT MBA ","Bocconi Entrance Test","B-MAT","EMAT","GNDU GMET","GSIB Entrance Test","HBSAT","HPU MAT","APICET","IGNOU OPENMAT","IRMA Exam","JEMAT","Karnataka PGCET","KIITEE Management","KMAT","KMAT Kerala","MPMET","PGRRCDE MBA Entrance Test","PIM-MAT","PU-CET (P.G.)","PU MET","RMAT","SMAT","SPJAT","SRMCAT","TANCET MBA","TSICET MBA","UPESMET","UPMCAT","VMOU MET","X-GMAT","MCAER Entrance Exam","NEST","JAM","OUAT Entrance Exam","JEST","GBPUAT Entrance Exam","UPCATET","GSAT","CG PAT","BCECE Medical","CMC VELLORE Entrance Exam","RPMT","DMAT","NEET UG","AIIMS MBBS Exam","MT CET Pharmacy","NIPER JEE","TNPCEE","GPAT","AU AIMEE","NIMCET","JUET MCA","Karnatka PGCET MCA","BHU PET MCA","NPAT","SUAT MCA","IPMAT","Christ University BBA Entrance Exam","AIMA UGAT","DU JAT","SRMHCAT","MP MET","DU BMS CET","SET BBA","CART","XIC Entrance Exam","ACJ Entrance Exam","IIMC Entrance Exam","JMI Entrance Exam","NCHMCT JEE","Ecole Hoteliere Lavasa Entrance Exam","Oberoi STEP Exam","DTE HMCT Entrance Exam","JEE AAT","picasso animation college entrance exam","arena animation academy entrance exam","TISSNET","HSEE","JNAFAU Entrance Exam","BHU UET BFA");

	
		$subValsArray = array('Counselling','Cut-off','Eligibility','GDPI','Dates','Pattern','Preparation','Process','Scores','Coaching Centres','colleges accepting',"Answer Key","Admit Card","Selection Process","Provisional Allotment Process","Centres","Application Process","Syllabus","Notifications","Application Form","Sample Papers","Application Fee");
	
		foreach ($newTagNames as $tagName) {
			$data = array();
			$data['tagName'] = $tagName;
			$data['tagDescription'] = "";
			$data['tagEntity'] = "Exams";
			$parentTagId = $this->scriptModel->addTag($data);

			//Exams varients
			foreach ($subValsArray as $varients) {
				$data = array();
				if($varients == "colleges accepting"){
					$data['tagName'] = $varients." ".$tagName;
				}else{
					$data['tagName'] = $tagName." ".$varients;	
				}
				
				$data['tagDescription'] = "";
				$data['tagEntity'] = "Exams varients";
				$tagId = $this->scriptModel->addTag($data);
				$temp = array();
				$temp['tag_id']= $tagId;
				$temp['parent_id']= $parentTagId;
				$temp['creationTime']=date("Y-m-d H:i:s");
				$parentTagMappingList[] = $temp;
			}
		}
		$this->scriptModel->createParentChildMapping($parentTagMappingList);

	}

	function oldTagsVarients(){
		$oldTagsArray = array("387247" => "CMAT","387248" => "IIFT","387249" => "MPPET","387250" => "IBSAT","387251" => "MANIPAL - ENAT","387252" => "MANIPAL - ENAT - Engineering","387253" => "MANIPAL - ENAT - Architecture","387254" => "CAT","387255" => "JEE Mains","387256" => "XAT","387257" => "JEE","387258" => "JEE Advanced","387259" => "BITSAT","387260" => "NMAT","387261" => "COMEDK","387262" => "COMEDK - Engineering","387263" => "COMEDK - Medical","387264" => "UPSEE","387265" => "UPSEE - Engineering","387266" => "UPSEE - Medical","387267" => "UPSEE - Architecture","387268" => "UPSEE - MBA","387269" => "SNAP","387270" => "MAT","387271" => "KEAM","387272" => "KEAM - Engineering","387273" => "KEAM - Medical","387274" => "KEAM - Architecture","387275" => "RPET","387276" => "AP EAMCET","387277" => "AP EAMCET - Engineering","387278" => "AP EAMCET - Medical","387279" => "VITEEE","387280" => "KIITEE","387281" => "KIITEE - Engineering","387282" => "KIITEE -Medical","387283" => "KIITEE - law","387284" => "KIITEE MBA","387285" => "KCET","387286" => "KCET - Engineering","387287" => "KCET -Medical","387288" => "TNEA","387289" => "WBJEE","387290" => "WBJEE - Engineering","387291" => "WBJEE - Medical","387292" => "AEEE","387293" => "UPES-EAT","387294" => "UPES-EAT-Engineering","387295" => "UPES-EAT-Design","387296" => "PESSAT","387297" => "PESSAT MBA","387298" => "GATE","387299" => "SRMJEEE","387300" => "MICAT","387301" => "JCECE","387302" => "JCECE - Engineering","387303" => "JCECE - Medical","387304" => "VITMEE","387305" => "ATMA","387306" => "NATA","387307" => "LPU-NEST","387308" => "MU-OET","387309" => "MU-OET - Engineering","387310" => "MU-OET - Medical","387311" => "MU-OET - Hotel Management","387312" => "OJEE","387313" => "OJEE - Medical","387314" => "OJEE - MBA","387315" => "AUEET","387316" => "MAH-CET","387317" => "IIHM eCHAT","387318" => "CLAT","387319" => "NIFT","387320" => "CUSAT CAT","387321" => "NAT","387322" => "LSAT","387323" => "GCET","387324" => "GCET - Engineering","387325" => "GCET - Medical","387326" => "AJEE","387327" => "AJEE - Engineering","387328" => "AJEE - Law","387329" => "AJEE - MBA","387330" => "TS EAMCET","387331" => "TS EAMCET - Engineering","387332" => "UPES MET","387333" => "TS EAMCET - Medical","387334" => "USMLE","387335" => "TOEFL","387336" => "SAT","387337" => "IELTS","387338" => "GRE","387339" => "GMAT","387340" => "MCAT","387341" => "PTE","387342" => "CAEL","387343" => "MELAB","387344" => "SAT subject","387345" => "ACT","387346" => "LNAT","387347" => "UKCAT","387348" => "PLAB","387349" => "OET","387350" => "CAPF","387351" => "ISS","387352" => "SCRA","387353" => "Geologists exam","387354" => "Combined Medical Services exam","387355" => "State Public Service Commision exam","387356" => "CGL","387357" => "CHSL","387358" => "Sub-Inspector in CPO Exam","387359" => "Section Officer  exam","387360" => "Junior Engineer exam","387361" => "FCI exam","387362" => "CDS","387363" => "NDA","387364" => "IBPS clerk","387365" => "IBPS PO","387366" => "SBI clerk","387367" => "SBI PO","387368" => "IBPS","418960" => "NEET","418974" => "UPSC");

		$subValsArray = array("Answer Key","Admit Card","Selection Process","Provisional Allotment Process","Centres","Application Process","Syllabus","Notifications","Application Form","Sample Papers","Application Fee");

		$parentTagMappingList = array();
		foreach ($oldTagsArray as $parentTagId => $tagName) {
			foreach ($subValsArray as $varients) {
				$data = array();
				$data['tagName'] = $tagName." ".$varients;	
				$data['tagDescription'] = "";
				$data['tagEntity'] = "Exams varients";
				$tagId = $this->scriptModel->addTag($data);
				$temp = array();
				$temp['tag_id']= $tagId;
				$temp['parent_id']= $parentTagId;
				$temp['creationTime']=date("Y-m-d H:i:s");
				$parentTagMappingList[] = $temp;
			}
		}

		$this->scriptModel->createParentChildMapping($parentTagMappingList);

	}

	/*
		create Tags in shiksha and add mapping tags with shiksha entities
	*/
	function addTagsInShiksha($sheetName){
		ini_set('memory_limit',-1);
		$file_name = "/var/www/html/shiksha/public/Shiksha_Tag_Excel_prod.xlsx";
        $this->load->library('common/PHPExcel');
        $objReader= PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel=$objReader->load($file_name);
        //$objWorksheet=$objPHPExcel->setActiveSheetIndex(0);
        //$objWorksheet = $objPHPExcel->getActiveSheet();
		$objWorksheet = $objPHPExcel->getSheetNames();
		$sheetCount   = $objPHPExcel->getSheetCount();

		$configArray = array('careers' => 'Careers','country' => 'Country','state' => 'State', 'city' => 'City', 'colleges'=> 'Colleges','abroadUniversity' => 'University','educationType' => 'Education Type','deliveryMethod' => 'Delivery Method','credential' => 'Credential', 'courseLevel' => 'Course Level', 'stream' => 'Stream', 'substream' => 'Sub-Stream' , 'specialization' => 'Specialization', 'baseCourse' => 'Course', 'exams' => 'Exams');

		$createNewTags = array('Careers','Country','State','City','Colleges','University');

		$tagMappingComb = array('Education Type','Delivery Method','Credential','Course Level', 'Stream', 'Sub-Stream', 'Specialization', 'Course', 'Exams');

		$highestRowForSheet = array('careers' => 203,'country' => 77,'state' => 299, 'city' => 1409, 'colleges'=> 974,'abroadUniversity' => 500,'educationType' => 3, 'deliveryMethod' => 7, 'credential' => 4, 'courseLevel' => 7, 'stream' => 20, 'substream' => 106, 'specialization' => 541, 'baseCourse' => 138, 'exams' => 244);

		if(!array_key_exists($sheetName, $configArray))
		{
			echo 'Wrong Sheet Name';
			die;
		}

       
		//for($i=0;$i<$sheetCount;$i++){
		$sheetPos = array_search($sheetName, array_keys($configArray));

			$objWorksheet = $objPHPExcel->setActiveSheetIndex($sheetPos);
			$highestRow = $highestRowForSheet[$sheetName];//$objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);

			$headingsArray = $headingsArray[1];
			//_p($headingsArray);die;
			for ($row = 2; $row <= $highestRow; ++$row) {
				$dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
				$dataRow = $dataRow[$row];
				if(empty($dataRow['D']) || $dataRow['D'] == 'NULL')
				{
					continue;
				}

				if(in_array($configArray[$sheetName], $createNewTags))
				{
					$this->createShikshaTagForEntities($dataRow,$configArray,$sheetName,$headingsArray);
				}
				elseif(in_array($configArray[$sheetName], $tagMappingComb))
				{
					$this->updateShikshaEntitiesForNewMappings($dataRow,$configArray,$sheetName,$headingsArray);
				}
			}
			_p('Done');
			//}

	}
	function createShikshaTagForEntities($dataRow,$configArray,$sheetName, $headingsArray)
	{
		if(!empty($dataRow['A']) && !empty($dataRow['C']) && $dataRow['C'] != 'NULL' && $dataRow['A'] != 'NULL')
				{
					if($configArray[$sheetName] == 'Colleges')
					{
						$synonymsArray = explode(';;', $dataRow['E']);
					}
					else
					{
						$synonymsArray = explode(',', $dataRow['E']);
					}
					$this->addSynonymsOfTags($dataRow['C'],$synonymsArray,$configArray[$sheetName].' synonym');
					//$isMappingExist = $this->scriptModel->isTagMappingWithEntityExist($dataRow['A'],$dataRow['C'],$objWorksheet[$i],true);
					$this->isTagMappingWithEntityExist($dataRow['A'],$dataRow['C'],$configArray[$sheetName],true);
				}
				elseif (((!empty($dataRow['A']) && $dataRow['A'] != 'NULL') && (empty($dataRow['C']) || $dataRow['C'] == 'NULL')) || ((empty($dataRow['A']) || $dataRow['A'] == 'NULL') && (empty($dataRow['C']) || $dataRow['C'] == 'NULL'))) {

					$isExist = $this->scriptModel->isTagAlreadyExists($dataRow['D']);

					if($isExist === true)
					{
						error_log('Tag Already Exists => '.$dataRow['D']);
						if(((!empty($dataRow['A']) && $dataRow['A'] != 'NULL') && (!empty($dataRow['C']) && $dataRow['C'] != 'NULL')))
						{
							$this->isTagMappingWithEntityExist($dataRow['A'],$dataRow['C'],$configArray[$sheetName],true);
						}
						return;
					}

					$tagNewId = $this->scriptModel->createTagInShiksha($dataRow['D'],$configArray[$sheetName]);
					if($configArray[$sheetName] == 'Colleges')
					{
						$synonymsArray = explode(';;', $dataRow['E']);
					}
					else
					{
						$synonymsArray = explode(',', $dataRow['E']);
					}
					$this->addSynonymsOfTags($tagNewId,$synonymsArray,$configArray[$sheetName].' synonym');
					$this->addVarientsOfTags($tagNewId,$dataRow['D'],$configArray[$sheetName]);
					if(((!empty($dataRow['A']) && $dataRow['A'] != 'NULL') && (empty($dataRow['C']) || $dataRow['C'] == 'NULL')))
					{
						$this->isTagMappingWithEntityExist($dataRow['A'],$tagNewId,$configArray[$sheetName],true);
					}
					$parentIdExist = array_search('Parent Tag Id', $headingsArray);
					if(!empty($tagNewId) && !empty($parentIdExist) && !empty($dataRow[$parentIdExist]))
					{
						$this->scriptModel->addMappingWithParent($tagNewId,$dataRow[$parentIdExist]);
					}
				}
	}
	function updateShikshaEntitiesForNewMappings($dataRow,$configArray,$sheetName, $headingsArray)
	{
		
			$tagNewId = $this->scriptModel->updateShikshaEntitiesForNewMappings($dataRow['D'],$configArray[$sheetName]);
			$synonymsArray = explode(',', $dataRow['E']);
			$this->addSynonymsOfTags($tagNewId,$synonymsArray,$configArray[$sheetName].' synonym',true);
			$this->addVarientsOfTags($tagNewId,$dataRow['D'],$configArray[$sheetName],true);
			if(((!empty($dataRow['A']) && $dataRow['A'] != 'NULL') && !empty($tagNewId)))
			{
				$this->isTagMappingWithEntityExist($dataRow['A'],$tagNewId,$configArray[$sheetName],true);
			}
			$parentIdExist = array_search('Parent Tag Id', $headingsArray);
			if(!empty($tagNewId) && !empty($parentIdExist) && !empty($dataRow[$parentIdExist]) && $dataRow[$parentIdExist] != 'NULL')
			{
				$this->scriptModel->addMappingWithParent($tagNewId,$dataRow[$parentIdExist]);
			}
			elseif(!empty($tagNewId) && ( empty($parentIdExist) || empty($dataRow[$parentIdExist]) || $dataRow[$parentIdExist] == 'NULL'))
			{
				$parentIdExist = array_search('Parent Entity Id1', $headingsArray);
				if(!empty($parentIdExist) && !empty($dataRow[$parentIdExist]) && $dataRow[$parentIdExist] != 'NULL')
				{
					$parentEntityType = 'Sub-Stream';
				}
				else{
					$parentIdExist = array_search('Parent Entity Id2', $headingsArray);
					if(!empty($parentIdExist) && !empty($dataRow[$parentIdExist]) && $dataRow[$parentIdExist] != 'NULL')
					{
						$parentEntityType = 'Stream';
					}
				}
				if(!empty($parentEntityType))
				{
					$this->scriptModel->isParentExistAddMapping($tagNewId,$dataRow[$parentIdExist],$parentEntityType);
				}
			}

			if($configArray[$sheetName] == 'Course')
			{
				$tagLib = $this->load->library('Tagging/TaggingCreationLib');
				$courseModesArray = $tagLib->showCourseModes($dataRow['D']);
				foreach ($courseModesArray as $modeKey => $modeValue) {
					if(!empty($modeValue))
					{
						$tagModeNewId = $this->scriptModel->updateShikshaEntitiesForNewMappings($modeValue,'Course Modes');
						if(!empty($tagNewId) && !empty($tagModeNewId))
						{
							$this->scriptModel->addMappingWithParent($tagModeNewId,$tagNewId);
						}
					}
				}
				$this->createCourseSubStreamSpec($dataRow['D'],'Sub-Stream',$tagNewId);
				$this->createCourseSubStreamSpec($dataRow['D'],'Specialization',$tagNewId);
				
			}

	}
	function createCourseSubStreamSpec($tagName,$entityName,$tagNewId)
	{
		$tagLib = $this->load->library('Tagging/TaggingCreationLib');
		$courseSubStreamsArray = $tagLib->showCourseSubstreamsSpec($tagName,$entityName);
				foreach ($courseSubStreamsArray as $csKey => $csValue) {
					if(!empty($csValue))
					{
						if($entityName == 'Sub-Stream')
						{
							$tagEntityType = 'Course substreams';
						}
						else
						{
							$tagEntityType = 'Course specializations';
						}
						$tagModeNewId = $this->scriptModel->updateShikshaEntitiesForNewMappings($csValue,$tagEntityType);
						if(!empty($tagNewId) && !empty($tagModeNewId))
						{
							$this->scriptModel->addMappingWithParent($tagModeNewId,$tagNewId);
						}
						if(!empty($tagModeNewId) && !empty($csKey))
						{
							$this->scriptModel->addMappingWithParent($tagModeNewId,$csKey);	
						}
					}
				}
	}
	function isTagMappingWithEntityExist($entityId,$tagId,$entityTye,$insert=false)
	{
		$tagEntityType = $entityTye;
		if($entityTye == 'Colleges')
		{
			$this->load->builder("nationalInstitute/InstituteBuilder");
			$instituteBuilder = new InstituteBuilder();
			$this->instituteRepo = $instituteBuilder->getInstituteRepository();

        	$instituteObj = $this->instituteRepo->find($entityId,array('basic'));
        	$listingType = $instituteObj->getType();
        	if(strtolower($listingType) == 'university')
        	{
        		$tagEntityType = 'National-University';
        	}
        	else
        	{
        		$tagEntityType = 'institute';
        	}

		}
		error_log('Tagging &&'.$entityId.'/'.$tagId.'/'.$tagEntityType);
		$isMappingExist = $this->scriptModel->isTagMappingWithEntityExist($entityId,$tagId,$tagEntityType,true);
	}
	function addSynonymsOfTags($mainId,$synonymsArray,$tagEntity = '',$isUpdateCall = false)
	{	
		if(is_array($synonymsArray) && count($synonymsArray) > 0 && !empty($mainId))
		{
			foreach ($synonymsArray as $key => $synonymText) {
				$synonymText = trim($synonymText);
				if(!empty($synonymText) && $synonymText != 'NULL')
				{
					if($isUpdateCall)
					{
						$this->scriptModel->updateSynonymsOfTags($mainId,$synonymText,$tagEntity);
					}
					else
					{
						$isExist = $this->scriptModel->isSynonymsExist($synonymText,$tagEntity);
						if($isExist === FALSE)
						{
							$this->scriptModel->createSynonymForTags($mainId,$synonymText,$tagEntity);
						}
					}
				}
			}
		}
	}
	function addVarientsOfTags($mainId,$tagName,$tagEntity,$isUpdateCall = false)
	{
		/*$tagName = 'Hotel Management';
		$tagEntity = 'Substream';*/
		$tagLib = $this->load->library('Tagging/TaggingCreationLib');
		$varients = $tagLib->showVarients($tagName,$tagEntity);
		$varientsTagId = array();
		foreach ($varients as $key => $value) {
			if($isUpdateCall)
			{
				$varientsTagId[] = $this->scriptModel->updateShikshaEntitiesForNewMappings($value,$tagEntity.' varients');
			}
			else
			{
				$varientsTagId[] = $this->scriptModel->createTagInShiksha($value,$tagEntity.' varients');
			}
		}

error_log('Tagging varients'.$tagNewId.'/'.print_r($varientsTagId,true));
		//adding mapping vareints with it's actual tag
		foreach ($varientsTagId as $key => $value) {
			if(!empty($value))
			{
				/*$temp= array();
				$temp['tag_id'] = $value;
				$temp['parent_id'] = $main_id;
				$parentTagMappingList[] = $temp;*/
				$this->scriptModel->addMappingWithParent($value,$mainId);		
			}
		}
		/*if(!empty($parentTagMappingList))
		{
			$this->scriptModel->addMappingWithParent($parentTagMappingList);		
		}*/
	}
	function duplicateTagsRemove()
	{
		ini_set('memory_limit',-1);
		$file_name = "/var/www/html/shiksha/public/duplicateTagsWithIds1.xlsx";
        $this->load->library('common/PHPExcel');
        $objReader= PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel=$objReader->load($file_name);
        //$objWorksheet=$objPHPExcel->setActiveSheetIndex(0);
        //$objWorksheet = $objPHPExcel->getActiveSheet();
		$objWorksheet = $objPHPExcel->getSheetNames();
		$sheetCount   = $objPHPExcel->getSheetCount();
       

			$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
			//$highestRow = $highestRowForSheet[$sheetName];//$objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);

			$headingsArray = $headingsArray[1];
			$duplicateArray = array();
			for ($row = 2; $row <= 1529; ++$row) {
				$dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
				$dataRow = $dataRow[$row];
				if(!empty($dataRow['C']) || $dataRow['D'] != 'NULL')
				{
					if(array_key_exists($dataRow['C'], $duplicateArray))
					{
						$duplicateArray[$dataRow['C']]['tag_id2'] = $dataRow['A'];
						$duplicateArray[$dataRow['C']]['tag_type2'] = $dataRow['B'];
					}
					else
					{
						$duplicateArray[$dataRow['C']]['tag_id1'] = $dataRow['A'];
						$duplicateArray[$dataRow['C']]['tag_type1'] = $dataRow['B'];	
					}
					
				}
			}
			foreach ($duplicateArray as $key => $value) {
				if(array_key_exists('tag_id1', $value) && array_key_exists('tag_id2', $value) && $value['tag_type1'] == $value['tag_type2'])
				{
					
					$parentTags = $this->scriptModel->getParentTags($value['tag_id2']);
					$chidTags = $this->scriptModel->getChildTags($value['tag_id2']);
					foreach ($parentTags as $pkey => $pvalue) {
						if(!empty($pvalue) && $value['tag_id1'] != $pvalue)
						{
							$this->scriptModel->addMappingWithParent($value['tag_id1'],$pvalue);
						}
					}
					foreach ($chidTags as $ckey => $cvalue) {
						if(!empty($cvalue) && $cvalue != $value['tag_id1'])
						{
							$this->scriptModel->addMappingWithParent($cvalue,$value['tag_id1']);
						}
					}
					$this->scriptModel->updateContentMapping($value['tag_id2'],$value['tag_id1']);

					$this->scriptModel->removeDuplicates($value['tag_id2'],$value['tag_id1']);
				}
			}
		}
		function updateStreamTags()
		{
			$streamTags = array(
								array('tag_id1' => 430369,
									   'tag_id2' => 8),
								array('tag_id1' => 429912,
									   'tag_id2' => 26),
								array('tag_id1' => 430824,
									   'tag_id2' => 12),
								array('tag_id1' => 83,
									   'tag_id2' => 11),
								array('tag_id1' => 365,
									   'tag_id2' => 27),
									);

			$streamNames = array(
											array(
													'old' => 'Banking,Finance & Insurance',
													'new' => 'Banking, Finance & Insurance'
												),
											array(
													'old' => 'Fine Arts & Performing Arts',
													'new' => 'Arts ( Fine / Visual / Performing )'
												),
											array(
													'old' => 'Medical',
													'new' => 'Medicine & Health Sciences'
												),
											array(
													'old' => 'Nursing & Health Sciences',
													'new' => 'Nursing'
												),
											array(
													'old' => 'Animation & Multimedia',
													'new' => 'Animation'
												)
										);
					$tagLib = $this->load->library('Tagging/TaggingCreationLib');

					foreach ($streamNames as $skey => $svalue) {
						$oldvarients = $tagLib->showVarients($svalue['old'],'Stream');
						$newvarients = $tagLib->showVarients($svalue['new'],'Stream');

							foreach ($oldvarients as $okey => $ovalue) {
							$oldTagId = $this->scriptModel->getTagId($ovalue);
							$newTagId = $this->scriptModel->getTagId($newvarients[$okey]);

							if(!empty($oldTagId) && !empty($newTagId))
							{
								$this->scriptModel->updateContentMapping($oldTagId,$newTagId);
							}
						}
					}



			foreach ($streamTags as $key => $value) {
					$this->scriptModel->updateContentMapping($value['tag_id2'],$value['tag_id1']);
					$this->scriptModel->removeStreams($value['tag_id2']);
				}	
		}
		function deleteTagsFromSolr()
		{
			$deletedTags = $this->scriptModel->deleteTagsFromSolr();
		}
		function autoTaggingToQnA()
		{
			$bucketCount = 500;
			$creationTime  = '2018-05-30 15:20:00';
			$endTime  = '2018-05-30 15:20:00';
			$limit = $bucketCount;
			$anamodel = $this->load->model('messageBoard/anamodel');
			$redisLib = PredisLibrary::getInstance();

			$count = $anamodel->getQuestionsCountDataFromSpecificDate($creationTime,$endTime);
			$numberOfBuckets = ceil($count / $bucketCount);
			for ($i=0; $i < $numberOfBuckets; $i++) {
				$offset = $limit * $i;
				if(!empty($offset))
					$offset = $offset + 1;
				$result = $anamodel->getQuestionsDataFromSpecificDate($creationTime,$endTime,$offset,$limit);

				$this->load->library('Tagging/TaggingLib');
	            $taggingLib = new TaggingLib();

				$mappingMsgIdQuestion = array();
				$mappingMsgIdInstituteId = array();
				$questionIds = array();
				foreach ($result as $key => $value) {
					$mappingMsgIdQuestion[$value['msgId']] = $value['msgTxt'];
					$questionIds[] = $value['msgId'];
					if(!empty($value['instituteId']))
						$mappingMsgIdInstituteId[$value['msgId']] = $value['instituteId'];
				}
				$autoTags = array();
				$tagExistIds = array();
				if(!empty($questionIds))
				{
					$tempTags = $taggingLib->checkTagsExistOnDB($questionIds);
					foreach ($tempTags as $key => $value) {
							$tagExistIds[] = $value['content_id'];
						}	
				}
				foreach ($mappingMsgIdQuestion as $key => $value) {
					if(in_array($key, $tagExistIds))
					{
						unset($mappingMsgIdQuestion[$key]);
					}
				}
				
				if(!empty($mappingMsgIdQuestion))
					$autoTags = $taggingLib->showTagSuggestions($mappingMsgIdQuestion,'normal',false,true);
				$autoTagDetails = array();
				if(is_array($autoTags))
				{
					foreach ($autoTags as $key => $value) {
						foreach ($value['objective'] as $obkey => $obvalue) {
							if(!empty($obvalue))
							{
								$temp = array('tagId' => $obvalue,'classification' => 'objective');
								$autoTagDetails[$key][] = $temp;
							}
						}
						foreach ($value['background'] as $obkey => $obvalue) {
							if(!empty($obvalue))
							{
								$temp = array('tagId' => $obvalue,'classification' => 'background');
								$autoTagDetails[$key][] = $temp;
							}
						}
					}
				}

				foreach($mappingMsgIdInstituteId as $key => $value)
                {
                    if(!array_key_exists($key,$autoTagDetails))
                    {       
                        $autoTagDetails[$key] = array();
                    }       
                }
		function autoTaggngOnQuestions()
		{
			$anamodel = $this->load->model('messageBoard/anamodel');
			$taggingLib = $this->load->library('TaggingLib');
			$result = $anamodel->getQuestionsAfterSpecificDate('2018-02-11 00:00:00',0,500);
			foreach ($result as $rkey => $rvalue) {
				$tags = $taggingLib->showTagSuggestions(array('CAT 2017 pattern changes: How should we interpret them?'));
				print_r($tags);
				die('12');
			}
		}

				
				$autoAllTagDetails = array();
				if(!empty($autoTagDetails))
				{
					foreach ($autoTagDetails as $key => $value) {
						$autoAllTagDetails[$key] = $taggingLib->findTagsToBeAttached($value);
					}
				}
				foreach ($autoAllTagDetails as $key => $value) {
//					$taggingLib->deleteTagsWithContentToDB($key);
					$listingTypeId = null;
					if(array_key_exists($key, $mappingMsgIdInstituteId))
					{
						$listingTypeId = $mappingMsgIdInstituteId[$key];
					}
		       		$taggingLib->insertTagsWithContentToDB($value,$key,"question",$action="threadpost",$editType="editEntity",$listingTypeId,$extralistingArray, $extraParam);
		       		$redisLib->addMembersToHash('questionPostedLastNMins',array($key => 'edit'));
				}	
			}
		}
		function autoTaggingToQnAFromRedis()
		{
			$bucketCount = 500;
			$creationTime  = '2017-10-18 15:20:00';
			$endTime  = '2018-01-30 15:20:00';
			$limit = $bucketCount;
			$anamodel = $this->load->model('messageBoard/anamodel');
			$taggingmodel = $this->load->model('Tagging/taggingmodel');
			$redisLib = PredisLibrary::getInstance();

			$count = $anamodel->getQuestionsCountDataFromSpecificDate($creationTime,$endTime);
			$numberOfBuckets = ceil($count / $bucketCount);
			for ($i=0; $i < $numberOfBuckets; $i++) {
				$offset = $limit * $i;
				if(!empty($offset))
					$offset = $offset + 1;
				$result = $anamodel->getQuestionsIdsFromSpecificDate($creationTime,$endTime,$offset,$limit);
				$msgIds = array();
				$redisMsgIds = array();
				foreach ($result as $key => $value) {
					$msgIds[] = $value['msgId'];
					$redisMsgIds[] = 'threadTags:thread:'.$value['msgId'];
				}
				$tagsMappingMsgIdDB = $taggingmodel->fetchAllTagsOnContentIds($msgIds,'question');
				$redisLib->setPipeLine();	
				foreach ($redisMsgIds as $key => $value) {
					$redisLib->getMembersInSortedSet($value, 0 , -1, TRUE, FALSE,TRUE);	
				}
				$tagsFromRedis = $redisLib->executePipeLine();
				$tagsMappingMsgIdRedis = array();
				foreach ($tagsFromRedis as $key => $value) {
					$tagsMappingMsgIdRedis[$msgIds[$key]] = $value;
				}
				foreach($tagsMappingMsgIdRedis as $tKey => $tValue)
				{
					if(!empty($tagsMappingMsgIdDB[$tKey]))
					{
						$temp = array_diff($tValue,$tagsMappingMsgIdDB[$tKey]);
						if(!empty($temp))
						{
							$tagsMappingMsgIdRedis[$tKey] = $temp;	
						}
						else
						{
							unset($tagsMappingMsgIdRedis[$tKey]);
						}
					}
				}
				if(!empty($tagsMappingMsgIdRedis))
					$taggingmodel->insertTagsToDBFromRedis($tagsMappingMsgIdRedis);
			}
		} 
}
?>

