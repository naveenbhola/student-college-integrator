<?php

class shikshaTrackingModel extends MY_Model{
	private $collection;

	function _construct(){

	}


/*	$m = new MongoClient();

	$c1 = $m->selectCollection("foo", "bar.baz");
	// which is equivalent to
	$c2 = $m->selectDB("foo")->selectCollection("bar.baz");
*/

	/* 
    * Make connection to shikshaTracking collection in MongoDb
    * 
    */
    function makeConnection($dbURL,$db="default",$collection="shikshaTracking"){

        if($dbURL =""){
        	$conn = new MongoClient();	
        } else{
        	$conn = new MongoClient($dbURL);	
        }
		
		if($collection ==""){ //optional , depends on default
			return false;
		}

        //MongoConnection->dbName->CollectionName
        // public MongoCollection selectCollection ( string $db , string $collection )
		//public MongoDB selectDB ( string $name )

        $this->collection = $conn->selectCollection("shikshaTracking");
    }

    function insertDocument($data){		//data coming in desired format, json
    	$this->makeConnection($writeURL,$db,"shikshaTracking"); 	//db optional

    	$this->collection->insert($data);

    }

    function selectDocument($data){
    	$this->makeConnection($readURL,$db,"shikshaTracking");

    	
    }


}
