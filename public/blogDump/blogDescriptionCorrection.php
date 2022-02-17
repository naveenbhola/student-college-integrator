<?php
error_reporting(E_ALL);

$conn = mysql_connect('localhost','shiksha','shiKm7Iv80l');

$db1 = mysql_select_db('shiksha',$conn);
$db2 = mysql_select_db('blogdump',$conn);


define("BLOG_PARSER_DATE", "2014-01-01 00:00:00");

correctDescription();
echo 'complete';
exit;

    function correctDescription(){

		$results      = getArticleResults('article');
		$tableArray1     = array('tableName'=>'shiksha.blogDescriptions','fieldName'=>'descriptionId','fieldName1'=>'description');
		toIterateResult('article',$results,array('blogImageURL','description','description','descriptionId','blogTitle'),$tableArray1);
		
			
		$results2     = getArticleResults('qna');
		$tableArray2     = array('tableName'=>'shiksha.blogQnA','fieldName'=>'id','fieldName1'=>'question','fieldName2'=>'answer');
		toIterateResult('qna',$results2,array('blogImageURL','qna','question','answer','id','blogTitle'),$tableArray2);
	
		$results5     = getArticleResults('sasection');
		$tableArray3     = array('tableName'=>'shiksha.study_abroad_content_sections','fieldName'=>'id','fieldName1'=>'details');
		toIterateResult('sasection',$results5,array('contentImageURL','images','details','id','strip_title'),$tableArray3);
    }



/**
     * iterate query results
     * @param  string $type
     * @param  array $result
     * @param  array $fieldsArray
     * @return true;
     */
    function toIterateResult($type,$result,$fieldsArray,$tableArray){

    	
    	foreach($result as $res){
    		   	  
    		$insertedString          = '';   
		    foreach($res[$fieldsArray[1]] as $des){
		


		    	if($type == 'qna'){
		    		$questionDesc = toCopyDescriptioImages($res[$fieldsArray[5]],$des[$fieldsArray[2]]);	
		    		$answerDesc   = toCopyDescriptioImages($res[$fieldsArray[5]],$des[$fieldsArray[3]]);
		    		$qnaId        = $des[$fieldsArray[4]];
					$insertedString .= "($qnaId,'$questionDesc','$answerDesc'),";	
		    	}elseif($type == 'sasection'){
					echo $des[$fieldsArray[4]];

		    		$details        = toCopyDescriptioImages($res[$fieldsArray[4]],$des[$fieldsArray[2]]);
					$imageId        = $des[$fieldsArray[3]];
					$insertedString .= "($imageId,'$details'),";
		    		
		    	}elseif($type == 'article'){
		    		$newDescription = toCopyDescriptioImages($res[$fieldsArray[4]],$des[$fieldsArray[2]]);	
					$descriptionId  = $des[$fieldsArray[3]];
		    		$insertedString .= "($descriptionId,'$newDescription'),";
		    	}
				
		    }
		   $insertedString =  rtrim($insertedString,',');
		   setBlogUpdateQuery($insertedString,$tableArray);
		}
    }


    /**
     * backup description images
     * @param  string $description
     * @return true              
     */
    function toCopyDescriptioImages($title,$description){
        $dom = new DOMDocument;
        $dom->loadHTML($description);
        //$dom->formatOutput=true;
        $anchors = $dom->getElementsByTagName('img');
        $html = '';

		if($anchors->length > 0)
		{
            foreach($anchors as $anchor)
            {
				$rel     = array(); 
				$relAtt1 = $anchor->getAttribute('src');
		        if($relAtt1 != '')
		        {
			        // checking the url contains mediadata/image and replacing with new path
                    $finalImageUrl = replaceImagePathWithNew($relAtt1);
              

                    // checking the tag has alt attribute or not
                    if ($anchor->hasAttribute('alt') AND ($relAtt = $anchor->getAttribute('alt')) !== '') 
                    {
                            $rel = preg_split('/\s+/', trim($relAtt));
                    }

                    // if alt tag is not their is url then insert  the title 
                    if(count($rel)<=0)
                    {
                            $rel[] = $title;
                    }
                    // set the alt and src atrribute in image tag
                    $anchor->setAttribute('src', $finalImageUrl);    
                    $anchor->setAttribute('alt', implode(' ', $rel));
                }
            }	



            foreach($dom->getElementsByTagName('body')->item(0)->childNodes as $element) {
                    $html .= $dom->saveHTML($element);
            }
        }else{
        	$html = $description;
        }


		$html = mb_convert_encoding($html, "HTML-ENTITIES", "UTF-8");

	    return addslashes($html);
   
    }



