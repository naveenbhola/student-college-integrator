<?php

class CronMonitor extends MX_Controller
{
    function __construct()
    {
        $this->load->model('monitor/cronmodel');
    }
    
    function index()
    {
        $data = array();
        $data['cronsForLagMonitoring'] = $this->cronmodel->getCronsForLagMonitoring();
        $data['page'] = 'Main';
        $this->load->view('monitor/CronMonitorMain',$data);
    }
    
    function showCronErrors()
    {
        $data = array();
        $errors = $this->cronmodel->getCronErrors();
        $cronErrors = array();
        foreach($errors as $error) {
            if(date('Y-m-d') == date('Y-m-d',strtotime($error['time']))) {
                $cronErrors['today'][] = $error;
            }
            else if(date('Y-m-d',strtotime('-1 day')) == date('Y-m-d',strtotime($error['time']))) {
                $cronErrors['yesterday'][] = $error;
            }
            else {
                $cronErrors['previous'][] = $error;
            }
        }
        
        $data['errors'] = $cronErrors;
        $data['page'] = 'CronErrors';
        $this->load->view('monitor/CronMonitorErrors',$data);
    }
    
    public function getLag($cron)
    {
        echo $this->cronmodel->getLag($cron);
    }
    
    public function sendAlertForLaggingCrons()
    {
        $this->validateCron();
        $cronsForLagMonitoring = $this->cronmodel->getCronsForLagMonitoring();
        foreach($cronsForLagMonitoring as $cronId => $cron) {
            $lag = $this->cronmodel->getLag($cronId);
            if($lag > $cron['lagLimit']) {
                $this->_sendAlert($cron['name'], "Current Lag: $lag seconds");    
            }
        }
    }
    
    private function _sendAlert($subject,$message)
    {
        $headers = 'From: ProductionMonitor@shiksha.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        $to = "teamldb@shiksha.com";
        mail($to, "CRON ALERT:: ".$subject, $message,$headers);
    }
}
