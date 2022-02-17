<?php

/**
 * Class CertificateProviderRepository Responsible for handling the repository functionality for the certificate_providers tables belonging to the Shiksha Listings
 *
 * @version ShikshaRecatV1.0
 * @author Shiksha Listings Team
 *
 */
class CertificateProviderRepository extends ListingBaseRepository{
	private $fieldNameToEntityFunctionMapping = array('name' => 'getName', 
													  'synonym' => 'getSynonym', 
													  'alias' => 'getAlias');
	function __construct($cache,$certificatemodel)
    {
        parent::__construct($cache,$certificatemodel);

        $this->certificatemodel = $certificatemodel;
        $this->cache = $cache;

        $this->CI->load->entity('CertificateProviders','listingBase');
        $this->setEntity(new CertificateProviders());
    }

    /**
     * To use this repository, first set hierarchy repo and course repo as member variables for dependencies.
     * @param object $hierarchyRepo object of hierarchy repo
     * @param object $courseRepo    object of popular course repo
     */
    public function _setDependencies($hierarchyRepo,$courseRepo){
    	$this->hierarchyRepo 	= $hierarchyRepo;
    	$this->courseRepo 		= $courseRepo;
    	return $this;
    }

    function find($id) {
        $data=  parent::find($id);                
        return $data;
    }

    function findMultiple($ids) {       
        if(is_array($ids)){
            $data =  parent::findMultiple($ids); 
        }
        return $data;
    }

    /**
     * To get certification provider by a particular streamId,substreamId,specializationId where streamId is compulsary.
     * By default only ids of certification providers are returned. If object is passed as return type, then objects are returned.
     * @param  int  $streamId         streamId
     * @param  int $substreamId      substreamId
     * @param  int $specializationId specializationId
     * @param  string  $returnType       objects or simple Ids to be returned
     * @return array[int]|array[objects]                    returns array of int or objects
     */
    public function getCertificateProvidersByBaseEntities($streamId, $substreamId = 0, $specializationId = 0, $returnType = 'array'){
        
    }

    /**
     * To get certification provider for multiple sets where each set containing keys - streamId, substreamId, specializationId.
     * @param  array $hierarchyArr Array of sets
     * @param  string $returnType   objects or simple Ids to be returned
     * @return array[int]|array[objects]                    returns array of int or objects
     */
    public function getCertificateProvidersByMultipleBaseEntities($hierarchyArr, $returnType = 'array'){
        
    }

    /**
     * Get cpIds mapped to course ids
     * @param  array $courseIds array of courseids
     * @return array            mappng between popularcourse id and array of cpids
     */
    public function getCertProvidersByBaseCourses($courseIds){
        $coursesData = $this->certificatemodel->getEntitiesMappedToCourses('certificate_provider',array(),$courseIds);
        
        foreach ($coursesData as $val) {
            $tempData['id'] = $val['entity_id'];
            $courseMappings[$val['base_course_id']][] = $tempData;
        }

        return $courseMappings;
    }

    /**
     * Get courses mapped to cpids
     * @param  array $cpIds array of cpids
     * @return array        mapping array between cpids and courseids
     */
    public function getBaseCourseIdsbyCertProviders($cpIds){
    	$coursesData = $this->certificatemodel->getEntitiesMappedToCourses('certificate_provider',$cpIds);

    	foreach ($coursesData as $val) {
            $tempData['id'] = $val['base_course_id'];
            $courseMappings[$val['entity_id']][] = $tempData;
    	}

    	return $courseMappings;
    }

    public function save($data,$mode){
        $certificationProviderData['table']['name']    = $data['name'];
        $certificationProviderData['table']['alias']   = $data['alias'];
        $certificationProviderData['table']['synonym'] = $data['synonym'];
        $certificationProviderData['table']['status']  = 'live';
        $certificationProviderData['userId']           = $data['userId'];

        if($mode == 'edit'){
            $certificationProviderData['table']['certificate_provider_id'] =     $data['certificationProviderId'];
        }

        $returnData = $this->model->save($certificationProviderData,$mode);

        if($returnData['data']['status'] == 'success'){
            $certificateProviderId = $returnData['data']['certificate_provider_id'];
            $courseMapping  = array_map("unserialize", array_unique(array_map("serialize", $data['courseMapping'])));
            $insertData = array();
            foreach ($courseMapping as $key => $value) {
                $temp['base_course_id'] = $value['courseId'];
                $temp['entity_id']      = $certificateProviderId;
                $temp['entity_type']    = 'certificate_provider';
                $temp['status']         = 'live';
                $temp['created_on']     = date("Y-m-d H:i:s");
                $temp['updated_by']     = $data['userId'];
                $temp['created_by']     = $data['userId'];
                $insertData[] = $temp;
            }
            $this->model->insertCourseMapping($certificateProviderId,$insertData);
        }
        return $returnData;
    }
}
