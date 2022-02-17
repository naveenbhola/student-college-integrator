<?php
class paymodel extends OnlineParentModel {

	function __construct(){
		parent::__construct();
		$this->initiateModel('write');
	}

    function escapeMyString($variable){
        if(mysql_real_escape_string($variable))
            return mysql_real_escape_string($variable);
        else
            return mysql_escape_string($variable);
    }

	function getPaymentInformation($orderId,$startDate,$endDate,$instituteName,$mode,$status,$candidateName,$orderby,$from,$to,$checkForDisable='true'){
		if(!empty($orderId)){
			$orderIdStr = $this->escapeMyString($orderId);
			$orderQuery = "p.orderId LIKE '%{$orderIdStr}%'";
		}else{
			$orderQuery = "1=1";
		}
		if(!empty($startDate) && ($startDate!='dd/mm/yyyy')){
			$startDate_array = explode("/", $startDate);
			$startDate = $startDate_array[2]."-".$startDate_array[1]."-".$startDate_array[0];
			//$startDateQuery = "p.date >= '{$startDate}'";
			$startDateQuery = "ff.receiptDate >= '{$startDate}'";
		}else{
			$startDateQuery = "1=1";
		}
		if(!empty($endDate) && ($endDate!='dd/mm/yyyy')){
			$endDate_array = explode("/", $endDate);
			$endDate = $endDate_array[2]."-".$endDate_array[1]."-".(intval($endDate_array[0]))." 23:59:59";
			//$endDateQuery = "p.date < '{$endDate}'";
			$endDateQuery = "ff.receiptDate <= '{$endDate}'";
		}else{
			$endDateQuery = "1=1";
		}
		if(!empty($instituteName)){
			$instituteNameQuery = " p.paymentId IN (select paymentId FROM OF_Payments WHERE instituteId IN (SELECT institute_id FROM institute WHERE institute_name like '%" . $this->escapeMyString($instituteName) . "%' and status = 'live'))";
		}else{
			$instituteNameQuery = " 1=1";
		}
			$modeQuery = "p.mode = 'Online'";
			
		if(!empty($status)){
			$statusQuery = "ff.status = '{$status}'";
		}else{
			$statusQuery = "1=1";
		}
		if(!empty($candidateName)){
			$candidateName= $this->escapeMyString($candidateName);
			$candidateNameQuery = " p.paymentId IN (SELECT paymentId FROM OF_Payments WHERE userId IN (SELECT userid FROM tuser WHERE email = '$candidateName' ))";
		}else{
			$candidateNameQuery = "1=1";
		}
		if(!empty($orderby)){
			$orderbyQuery = "ORDER BY {$orderby} DESC ";
		}else{
			$orderbyQuery = "ORDER BY p.paymentId DESC ";
		}

		$paymentDetails = "SELECT SQL_CALC_FOUND_ROWS p.paymentId,p.orderId ,p.date,ff.status,ff.receiptDate FROM `OF_PaymentLog` p , OF_PaymentFinanceFields ff, OF_Payments p2,OF_UserForms u where p2.userId=u.userId AND p2.onlineFormId = u.onlineFormId AND u.courseId!=0 AND u.type='course' and $orderQuery AND p.orderId = ff.orderId and p.date = ff.transactionDate and  ";
		
		$paymentDetails .= "$startDateQuery and $endDateQuery and $instituteNameQuery and $modeQuery and $statusQuery and ";
		$paymentDetails .= "$candidateNameQuery and p2.mode = 'Online' AND p2.paymentId = p.paymentId $orderbyQuery";
	        $paymentDetails .= "limit $from , $to";
		error_log('ppppp0'.$paymentDetails);

		$query = $this->dbHandle->query($paymentDetails);
		$results = $query->result_array();
		
		$queryCmdT = 'SELECT FOUND_ROWS() as totalRows';
		$queryT = $this->dbHandle->query($queryCmdT);
		$totalRows = 0;
		foreach ($queryT->result() as $row) {
			$totalRows = $row->totalRows;
		}
		// Collect all retrieved payment ids in an array

		$payment_ids_array = array();
		$index = 0;
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				if ($index && $payment_ids_array[$index-1] == $row['paymentId']) {
					continue;
				}
				$index++;
				$payment_ids_array[] = $row['paymentId'];
			}
		}
		$payment_ids = implode(", ", $payment_ids_array);

		$successful_payment_ids = array();
		// Retrieve if any of the payment ids retrieved has a successful payment
		if($payment_ids!=''){
			$paymentDetails2 = "SELECT p.paymentId, p.status FROM `OF_PaymentLog` p WHERE status=\"Success\" AND p.paymentId in (?)";
			$query = $this->dbHandle->query($paymentDetails2,array($payment_ids_array));
			$results2 = $query->result_array();
			if(!empty($results2) && is_array($results2)) {
				foreach ($results2 as $row) {
					// Make an associative array out of successful payment ids
					$successful_payment_ids[$row['paymentId']] = true;
				}
			}
		}

		// From the search results, make an associative array of paymentIds that are successful

		$retrieved_successful_payment_ids = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				if ($row['status'] == "Success") {
					$retrieved_successful_payment_ids[$row['paymentId']] = true;
				}
			}
		}

		$payment_details_array = array();
		if(!empty($results) && is_array($results) ) {
			foreach ($results as $row){
                if($checkForDisable=='true'){
				if (isset($successful_payment_ids[$row['paymentId']]) &&
						!isset($retrieved_successful_payment_ids[$row['paymentId']])) {
					$row['disabled'] = true;
				} else {
					$row['disabled'] = false;
				}
                }
				$payment_details_array[] = $row;
			}

		}
		$payment_details_array['totalResults'] = intval($totalRows);
		return $payment_details_array;
	}

	//this function returns all the statuses of a particular payment id
	function getPaymentLog($paymentId){
		$getLog="SELECT status from OF_PaymentLog WHERE paymentId=?";
		$query = $this->dbHandle->query($getLog, array($paymentId));
		$results = $query->result_array();
		return $results;
	}


	//this function updates the status of the user form

	function updateFormStatus($orderId,$paymentId,$log,$currentdate,$status,$statusOfUserForm,$date){
		$updateUserForms ="UPDATE OF_UserForms SET status=? where onlineFormId IN (SELECT onlineFormId FROM OF_Payments WHERE paymentId=?)";
		$query = $this->dbHandle->query($updateUserForms, array($statusOfUserForm,$paymentId));
	}

	
	function getOnlineFormDetails($paymentId){
		$getOnlineFormDetails="SELECT onlineFormId ,userId,instituteId FROM OF_Payments WHERE paymentid=?";
		$query = $this->dbHandle->query($getOnlineFormDetails, array($paymentId));
		$results = $query->result_array();
		return $results;
	}
	
	
	function getDownloadPaymentInformation($orderId,$startDate,$endDate,$instituteName,$mode,$status,$candidateName,$orderby,$from,$to,$checkForDisable='true'){
		if(!empty($orderId)){
			$orderIdStr = $this->escapeMyString($orderId);
			$orderQuery = "paymentLog.orderId LIKE '%{$orderIdStr}%'";
		}else{
			$orderQuery = "1=1";
		}
		if(!empty($startDate) && ($startDate!='dd/mm/yyyy')){
			$startDate_array = explode("/", $startDate);
			$startDate = $startDate_array[2]."-".$startDate_array[1]."-".$startDate_array[0];
			$startDateQuery = "ff.receiptDate >= '{$startDate}'";
		}else{
			$startDateQuery = "1=1";
		}
		if(!empty($endDate) && ($endDate!='dd/mm/yyyy')){
			$endDate_array = explode("/", $endDate);
			$endDate = $endDate_array[2]."-".$endDate_array[1]."-".(intval($endDate_array[0]))." 23:59:59";
			$endDateQuery = "ff.receiptDate <= '{$endDate}'";
		}else{
			$endDateQuery = "1=1";
		}
		if(!empty($instituteName)){
			$instituteNameQuery = " paymentLog.paymentId IN (select paymentId FROM OF_Payments WHERE instituteId IN (SELECT institute_id FROM institute WHERE institute_name like '%" . $this->escapeMyString($instituteName) . "%' and status = 'live'))";
		}else{
			$instituteNameQuery = " 1=1";
		}
			$modeQuery = "paymentLog.mode = 'Online'";
			
		if(!empty($status)){
			$statusQuery = "ff.status = '{$status}'";
		}else{
			$statusQuery = "1=1";
		}
		if(!empty($candidateName)){
			$candidateName= $this->escapeMyString($candidateName);
			$candidateNameQuery = " paymentLog.paymentId IN (SELECT paymentId FROM OF_Payments WHERE userId IN (SELECT userid FROM tuser WHERE email = '$candidateName' ))";
		}else{
			$candidateNameQuery = "1=1";
		}
		if(!empty($orderby)){
			$orderbyQuery = "ORDER BY paymentLog.$orderby DESC ";
		}else{
			$orderbyQuery = "ORDER BY paymentLog.paymentId DESC ";
		}

        $paymentDetails = "SELECT distinct (select value from OF_FormUserData where OF_FormUserData.fieldName ='firstName' AND OF_FormUserData.onlineFormId = ofu.onlineFormId LIMIT 1) firstName,(select value from OF_FormUserData where OF_FormUserData.fieldName ='middleName' AND OF_FormUserData.onlineFormId = ofu.onlineFormId LIMIT 1) middleName,(select value from OF_FormUserData where OF_FormUserData.fieldName ='lastName' AND OF_FormUserData.onlineFormId = ofu.onlineFormId LIMIT 1) lastName,payment.amount,paymentLog.*,paymentLog.status as trans_status,ofu.creationDate,ofu.onlineFormId, ofu.status,inst.name institute_name,cd.name courseTitle,ofu.status as formstatus, ff.status as financeStatus,ff.receiptDate FROM shiksha_courses cd, shiksha_institutes inst,OF_UserForms ofu,OF_Payments payment, OF_PaymentLog paymentLog, OF_PaymentFinanceFields ff WHERE  payment.userId=ofu.userId AND payment.onlineFormId = ofu.onlineFormId AND cd.primary_id = inst.listing_id AND cd.course_id = ofu.courseId AND cd.status='live' AND inst.status='live' AND ofu.courseId!=0 AND ofu.type='course' and payment.paymentId=paymentLog.paymentId and payment.mode='Online' and paymentLog.orderId = ff.orderId and paymentLog.date = ff.transactionDate and ";
		$paymentDetails .= "$orderQuery and $startDateQuery and $endDateQuery and $instituteNameQuery and $modeQuery and $statusQuery and ";
		$paymentDetails .= "$candidateNameQuery $orderbyQuery limit $from , $to";

		$query = $this->dbHandle->query($paymentDetails);
		$results = $query->result_array();
		return $results;
	}

	function addEntriesInFinanceModule(){
                $getOnlineFormDetails="SELECT * FROM OF_PaymentLog";
                $query = $this->dbHandle->query($getOnlineFormDetails);
                $results = $query->result_array();
		foreach ($results as $result){
			$orderId = $result['orderId'];
			$paymentId = $result['paymentId'];
			$receiptDate = $result['date'];
			$status = $result['status'];
			$queryCmd = "INSERT INTO OF_PaymentFinanceFields (paymentId,orderId,receiptDate,status,transactionDate) values (?,?,?,?,?)";
			$this->dbHandle->query($queryCmd, array($paymentId,$orderId,$receiptDate,$status,$receiptDate));
		}

	}
	
}	

