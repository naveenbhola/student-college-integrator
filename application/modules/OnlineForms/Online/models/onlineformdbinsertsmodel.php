<?php

class OnlineFormDBInsertsModel extends MY_Model
{
	
	private $dbHandle;

	private function initiateModel($operation = 'read')
    {
		if($operation=='read')
		{
			$this->dbHandle = $this->getReadHandle();
		}
		else
		{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}

	public function pageListInsert($pageList)
	{
		if(empty($pageList))
		{
			return;
		}

		$this->initiateModel("write");
		$result = $this->dbHandle->insert('OF_PageList',$pageList);
		$insert_id = $this->dbHandle->insert_id();
		return $insert_id;
	}

	public function fieldsListInsert($fieldsList)
	{
		if(empty($fieldsList))
		{
			return;
		}

		$this->initiateModel("write");
		$this->dbHandle->insert_batch('OF_FieldsList',$fieldsList);
	}

	public function getFieldIdandNameMapping($fieldNames)
	{
		if(empty($fieldNames))
		{
			return;
		}
		$this->initiateModel("read");

		$sql = "SELECT fieldId,name from OF_FieldsList where name in (?)";

		$result = $this->dbHandle->query($sql,array($fieldNames))->result_array();
		return $result;
	}

	public function pageEntityMappingInsert($pageEntityMapping)
	{
		if(empty($pageEntityMapping))
		{
			return;
		}

		$this->initiateModel("write");
		$this->dbHandle->insert_batch('OF_PageEntityMapping',$pageEntityMapping);
	}

	public function fieldPrefilledValuesInsert($fieldPrefilledValues)
	{
		if(empty($fieldPrefilledValues))
		{
			return;
		}

		$this->initiateModel("write");
		$this->dbHandle->insert_batch('OF_FieldPrefilledValues',$fieldPrefilledValues);
	}

	public function fieldValidationsInsert($fieldValidations)
	{
		if(empty($fieldValidations))
		{
			return;
		}

		$this->initiateModel("write");
		$this->dbHandle->insert_batch('OF_FieldValidations',$fieldValidations);
	}

	public function listFormsInsert($listForms)
	{
		if(empty($listForms))
		{
			return;
		}

		$this->initiateModel("write");
		$this->dbHandle->insert('OF_ListForms',$listForms);
		$insert_id = $this->dbHandle->insert_id();
		return $insert_id;
	}

	public function pageMappingInFormInsert($pageMappingInForm)
	{
		if(empty($pageMappingInForm))
		{
			return;
		}

		$this->initiateModel("write");
		$this->dbHandle->insert_batch('OF_PageMappingInForm',$pageMappingInForm);
	}
}

?>