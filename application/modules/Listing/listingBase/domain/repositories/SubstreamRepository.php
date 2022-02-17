<?php

/**
 * Class SubstreamRepository Responsible for handling the repository functionality for the core tables belonging to the Shiksha Listings
 *
 * @version ShikshaRecatV1.0
 * @author Shiksha Listings Team
 *
 */
class SubstreamRepository extends ListingBaseRepository{
	function __construct($cache,$model)
    {        
        parent::__construct($cache,$model);

        $this->model = $model;
        $this->cache = $cache;
        $this->CI->load->entity('Substreams','listingBase');
        $this->setEntity(new Substreams());
        $this->cache->setEntity('Substreams');
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
     * Function to save data into substreams table.Use with caution.
     * @param  array $data array of values from cms to insert
     * @param  mode $mode edit or add
     * @return int       returns the substreamid if already present or returns one after inserting
     */
    function save($data,$mode){
        
        $substreamData['table']['name']    = $data['substreamName'];
        $substreamData['table']['alias']   = $data['substreamAlias'];
        $substreamData['table']['synonym'] = $data['substreamSynonym'];
        $substreamData['table']['display_order'] = $data['substreamDisplayOrder'];
        $substreamData['table']['status']  = 'live';
        $substreamData['userId']  = $data['userId'];
        
        if($mode == 'add'){
            $substreamData['table']['primary_stream_id'] = $data['substreamPrimaryStream'];
        }
        else if($mode == 'edit'){
            $substream = $this->find($data['substreamId']);
            $substreamData['table']['primary_stream_id'] = $substream->getPrimaryStreamId();
            $substreamData['table']['substream_id'] = $data['substreamId'];
        }

        $data = $this->model->save($substreamData,$mode);
        return $data;
    }
}
