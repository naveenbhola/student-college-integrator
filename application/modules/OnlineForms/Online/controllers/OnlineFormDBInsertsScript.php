<?php 

class OnlineFormDBInsertsScript extends MX_Controller
{
	public function queryGenerator()
    {
        ini_set('memory_limit','1024M');
    	
        // =====================get pagename=================
        $pageName = "ADYPUBTECH2019";
		$courseId = 280191;

		$file = fopen("/tmp/".$pageName.".txt","w");

		fwrite($file,"please remove return or die statement from function then try again\n");
        echo "<br>please remove return or die statement from function then try again\n";
        return;

        $validationMap = array("text"=>array("validateEmail","validateStr","validateInteger","validateMobileInteger","validateAlphabetic","validateFloat"),"select"=>array("validateSelect"),"textarea"=>array("validateEmail","validateStr","validateInteger","validateMobileInteger","validateAlphabetic","validateFloat"),"checkbox"=>array("validateCheckedGroup"),"selectMultiple"=>array("multipleSelect"));

		$validationTypeArray = array('validateEmail','validateStr','validateInteger','validateMobileInteger','validateAlphabetic','validateFloat','validateSelect','validateCheckedGroup','multipleSelect');

		$typeArray = array('text','radio','date','select','file','textarea','checkbox','selectMultiple');


    	$inputFileName 	= '/var/www/html/shiksha/application/modules/OnlineForms/Online/onlineFormsExcel/FIIBMBA2019.xlsx';
    	$this->load->library('common/PHPExcel');
		$objPHPExcel 	= new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(true);
		$objPHPExcel  = $objReader->load($inputFileName);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        $data = array();
        $errorFlag = 0;
        for($row = 2; $row <= $highestRow && $row<=400; $row++) 
        {

            $name = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
            $type = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
            $label = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
            $defaultValue = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
            $required = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
            $prefilledValues = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
            $validationType = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
            $minlength = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
            $maxlength = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
            $caption = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
            $tooltip = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();

            if(!empty($name))
            {
                
                $name = str_replace(' ', '', $name);
                $name .= $pageName;

                if(preg_match('/[\'^£$%&.\/*()}{@#~?><>,|=+-]/',$name))
                {
                    fwrite($file,"please enter correct name at line $row \n");
                    echo "<br>please enter correct name at line $row \n";
                    $errorFlag = 1;
                }

                if(empty($label) || empty($required) || empty($type))
                {
                    fwrite($file,"label, required, type can not be empty at line $row \n");
                    echo "<br>label, required, type can not be empty at line $row \n";
                    $errorFlag = 1;
                }
                $required = strtolower($required);

                if($required=='yes' || $required=='true')
                {
                    $required = "true";
                }
                else if($required=='no' || $required=='false')
                {
                    $required = 'false';
                }
                else
                {
                    fwrite($file,"required field has wrong value at line $row \n");
                    echo "<br>required field has wrong value at line $row \n";
                    $errorFlag = 1;
                }

                if(!empty($validationType) && $validationType!="")
                {
                    if(in_array($validationType, $validationTypeArray))
                    {
                        if(empty($minlength) || empty($maxlength) || empty($caption))
                        {
                            fwrite($file,"minlength, maxlength, caption can not be empty at line $row \n");
                            echo "<br>minlength, maxlength, caption can not be empty at line $row \n";
                            $errorFlag = 1;
                        }
                    }
                    else
                    {
                        fwrite($file,"validationType is wrong at line $row \n");
                        echo "<br>validationType is wrong at line $row \n";
                        $errorFlag = 1;
                    }
                }

                if(!in_array($type,$typeArray))
                {
                    fwrite($file,"type value is wrong at line $row \n");
                    echo "<br>type value is wrong at line $row \n";
                    $errorFlag = 1;
                }
                
		if(!empty($validationMap[$type]))
                {
                    if(!empty($validationType) && !in_array($validationType,$validationMap[$type]))
                    {
                        fwrite($file,"validationType and type values are wrong at line $row \n");
                        echo "<br> validationType and type values are wrong at line $row \n";
                        $errorFlag = 1;
                    }
                }
                else
                {
                    if(!empty($validationType))
                    {
                        fwrite($file,"validationType should be empty at line $row \n");
                        echo "<br> validationType should be empty wrong at line $row \n";
                        $errorFlag = 1;
                    }
                }
                $data[$name] = array('name' => $name, 'type' => $type, 'label' => $label, 'defaultValue' => $defaultValue, 'required' => $required, 'prefilledValues' => $prefilledValues, 'validationType' => $validationType, 'minlength' => $minlength, 'maxlength' => $maxlength, 'caption' => $caption, 'tooltip' => $tooltip);
            }
        }

        $data['Terms'.$pageName] = array('name' => 'Terms'.$pageName, 'type' => 'checkbox', 'label' => "I agree to all terms and conditions", 'defaultValue' => "", 'required' => "true", 'prefilledValues' => "", 'validationType' => "", 'minlength' => "", 'maxlength' => "", 'caption' => "terms and conditions", 'tooltip' => "");

        if($errorFlag==1)
        {
            fwrite($file,"error in excel file\n");
            echo "<br>error in excel file\n";
            return;
        }
        


        $onlineFormDBInsertsModel = $this->load->model('OnlineFormDBInsertsModel');


        // insert query for OF_PageList

        fwrite($file,"inserting in OF_PageList\n");
        echo "<br>inserting in OF_PageList\n";

        $pageList = array("pageName" => $pageName, "pageType" => "custom", "status" => "live", "templatePath" => "Online/Templates/".$pageName);

        $pageId = $onlineFormDBInsertsModel->pageListInsert($pageList);

        if(empty($pageId))
        {
        	fwrite($file,"pageId returned is empty \n");
        	echo "<br>pageId returned is empty \n";
        	return;
        }

        // insert for OF_FieldsList

        fwrite($file,"inserting in OF_FieldsList\n");
        echo "<br>inserting in OF_FieldsList\n";

        $fieldsList = array();
        foreach ($data as $row)
        {
        	$fieldsList[] = array("type"=>$row['type'],"name"=>$row['name'],"label"=>$row['label'],"toopTip"=>$row['tooltip'],"defaultValue"=>$row['defaultValue'],"required"=>$row['required']);
        }

        $onlineFormDBInsertsModel->fieldsListInsert($fieldsList);


        $fieldNames = array();
        foreach ($data as $key => $value) {
        	$fieldNames[] = $key;
        }

        $fieldIdsMapping = $onlineFormDBInsertsModel->getFieldIdandNameMapping($fieldNames);

        $fieldIds = array();
        foreach ($fieldIdsMapping as $mapping) 
        {
        	$fieldIds[$mapping['name']] = $mapping['fieldId'];
        }


        // insert for OF_PageEntityMapping

        fwrite($file,"inserting in OF_PageEntityMapping\n");
        echo "<br>inserting in OF_PageEntityMapping\n";

        $pageEntityMapping = array();
        $entityOrder = 1;
        foreach ($data as $row) 
        {
        	if(empty($fieldIds[$row['name']]))
        	{
        		fwrite($file,"fieldid and name mapping not found \n");
        		echo "<br>fieldid and name mapping not found \n";
        		return;
        	}

        	$pageEntityMapping[] = array("pageId"=>$pageId,"entitySetId"=>$fieldIds[$row['name']],"entitySetType"=>"field","entityOrder"=>$entityOrder,"status"=>"live");
        	$entityOrder = $entityOrder + 1;
        }

        $onlineFormDBInsertsModel->pageEntityMappingInsert($pageEntityMapping);


        // insert for OF_FieldPrefilledValues

        fwrite($file,"inserting in OF_FieldPrefilledValues\n");
        echo "<br>inserting in OF_FieldPrefilledValues\n";

        $fieldPrefilledValues = array();
        foreach ($data as $row) 
        {
        	if(empty($fieldIds[$row['name']]))
        	{
        		fwrite($file,"fieldid and name mapping not found \n");
        		echo "<br>fieldid and name mapping not found \n";
        		return;
        	}
        	if(!empty($row['prefilledValues']) && $row['prefilledValues']!="")
        	{
        		$fieldPrefilledValues[] = array("fieldId"=>$fieldIds[$row['name']],"values"=>$row['prefilledValues']);
        	}
        }

        $onlineFormDBInsertsModel->fieldPrefilledValuesInsert($fieldPrefilledValues);

        // insert for OF_FieldValidations

        fwrite($file,"inserting in OF_FieldValidations\n");
        echo "<br>inserting in OF_FieldValidations\n";

        $fieldValidations = array();
        foreach ($data as $row) 
        {
        	if(!empty($row['validationType']) && $row['validationType']!="")
        	{
        		if(empty($row['minlength']) || empty($row['maxlength']) || empty($row['caption']))
        		{
        			fwrite($file,"minlength, maxlength, caption can not be empty \n");
        			echo "<br>minlength, maxlength, caption can not be empty \n";
        			return;
        		}
        		$fieldValidations[] = array("fieldId"=>$fieldIds[$row['name']],"validationType"=>$row['validationType'],"minCharactersAllowed"=>$row['minlength'],"maxCharactersAllowed"=>$row['maxlength'],"caption"=>$row['caption']);
        	}
        }

        $onlineFormDBInsertsModel->fieldValidationsInsert($fieldValidations);

        // insert query for OF_ListForms

        fwrite($file,"inserting in OF_ListForms\n");
        echo "<br>inserting in OF_ListForms\n";

        $listForms = array("formName" => $pageName, "courseId" => $courseId, "status" => "live");

        $formId = $onlineFormDBInsertsModel->listFormsInsert($listForms);

        if(empty($formId))
        {
        	fwrite($file,"formId returned is empty \n");
        	echo "<br>formId returned is empty \n";
        	return;
        }

        // insert for OF_PageMappingInForm

        fwrite($file,"inserting in OF_PageMappingInForm\n");
        echo "<br>inserting in OF_PageMappingInForm\n";

        $pageMappingInForm = array();
        for ($pageNumber=1;$pageNumber<5;$pageNumber++) 
        {
        	// get form id
        	if($pageNumber<4)
        	{
        		$pageMappingInForm[] = array("formId"=>$formId,"pageId"=>$pageNumber,"pageOrder"=>$pageNumber,"status"=>"live");
        	}
        	else
        	{
        		$pageMappingInForm[] = array("formId"=>$formId,"pageId"=>$pageId,"pageOrder"=>4,"status"=>"live");
        	}
        }

        $onlineFormDBInsertsModel->pageMappingInFormInsert($pageMappingInForm);

        fwrite($file,"inserting of data completed for form ".$pageName."\n\n\n\n");
        echo "<br>inserting of data completed for form ".$pageName."\n\n\n\n";

        fclose($file);

    }
}

?>
