<?php
class TagUrlMapping
{
	public static $order = array('manual','manual parent','manual_parent','objective','objective_parent','objective parent','background','background_parent');

	public function init()
	{
		$this->CI = & get_instance();
		$this->CI->load->model('Tagging/taggingmodel');	
	}
	public function getTagsUrl($tagsData)
	{
		$this->init();
		$tagmodel = new TaggingModel();
		$tagsArr = array_keys($tagsData);
		$tagsArrTemp = $tagsArr;

		$tagEntityDetails = $tagmodel->getTagDetails($tagsArr);
		//_p($tagEntityDetails);
		//die;
		foreach($tagEntityDetails as $k=>$v)
		{
			$tagEntityGroup[$v['entity_type']][]=$v['entity_id'];
			$tagIdMap[$v['entity_id']] = $v['tag_id'];
		}
		  //_p($tagEntityGroup);
		 // die;
		//_p($tagsArr);
		foreach($tagEntityGroup as $entityGroup=>$tagsInEntityGroup)
		{
			//_p($tagsInEntityGroup);die;
			switch($entityGroup)
			{
				case "institute":
				case "National-University":
				case "Careers":
				case "Exams":

					//getExamIds($tagsInEntityGroup);
					$repositoryObject = self::getGroupObject($entityGroup);
					$multiObjectArray = $repositoryObject->findMultiple($tagsInEntityGroup);
					foreach($multiObjectArray as $n=>$obj)
					{
						//_p($obj);die;
						if($entityGroup!="Careers")
						{
							$finalUrlMapping[$tagIdMap[$obj->getId()]]=array("type"=>$entityGroup,"url"=>$obj->getUrl(),"entityId"=>$obj->getId());
							unset($tagsInEntityGroup[$obj->getId()]);
						}	
						else
						{
							$finalUrlMapping[$tagIdMap[$obj->getCareerId()]]=array("type"=>$entityGroup,"url"=>$obj->getCareerUrl(),"entityId"=>$obj->getCareerId());
						}
						//_p($finalUrlMapping);die;
					}
					break;

				case "Stream":
				case "Sub-Stream":
				case "desired-course":
				case "Specialization":
				break;

			}
		}
		//_p($finalUrlMapping);
		//$nonEntity = array_diff($tagsArr, array_keys($finalUrlMapping));
		if(is_array($finalUrlMapping))
		{
			$nonEntity = array_diff($tagsArr, array_keys($finalUrlMapping));
		}
		else
		{
			$nonEntity = $tagsArr;
		}
		if(!empty($nonEntity))
		{
			$this->CI->load->model('Tagging/statictagurlmapmodel');
			$staticTagUrlMapModelObj = new statictagurlmapmodel;
			$tagUrls = $staticTagUrlMapModelObj->findTagUrl($nonEntity);
		}
		if(is_array(($tagUrls)))
		{
			foreach($tagUrls as $k=>$v)
			{
				$finalUrlMapping[$v['tagId']]=array("type"=>$v['type'],"url"=>SHIKSHA_HOME."/".$v['url']);
			}
		}
//_p($finalUrlMapping);die;
		if(is_array($finalUrlMapping))
		{
			$nonEntity = array_diff($tagsArr, array_keys($finalUrlMapping));
		}
		else
		{
			$nonEntity = $tagsArr;
		}
		if(!empty($nonEntity))
		{
			foreach($nonEntity as $k=>$v)
			{
				$finalUrlMapping[$v] = array("url"=>getSeoUrl($v, 'tag', $tagsData[$v]),"type"=>"ne");
			}
		}
		return $finalUrlMapping;
		//_p($finalUrlMapping);die;
	}
	public function getGroupObject($entity)
	{
		switch($entity)
		{
			case "Exams":
				$this->CI->load->builder('examPages/ExamBuilder');
                $examBuilder    = new ExamBuilder();
                $examRepository = $examBuilder->getExamRepository();
                return $examRepository;
                break;
                //$examIds = array_filter($examIds);
                //$multiExamObject = $examRepository->findMultiple($examIds);
            case "National-University":
            case "institute":
                $this->CI->load->builder("nationalInstitute/InstituteBuilder");
                $instituteBuilder = new InstituteBuilder();
                $instituteRepository = $instituteBuilder->getInstituteRepository();
                return $instituteRepository;
                break;
            case "Careers":
                $this->CI->load->builder('Careers/CareerBuilder');
                $careerBuilder = new CareerBuilder();
                $careerRepository = $careerBuilder->getCareerRepository();
                return $careerRepository;
            	break;
        }
        return;
	}
	public static function arr_sort($a,$b)
	{
  		foreach (self::$order as $key => $value) 
  		{
    		if ($value==$a['tag_type']) 
    		{
      			return 0;
      			break;
    		}
    		if ($value==$b['tag_type']) 
    		{
      			return 1;
      			break;
    		}
  		}
	}
	public function getTagsContentType($tagIds,$content_ids)
	{
		$this->init();
		$tagmodel = new TaggingModel();
		if(!is_array($content_ids))
		{
			$content_ids = array($content_ids);
		}
		$tagTypeInfo = $tagmodel->getContentType(array_keys($tagIds),$content_ids);
		foreach($tagTypeInfo as $k=>$v)
		{
			$finalInfo[$v['content_id']][$v['tag_id']]=$v['tag_type'];
		}
		return $finalInfo;
	}

	public function getTagsExistOnEntityTypes($tagIds,$entity_type=array('Stream'))
	{
		$this->init();
		$tagmodel = new TaggingModel();
		$tagsInfo = array();
		if(!empty($tagIds) && is_array($tagIds) && !empty($entity_type))
		{
			$tagsInfo = $tagmodel->getTagsType($tagIds);	
		}
		$result = array();
		foreach ($tagsInfo as $tagkey => $tagvalue) {
			if(in_array($tagvalue['entity_type'], $entity_type))
			{
				$result[$tagvalue['tag_id']] = array('entity_type' => $tagvalue['entity_type'],'entity_id' => $tagvalue['entity_id']);
			}
		}
		return $result;
	}
public static function sortTags($data)
{
	usort($data,'self::arr_sort');
	return $data;
}
}
?>
