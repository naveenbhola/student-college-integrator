<?php

class HardBounceTrackingModel extends MY_Model
{
    /**
    * Function to get the all hardbounced email 
    */
    public function getHardBounceEmailForever()
    {
        $this->initiateModel('read','HardBounceTracking');

        $sql = "SELECT email_id,bounce_type,added_on FROM UserBounceData WHERE bounce_type='hardbounce' AND".
                    " application_name = 'Shiksha.com'";

        $query = $this->dbHandle->query($sql);
        $result = $query->result_array();
        return $result;
        
    }

    /**
    * Function to get the hardbounced email on daily basis
    */
    public function getHardBounceEmailDaily()
    {
        $this->initiateModel('read','HardBounceTracking');

       /* $sql = "SELECT email_id,bounce_type,added_on FROM UserBounceData WHERE bounce_type='hardbounce' AND".
                   " application_name = 'Shiksha.com' and added_on > DATE_SUB(NOW(), INTERVAL 24 HOUR)";
       */ 
        // Removed 24Hrs check from query and fetch with is_processed = 'N'
       $sql = "SELECT email_id,bounce_type,added_on FROM UserBounceData WHERE bounce_type='hardbounce' AND".
               " application_name = 'Shiksha.com' and is_processed = 'N'";
        $query = $this->dbHandle->query($sql);
        $result = $query->result_array();
        return $result;
        
    }


    /**
    * Function to Copy Data From mailbouncelog.UserBounceData to Shiksha/Mailer DB
    * @param array $data Data to be inserted
    * @param string $db Database name(Default Shiksha used)
    * @param string $mode Whether to run code One time script / Daily Cron
    */

    public function copyDataToDB($data,$db='shiksha',$mode='daily'){
        if(empty($data)){
            return;
        }   
        if($db == "mailer"){
            $this->initiateModel('write','Mailer');
        } else {
            $this->initiateModel('write');
        }
        
        $date = date('Y-m-d H:i:s');
        if($mode == 'daily'){
            $sql = "INSERT IGNORE INTO $db.UserBounceData(email_id,bounce_type,added_on,updated_on) VALUES";
            foreach ($data as $row) {
                $sql .= "('".$row['email_id']."','".$row['bounce_type']."','".$row['added_on']."','".$date."'),";
            }
            $sql = substr($sql, 0,-1);
            $this->dbHandle->query($sql);
        }
        else if($mode == 'forever'){
            $this->dbHandle->insert_batch("$db.UserBounceData",$data);
        }
        unset($data);
        
    }



    /**
     * Function to get the userids from tuser based on inClause
     * @param string $inClause
     */
    public function getUserIds($inClause) {

        $this->initiateModel('read');
        $sql = "SELECT userid FROM tuser WHERE email IN $inClause";
        $query = $this->dbHandle->query($sql);
        $result = $query->result_array();
        return $result;
    }

    /**
     * Function to update the hardbounce status in tuserflag and tuserdata table
     * @param string $inClause
     */
    public function updateUserFlaginDB($inClause){

        $this->initiateModel('write');

        error_log("************bounce***********updating table tuserflag");
        //update tuserflag
        $sql = "UPDATE tuserflag SET hardbounce = '1' where userid IN $inClause";
        $query = $this->dbHandle->query($sql);
        $afftectedRows = $this->dbHandle->affected_rows();
        echo "<br />";
        echo "Affected rows in tuserflag--".$afftectedRows;
        echo "<br />";
        error_log("Affected rows in tuserflag--".$afftectedRows);


        error_log("************bounce***********updating table tuserdata");
        //update tuserdata
        $sql = "UPDATE tuserdata SET hardbounce = '1' where userid IN $inClause";
        $query = $this->dbHandle->query($sql);
        $afftectedRows = $this->dbHandle->affected_rows();
        echo "<br />";
        echo "Affected rows in tuserdata--".$afftectedRows;
        echo "<br />";
        error_log("Affected rows in tuserdata--".$afftectedRows);

    }

    // Function to update the is_processed column in Shiksha DB, $inclause with email ids to updated
    public function updateProcessingStatusShiksha($inClause){       
        $this->initiateModel('write');
        $date = date('Y-m-d H:i:s');
        error_log("************bounce***********updating isprocessing for shiksha");
        //update shiksha db for processed
        $sql = "UPDATE shiksha.UserBounceData SET is_processed = 'Y',updated_on='$date' where email_id IN $inClause";
        $query = $this->dbHandle->query($sql);
    }

    // Function to update the is_processed column in mailer DB, $inclause with email ids to updated
      public function updateProcessingStatusMailer($inClause){

       
        $this->initiateModel('write','Mailer');
        $date = date('Y-m-d H:i:s');

        error_log("************bounce***********updating isprocessing for mailer");
        //update mailer db for processed
        $sql = "UPDATE mailer.UserBounceData SET is_processed = 'Y',updated_on='$date' where email_id IN $inClause";
        $query = $this->dbHandle->query($sql);        
    }

    // Function to update the is_processed column in mailBounceLog DB, $inclause with email ids to updated
     public function updateProcessingStatusBounceLog($inClause){

        $this->initiateModel('write','HardBounceTracking');
        error_log("************bounce***********updating isprocessing for mailbouncelog");
        //update mailer db for processed
        $sql = "UPDATE UserBounceData SET is_processed = 'Y' where application_name = 'Shiksha.com' and email_id IN $inClause";
        $query = $this->dbHandle->query($sql);        
    }
        



    /**
     * Function to initiate the Model with read/handle and For a module
     * @param string $mode (For read and write handler)
     * @param string $module( For selecting database)
     */
    private function initiateModel($mode = "write", $module = '')
    {
        if($mode == 'read') {
            $this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
        } else {
            $this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
        }
    }

    public function addUserToIndexingQueue($userIdArray)
    {
        $this->initiateModel('write');
        $res = $this->dbHandle->insert_batch('tuserIndexingQueue',$userIdArray);
        
    }
   
}