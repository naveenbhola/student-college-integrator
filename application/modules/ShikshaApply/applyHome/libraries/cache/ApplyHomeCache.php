<?php

class ApplyHomeCache extends Cache{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * @return this function returns Study abroad counselling service rating data
     */
    public function getSACounsellingRatingData(){
        $result = json_decode($this->get('V1SACounsellingRating'),true);
        return $result;
    }
}

?>