<?php
class CleanUpEntityCache extends Cache {
    private $standardCacheTime = 604800; //1 Week
    
    function __construct() {
        parent::__construct();
    }
    
    public function storeEntity($entityName, $entityData)
    {   
        if(!empty($entityData)) {
            $data = serialize($entityData);
            $this->store('cleanedupentity',$entityName, $data, $this->standardCacheTime, NULL, 1);
        }
    }
    
    public function getEntity($entityName) //on whichever is find
    {
        if(!empty($entityName)) {
            $data = unserialize($this->get('cleanedupentity', $entityName));
        }

        return $data;
    }

    public function deleteCache($id, $key){
        if(empty($id)){
            return;
        }
        try {
            $this->delete($key,$id);
        }
            catch (Exception $e){
        }
    }
}
?>
