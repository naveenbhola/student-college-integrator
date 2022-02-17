<?php

class DocumentModel extends OnlineParentModel {

	function deletePreviousAttachments($userId,$onlineFormId)
	{
		$this->initiateModel('write');
		$data = array('status' => 'history');
		$this->dbHandle->where('userId', $userId);
		$this->dbHandle->where('onlineFormId', $onlineFormId);
		$this->dbHandle->where('status', 'live');
		return $this->dbHandle->update('OF_user_documents_mapping', $data); 
	}
	
	function attachDocuments($userId,$onlineFormId,$documentIds)
	{
		$this->initiateModel('write');
		
		if($documentIds){
			$documentIds = explode(',',$documentIds);
			
			
			foreach($documentIds as $documentId){
				$data[] = array(
					'userId' => $userId,
					'onlineFormId' => $onlineFormId,
					'status' => 'live',
					'documentId' => $documentId
				);
			}

			$this->dbHandle->insert_batch('OF_user_documents_mapping', $data);
		}
	}
	
	function getAttachedDocuments($userId,$onlineFormId)
	{
		$this->initiateModel();
		
		$queryCmd = "SELECT udm.*,udt.document_title,udt.document_saved_path,udt.doc_type ".
					"FROM OF_user_documents_mapping udm ".
					"LEFT JOIN OF_user_documents_table udt ON (udt.id = udm.documentID AND udt.status = 'live')".
					"WHERE udm.userId = ? ".
					"AND udm.onlineFormId = ? ".
					"AND udm.status = 'live'";
		
		$query = $this->dbHandle->query($queryCmd,array($userId,$onlineFormId));
		
		$results = $query->result_array();
		$details_array = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$details_array[] = $row;
			}
		}
		return $details_array;
	}
	
	function getDocumentDetails($documentId,$sharingDetails)
	{
		$this->initiateModel();
		
		$queryCmd = "SELECT * FROM OF_user_documents_table WHERE id = ?";
		
		$query = $this->dbHandle->query($queryCmd,array($documentId));
		$documentDetails = $query->row_array();
		
		if($sharingDetails && is_array($documentDetails) && $documentDetails['id']) {
			
			$queryCmd = "SELECT DISTINCT lm.username ".
						"FROM OF_user_documents_mapping udm ".
						"LEFT JOIN OF_UserForms uf ON (uf.onlineFormId = udm.onlineFormId AND uf.userId = udm.userId) ".
						"LEFT JOIN OF_InstituteDetails idt ON idt.courseId = uf.courseId ".
						"LEFT JOIN listings_main lm ON (lm.listing_type_id = idt.instituteId AND lm.listing_type = 'institute' AND lm.status = 'live') ".
						"WHERE udm.documentId = ? ".
						"AND udm.status = 'live'";
			
			$query = $this->dbHandle->query($queryCmd,array($documentId));
			$sharedWith = $query->result_array();
			
			if(is_array($sharedWith)) {
				$sharedWith = array_map('current',$sharedWith);
				$documentDetails['sharedWith'] = $sharedWith;
			}
		}
		return $documentDetails;
	}

	function getAttachedDocumentsInForm($userId,$onlineFormId,$document_title="")
	{
		if(empty($userId) || empty($onlineFormId))
		{
			return;
		}
		$this->initiateModel();

		if($document_title=="")
		{
			$queryCmd = "SELECT fud.userId,fl.name as document_title,fud.onlineFormId,fud.value as document_saved_path FROM OF_FormUserData AS fud INNER JOIN OF_FieldsList AS fl ON (fud.fieldId = fl.fieldId) WHERE fud.userId = ? and fud.onlineFormId = ? and fl.type='file'";

			$query = $this->dbHandle->query($queryCmd,array($userId,$onlineFormId));
		}
		else
		{
			$queryCmd = "SELECT fud.userId,fl.name as document_title,fud.onlineFormId,fud.value as document_saved_path FROM OF_FormUserData AS fud INNER JOIN OF_FieldsList AS fl ON (fud.fieldId = fl.fieldId) WHERE fud.userId = ? and fud.onlineFormId = ? and fl.type='file' and fud.fieldName = ?";
			
			$query = $this->dbHandle->query($queryCmd,array($userId,$onlineFormId,$document_title));
		}
		
		
		$results = $query->result_array();
		return $results;
	}
}
