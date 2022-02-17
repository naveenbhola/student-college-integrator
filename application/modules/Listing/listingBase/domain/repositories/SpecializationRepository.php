<?php

/**
 * Responsible for handling the repository functionality for the Specialization functionality in Shiksha Listings
 *
 * @version ShikshaRecatV1.0
 * @author Shiksha Listings Team
 *
 */
class SpecializationRepository extends ListingBaseRepository{
	function __construct($cache,$model)
    {        
        parent::__construct($cache, $model);

        // $this->model = $model;
        // $this->cache = $cache;
        $this->CI->load->entity('Specializations','listingBase');
        $this->setEntity(new Specializations());
        $this->cache->setEntity('Specializations');
    }

    function find($id) {
        $data=  $this->findMultiple(array($id));
        return $data[$id];
    }

    function findMultiple($ids) {       
        if(is_array($ids)){
            $data =  parent::findMultiple($ids); 
        }
        return $data;
    }

    /**
     * CMS function to save data into specializations table.Use with caution.
     * @param  array $data array from the cms received
     * @param  mode $mode edit or add
     * @return int       returns the specialization if already present or inserts and returns the new one.
     */
    function save($data,$mode){

        $specializationData['table']['name']    = $data['specializationName'];
        $specializationData['table']['alias']   = $data['specializationAlias'];
        $specializationData['table']['synonym'] = $data['specializationSynonym'];
        $specializationData['table']['type']    = $data['type'];
        $specializationData['table']['status']  = 'live';
        $specializationData['userId']  = $data['userId'];

        if($mode == 'add'){
            $specializationData['table']['primary_stream_id'] = $data['specializationPrimaryStream'];
            if(!empty($data['specializationPrimarySubstream'])){
                $specializationData['table']['primary_substream_id'] = $data['specializationPrimarySubstream'];
            }
        }
        else if($mode == 'edit'){
            $substream = $this->find($data['specializationId']);
            $specializationData['table']['primary_stream_id'] = $substream->getPrimaryStreamId();
            $substream = $substream->getPrimarySubStreamId();
            if(!empty($substream)){
                $specializationData['table']['primary_substream_id'] = $substream;
            }
            $specializationData['table']['specialization_id'] = $data['specializationId'];
        }

        $data = $this->model->save($specializationData,$mode);
        return $data;
    }
}