/**
     * replace image path function
     * @param  string $relAtt1
     * @return string
     */
    function replaceImagePathWithNew($relAtt1)
    {
        // checking the url contains mediadata/image and replacing with new path

        if(preg_match('/mediadata\/images\/articles/', $relAtt1) == 0)
        {
            if (strpos($relAtt1,'mediadata/images/') !== false) 
            {
                    $finalImageUrl = str_replace("mediadata/images/","mediadata/images/articles/",$relAtt1);
            }else
            {
                    $finalImageUrl= $relAtt1;	
            }
        }else{
            $finalImageUrl = $relAtt1;
        }

        return $finalImageUrl;
    }













  /**
    * return  data array of the blog 
    * @param  string $type type of article
    * @return Array
    */
   function getArticleResults($type){

        //fetching the query string based on blog type
        switch ($type){
        case article:
        $param            = _queryArticle('article');
        break;
        case slideshow:
        $param            = _queryArticle('slideshow');
        break;
        case qna:
        $param            = _queryArticle('qna');
        break;
        case images:
        $param            = _queryArticle('images');
        break;
        case saimages:
        $param            = _queryArticle('saimages');
        break;
        case sasection:
        $param            = _queryArticle('sasection');
        break;
        }


       
        //executing the query
        $queryRes         = mysql_query($param['query']);
                

        $firstArrayResult = firstArrayResults($queryRes,$param);
        mysql_data_seek($queryRes, 0);
        $results          = secondArrayResults($queryRes,$param,$firstArrayResult);

      
        return $results;
   }
   
   /**
    * 
    * @param  Array $queryRes Array after executing the query
    * @param  Array $param
    * @return Array
    */
   function firstArrayResults($queryRes,$param){

        while($row = mysql_fetch_array($queryRes)){
            if(count($param['arrayFirst']) == 3){
                $arrayDescription[$row[$param['arraySecond'][0]]][]=array($param['arrayFirst'][0]=>$row[$param['arrayFirst'][0]],$param['arrayFirst'][1]=>$row[$param['arrayFirst'][1]],$param['arrayFirst'][2]=>$row[$param['arrayFirst'][2]]);
            }else{
                $arrayDescription[$row[$param['arraySecond'][0]]][]=array($param['arrayFirst'][0]=>$row[$param['arrayFirst'][0]],$param['arrayFirst'][1]=>$row[$param['arrayFirst'][1]]);
            }
         }
        return $arrayDescription;
    }
   
    /**
     * purifying the result array
     * @param  Array $queryRes Array after executing the query
     * @param  Array $param
     * @param  Array $firstArrayResult
     * @return Array
     */
    function secondArrayResults($queryRes,$param,$firstArrayResult){

          while($row = mysql_fetch_array($queryRes)){
            if(count($param['arraySecond'])== 5){
                $results[$row[$param['arraySecond'][0]]]= array(
                                            $param['arraySecond'][0]=> $row[$param['arraySecond'][0]],
                                            $param['arraySecond'][1]=> $row[$param['arraySecond'][1]],
                                            $param['arraySecond'][2]=> $row[$param['arraySecond'][2]],
                                            $param['arraySecond'][3]=> $row[$param['arraySecond'][3]],
                                            $param['arraySecond'][4]=>$firstArrayResult[$row[$param['arraySecond'][0]]]
                                           );
            }else{
                $results[$row[$param['arraySecond'][0]]]= array(
                                            $param['arraySecond'][0]=> $row[$param['arraySecond'][0]],
                                            $param['arraySecond'][1]=> $row[$param['arraySecond'][1]],
                                            $param['arraySecond'][2]=> $row[$param['arraySecond'][2]],
                                            $param['arraySecond'][3]=>$firstArrayResult[$row[$param['arraySecond'][0]]]
                                           );
            } 
          }


        return $results;
    }
   

   /**
    * All query based on type
    * @param  string $type
    * @return Array
    */
   function _queryArticle($type){
        $blogPaserDate = BLOG_PARSER_DATE;
        if($type       == 'article'){
        $query         = "SELECT a.blogId,a.blogTitle,a.blogImageURL,b.description,b.descriptionId  FROM blogdump.blogTable a JOIN blogdump.blogDescriptions b on a.blogId = b.blogId WHERE a.status in ('live','draft') and a.creationDate > '$blogPaserDate' ORDER BY a.blogId DESC";
        $arrayFirst    = array('description','descriptionId');
        $arraySecond   = array('blogId','blogTitle','blogImageURL','description');
        }elseif($type  == 'slideshow'){
        $query         = "SELECT a.blogId,a.blogTitle,a.blogImageURL, b.id,b.image  FROM blogdump.blogTable a join blogdump.blogSlideShow b ON a.blogId = b.blogId WHERE a.status in ('live','draft')   AND a.creationDate > '$blogPaserDate' ORDER BY a.blogId DESC";
        $arrayFirst    = array('id','image');
        $arraySecond   = array('blogId','blogTitle','blogImageURL','slideshowImages');   
        }elseif($type  == 'qna'){
        $query         = "SELECT a.blogId,a.blogTitle,a.blogImageURL,b.id,b.question,b.answer FROM blogdump.blogTable a join blogdump.blogQnA b ON a.blogId = b.blogId WHERE a.status in ('live','draft') AND a.creationDate > '$blogPaserDate' ORDER BY a.blogId DESC";
        $arrayFirst    = array('id','question','answer');
        $arraySecond   = array('blogId','blogTitle','blogImageURL','qna');   
        }elseif($type  == 'images'){
        $query         = "SELECT a.blogId,a.blogTitle,a.blogImageURL,b.blogImageId,b.imageURL FROM blogdump.blogTable a join blogdump.blogImages b ON a.blogId = b.blogId WHERE a.status in ('live','draft') AND a.creationDate > '$blogPaserDate'";
        $arrayFirst    = array('blogImageId','imageURL');
        $arraySecond   = array('blogId','blogTitle','blogImageURL','images');  
        }elseif($type  == 'saimages'){
        $query         = "SELECT a.id,a.content_id,a.title,a.contentImageURL,b.saContentimageId,b.imageURL FROM blogdump.study_abroad_content a join blogdump.study_abroad_contentImages b ON a.content_id= b.saContentId WHERE a.status in ('live','draft') AND b.status in ('live','draft') AND a.created > '$blogPaserDate'";
        $arrayFirst    = array('saContentimageId','imageURL');
        $arraySecond   = array('content_id','id','title','contentImageURL','images');  
        }elseif($type  == 'sasection'){
        $query         = "SELECT a.id,a.content_id,a.strip_title,a.contentImageURL,b.id,b.details FROM blogdump.study_abroad_content a join blogdump.study_abroad_content_sections b ON a.content_id= b.content_id WHERE b.status in ('live','draft') AND a.status in ('live','draft') AND a.created > '$blogPaserDate'";
        $arrayFirst    = array('id','details');
        $arraySecond   = array('content_id','id','contentImageURL','strip_title','images');  
        }
        
       return array('query'=>$query,'arrayFirst'=>$arrayFirst,'arraySecond'=>$arraySecond);
   }
   

         
   /**
    * batch update query for update the blogs
    * @param string $insertedString
    * @param Array $tableArray
    */
   function setBlogUpdateQuery($insertedString,$tableArray){
        $tableName   = $tableArray['tableName'];
        $fieldName   = $tableArray['fieldName'];
        $fieldName1  = $tableArray['fieldName1'];
        
        
        
        if(array_key_exists('fieldName2',$tableArray)){
            $fieldName2  = $tableArray['fieldName2'];
            $queryUpdate = "insert into $tableName ($fieldName,$fieldName1,$fieldName2) VALUES $insertedString ON DUPLICATE KEY UPDATE
            $fieldName1  = VALUES($fieldName1),$fieldName2 = VALUES($fieldName2)";  
        }else{
            $queryUpdate = "insert into $tableName ($fieldName,$fieldName1) VALUES $insertedString ON DUPLICATE KEY UPDATE  $fieldName1 = VALUES($fieldName1)";
        }
      
        mysql_query($queryUpdate);
        return true;
   }




