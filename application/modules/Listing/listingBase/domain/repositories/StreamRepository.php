<?php

/**
 * Class StreamRepository
 *
 * This class is responsible for handling the repository functionality for the streams available in the system
 *
 * @version ShikshaRecatV1.0
 * @author Shiksha Listings Team
 *
 */
class StreamRepository extends ListingBaseRepository{
	
	function __construct($cache,$model){
        parent::__construct($cache,$model);

        $this->model = $model;
        $this->cache = $cache;
        $this->CI->load->entity('Streams','listingBase');
        $this->setEntity(new Streams());
        $this->cache->setEntity('Streams');
    }

    function getCache(){
        return $this->cache;
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

    function getAllStreams($outputFormat = 'array') {
        $result = $this->model->getAllStreams();

        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        if($outputFormat == 'object') {
            foreach ($result as $key => $value) {
                $streamIds[] = $value['id'];
            }
            $result = $this->findMultiple($streamIds); //findMultiple will change the sorting. To be taken care of.
        }

        return $result;
    }
}
