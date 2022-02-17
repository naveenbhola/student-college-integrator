<?php
class Listing_media_server extends MX_Controller {
	function index(){
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('listingconfig');
		$this->load->helper('date');
		$this->load->helper('url');
		$this->load->helper('shikshaUtility');
		$this->dbLibObj = DbLibCommon::getInstance('Listing');
		$config['functions']['sMapMediaContentWithListing'] = array('function' => 'Listing_media_server.sMapMediaContentWithListing');
		$config['functions']['sUpdateListingMediaAttributes'] = array('function' => 'Listing_media_server.sUpdateListingMediaAttributes');
		$config['functions']['sAssociateMedia'] = array('function' => 'Listing_media_server.sAssociateMedia');
		$config['functions']['sRemoveMediaContent'] = array('function' => 'Listing_media_server.sRemoveMediaContent');
		$config['functions']['sGetMediaDetailsForListing'] = array('function' => 'Listing_media_server.sGetMediaDetailsForListing');

		
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}

	function sUpdateListingMediaAttributes($request) {

		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$listingType = $parameters['1'];
		$listingId = $parameters['2'];
		$mediaType = $parameters['3'];
		$mediaId = $parameters['4'];
		$mediaAttributeName = $parameters['5'];
		$mediaAttributeValue= $parameters['6'];
		$mediaType = $this->getMediaType($mediaType);
		$this->db = $this->dbLibObj->getWriteHandle();
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		$this->db->where("media_id", $mediaId);
		$this->db->where("media_type", $mediaType);
		$this->db->where("listing_type", $listingType);
		$this->db->where("listing_type_id", $listingId);
		$this->db->update("institute_uploaded_media", array($mediaAttributeName => $mediaAttributeValue));

		$response = array(array('status'=>'success'),'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sMapMediaContentWithListing($request) {

		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$listingId = $parameters['1'];
		$listingType = $parameters['2'];
		$mediaType = $parameters['3'];
		$mediaType = $this->getMediaType($mediaType);
		$mediaDetails = json_decode(base64_decode($parameters['4']), true);

		$this->db = $this->dbLibObj->getWriteHandle();
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		$mediaId = $mediaDetails['mediaId'];
		$mediaName = $mediaDetails['mediaName'];
		$mediaUrl = $mediaDetails['mediaUrl'];
		$mediaThumbUrl = $mediaDetails['mediaThumbUrl'];
		$insertMediaMappingQuery = 'INSERT INTO institute_uploaded_media SET listing_type = ?, listing_type_id = ?, media_type = ?, media_id = ?, name = ?, url= ?, thumburl = ?, uploadeddate = NOW()';
		
		$queryResult = $this->db->query($insertMediaMappingQuery, array($listingType, $listingId, $mediaType, $mediaId, $mediaName, $mediaUrl, $mediaThumbUrl));
		$response = array(array('status'=>'success'),'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sAssociateMedia($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$parentListingType = $parameters['1'];
		$parentListingId = $parameters['2'];
		$mediaAssociation = json_decode(base64_decode($parameters['3']), true);

		$this->db = $this->dbLibObj->getWriteHandle();
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		foreach($mediaAssociation as $mediaType => $mediaTypeAssociations) {
			$mediaType = $this->getMediaType($mediaType); 
			foreach($mediaTypeAssociations as $mediaId => $mediaIdAssociations) {
				foreach($mediaIdAssociations as $mediaIdAssociation) {
					$mediaName = $mediaIdAssociation['mediaName'];
					$mediaUrl = $mediaIdAssociation['mediaUrl'];
					$mediaThumbUrl = $mediaIdAssociation['mediaThumbUrl'];
					$listingType = $mediaIdAssociation['entityName'];
					$listingId = $mediaIdAssociation['entityValue'];
					$insertMediaAsociationQuery = 'INSERT INTO listing_media_table SET type=?, type_id=?, media_type = ?, media_id = ?, associationdate = NOW()';
					error_log("ASHISH::". $insertMediaAsociationQuery);
					try{
					$queryResult = $this->db->query($insertMediaAsociationQuery, array($listingType, $listingId, $mediaType, $mediaId));
					} catch( Exception $e) { error_log("ASHISH::ERROR". $e ); }
				}
				$updateMediaListingMappingQuery = 'UPDATE institute_uploaded_media SET status = "live" WHERE media_id = ? AND media_type = ? AND listing_type_id = ? AND listing_type = ?';
				error_log("ASHISH::". $updateMediaListingMappingQuery);
				$queryResult = $this->db->query($updateMediaListingMappingQuery, array($mediaId, $mediaType, $parentListingId, $parentListingType));
			}
		}
		$response = array(array('status'=>'success'),'struct');
		return $this->xmlrpc->send_response($response);
	}

	function getMediaType($mediaType) {
		switch($mediaType) {
			case 'documents' : $mediaType = 'doc'; break;
			case 'photos' : $mediaType = 'photo'; break;
			case 'videos' : $mediaType = 'video'; break;
		}
		return $mediaType;
	}

	function sRemoveMediaContent($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$listingType = $parameters['1'];
		$listingId = $parameters['2'];
		$mediaType = $parameters['3'];
		$mediaId = $parameters['4'];
		$mediaType = $this->getMediaType($mediaType); 

		$this->db = $this->dbLibObj->getWriteHandle();
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$updateMediaListingMappingQuery = 'UPDATE institute_uploaded_media SET status = "deleted" WHERE media_id = ? AND media_type= ? AND listing_type = ? AND listing_type_id = ?';
		error_log("ASHISH::". $updateMediaListingMappingQuery);
		$queryResult = $this->db->query($updateMediaListingMappingQuery, array($mediaId, $mediaType, $listingType, $listingId));
		$response = array(array('status'=>'success'),'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sGetMediaDetailsForListing($request) {
		$parameters  = $request->output_parameters();
		$appId       = $parameters['0'];
		$listingType = $parameters['1'];
		$listingId   = $parameters['2'];

		$this->db = $this->dbLibObj->getReadHandle();
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		$getMediaDetailsQuery = 'SELECT * FROM institute_uploaded_media a, listing_media_table b WHERE a.media_id = b.media_id AND a.media_type=b.media_type AND a.status!="deleted" AND a.listing_type = ? AND a.listing_type_id = ? ORDER BY a.media_type, a.media_id';
		error_log("ASHISH::". $getMediaDetailsQuery);
		$queryResult = $this->db->query($getMediaDetailsQuery, array($listingType, $listingId));

		$msgArray = array();
		foreach ($queryResult->result() as $row){
			$mediaType = $row->media_type;
			$mediaId = $row->media_id;
			$mediaName = $row->name;
			$mediaUrl = $row->url;
			$mediaThumbUrl = $row->thumburl;
			$mediaUploadDate = $row->uploaddate;
			$mediaAssociationDate = $row->associationdate;
			$type = $row->type;
			$typeId = $row->type_id;
			switch($mediaType) {
				case 'doc' : $mediaType = 'documents'; break;
				case 'photo' : $mediaType = 'photos'; break;
				case 'video' : $mediaType = 'videos'; break;
			}

			$msgArray[$mediaType][$mediaId]['mediaCaption'] = $mediaName;
			$msgArray[$mediaType][$mediaId]['mediaUrl'] = $mediaUrl;
			$msgArray[$mediaType][$mediaId]['mediaThumbUrl'] = $mediaThumbUrl;
			$msgArray[$mediaType][$mediaId]['mediaUploadDate'] = $mediaUploadDate;
			$msgArray[$mediaType][$mediaId]['mediaAssociationDate'] = $mediaAssociationDate;
			$msgArray[$mediaType][$mediaId]['mediaAssociation'][] = array($type => $typeId);
		}
		error_log("ASHISH :: ". print_r($msgArray, true));
		$response = array(base64_encode(json_encode($msgArray)));
		return $this->xmlrpc->send_response($response);
	}
}
