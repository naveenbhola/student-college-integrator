<?php
/**
 * Class for Sums Mailer web services
 * 
 */
class SumsMailerServer extends MX_Controller
{
    public $fromAddressArr = array('no-reply' => 'no-reply-sums@shiksha.com');

    public $response = '';

    private $db_sums;

    function index()
    {
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');

        $config['functions']['ssendSumsMails'] = array('function'=>'SumsMailerServer.ssendSumsMails');
        $config['functions']['sgetCronMails'] = array('function'=>'SumsMailerServer.sgetCronMails');
        $config['functions']['stransactionDetailStandAlone'] = array('function'=>'SumsMailerServer.stransactionDetailStandAlone');
        $args = func_get_args(); $method = $this->getMethod($config,$args);

        return $this->$method($args[1]);
    }

    /**
     * This method adds new marketing page entry in the database.
     * $this->db_sums = $this->getDbHandler();
     * @access	public
     * @return  object
     */
    private function getDbHandler($flag='read')
    {
        $this->dbLibObj = DbLibCommon::getInstance('SUMS');

        if ( $flag == 'read')
        {
            $this->db_sums = $this->_loadDatabaseHandle();   //For Read Handle
        }
        else
        {
            $this->db_sums = $this->_loadDatabaseHandle('write');   //For Write Handle
        }

        return $this->db_sums;

    }	
    /**
     * Function to send Sums Mails
     */
    function ssendSumsMails($request)
    {

        error_log_shiksha("MAILER_SERVER_SUMS :: AAYAAAA");
        $parameters = $request->output_parameters();
        $this->db_sums = $this->getDbHandler();
        $mailData = json_decode($parameters['1'],true);
        error_log_shiksha("MAILER_SERVER_SUMS :: ".print_r(($mailData),true));
        $transactionId = $mailData['transactionId'];
        $eventType = $mailData['eventType'];
        switch($eventType){
        case 'APPROVAL_QUEUE':
            //Mail to be sent to executive who made sale, Approving Manager
            $this->approvalTransactMail($transactionId);
            $this->response = 'Mails sent';
            break;
        case 'MANAGER_APPROVED':
            //Mail to be sent to executive who made sale, Approving Manager
            $this->managerApprovedMail($transactionId);
            $this->response = 'Mails sent';
            break;
        case 'DUE_PAYMENT_NOTIFICATION':
            $this->response = 'Mails sent';
            break;
        case '15_DAYS_DUE_PAYMENT_NOTIFICATION':
            $this->response = 'Mails sent';
            break;
            // 	default:
            // 	   // @ ToDo Handle invalid Request type here
            // 	   $this->response = 'Mails sending failed';
            // 	   break;
        }
        $response=array(
            array(
                'Error'=>array($this->response,'string')
            ),'struct');

        return $this->xmlrpc->send_response ($response);
    }
    /**
     * Function to send Transaction Approval mail
     * 
     */
    function approvalTransactMail($transactionId){
        $this->db_sums = $this->getDbHandler();

        $query =  "select concat_ws(',',Q.ApproverId,T.SalesBy) as mailUsers from Transaction T,Transaction_Queue Q where T.TransactionId=Q.TransactionId and T.TransactionId=?";
        error_log_shiksha($query);
        $arrResults = $this->db_sums->query($query, array($transactionId));

        $this->load->library(array('alerts_client'));
        $alertClient = new Alerts_client();
        foreach ($arrResults->result() as $row1){
            $fromAddress = $this->fromAddressArr['no-reply'];
//            $transDetail = transactionDetailStandAlone($transactionId);
            $content = html_entity_decode($transDetail[0],ENT_NOQUOTES,'UTF-8');
            $subject = "Appoval request for TransactionId: $transactionId";
            $contentType = "html";

            $toAddressArr = explode(",",$row1->mailUsers);
            foreach($toAddressArr as $toAdd){
                $query = "select email from shiksha.tuser where userid=?";
                error_log_shiksha($query);
                $arrResults2 = $this->db_sums->query($query, array($toAdd));
                foreach ($arrResults2->result() as $row2){
                    $toAddress = $row2->email;
                    $response=$alertClient->externalQueueAdd("12",$fromAddress,$toAddress,$subject,$content,$contentType);
                }
            }
        }
    }
    /**
     * Function to send manager approval mails
     */
    function managerApprovedMail($transactionId){
        $this->db_sums = $this->getDbHandler();

        $query =  "select concat_ws(',',Q.ApproverId,T.SalesBy) as mailUsers from Transaction T,Transaction_Queue Q where T.TransactionId=Q.TransactionId and T.TransactionId=?";
        error_log_shiksha($query);
        $arrResults = $this->db_sums->query($query, array($transactionId));

        $this->load->library(array('alerts_client'));
        $alertClient = new Alerts_client();
        foreach ($arrResults->result() as $row1){
            $fromAddress = $this->fromAddressArr['no-reply'];
            $content = $transactionId. "has been approved!!";
            $subject = "TransactionId: $transactionId has been approved!!";
            $contentType = "html";

            $toAddressArr = explode(",",$row1->mailUsers);
            foreach($toAddressArr as $toAdd){
                $query = "select email from shiksha.tuser where userid=?";
                error_log_shiksha($query);
                $arrResults2 = $this->db_sums->query($query, array($toAdd));
                foreach ($arrResults2->result() as $row2){
                    $toAddress = $row2->email;
                    $response=$alertClient->externalQueueAdd("12",$fromAddress,$toAddress,$subject,$content,$contentType);
                }
            }
        }
    }
    /**
     * Function to send cron Mails
     */
    function sgetCronMails($request)
    {
        $this->db_sums = $this->getDbHandler();
        $parameters = $request->output_parameters();
        $param = $parameters[1];
        error_log_shiksha("sgetCronMails :: ".print_r(($param),true));
        switch($param)
        {
        case '15_DAYS_DUE_PAYMENT_NOTIFICATION':
            $sql = "select Payment.Transaction_Id from Payment inner join Payment_Details on Payment.Payment_Id = Payment_Details.Payment_Id where DATE_ADD(CURDATE(),INTERVAL 15 DAY) <= Payment_Details.Cheque_Date  order by Payment_Details.Cheque_Date desc";
            break;
        case 'DUE_PAYMENT_NOTIFICATION':
            $sql = "select Payment.Transaction_Id from Payment inner join Payment_Details on Payment.Payment_Id = Payment_Details.Payment_Id where Payment_Details.Cheque_Date < CURRENT_DATE order by Payment_Details.Cheque_Date desc";
            break;
        case 'ERROR_ON_REQUEST':
            return $this->xmlrpc->send_error_message('007', 'INVALID_REQUEST_TYPE');
            break;				
        }
        /* execution of sql for payment alerts w.r.t. different cases */
        error_log_shiksha($sql);
        $arrResults = $this->db_sums->query($sql);
        $msgArray = array();
        foreach ($arrResults->result() as $row){
            array_push($msgArray,array(
                array(
                    'id'=>array($row->Transaction_Id,'string')
                ),'struct'));//close array_push
        }
        $response = array($msgArray,'struct');
        error_log_shiksha("sgetCronMails :: ".print_r(($response),true));
        return $this->xmlrpc->send_response ($response);
    }
    /**
     * Function to get Transaction details
     */
    function stransactionDetailStandAlone($request)
    {
        $parameters = $request->output_parameters();
        $transactionId = $parameters['0'];

        $this->load->library(array('sums_mis_query_get_data'));
        $objMISClient = new Sums_mis_query_get_data();
        $paymentAndTransactionDetails= $objMISClient->getTransactionAndPaymentDetails(1,$transactionId);

        if(isset($paymentAndTransactionDetails[0]['transactionDetails'][0]) && is_array($paymentAndTransactionDetails[0]['transactionDetails'][0]))
        {
            $clientId = $paymentAndTransactionDetails[0]['transactionDetails'][0]['ClientUserId'];
        }
        $data['clientDetails'] = $paymentAndTransactionDetails[0]['clientDetails'][0];
        $data['transactionDetails'] = $paymentAndTransactionDetails[0]['transactionDetails'];
        $data['paymentDetails'] = $paymentAndTransactionDetails[0]['paymentDetails'];
        $data['transactionId'] = $transactionId;

        error_log_shiksha(print_r($data,true));

        $transView = $this->load->view('sums/transactionDetailStandAlone',$data,true);
        $response=array(
            array(
                array($transView,'string')
            ),'struct');

        return $this->xmlrpc->send_response ($response);
    }
}

?>
