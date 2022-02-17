<?php
/**
 * sawatchdog Class
 * @package Package sawatchdog
 * @subpackage sawatchdog Front-end
 * @category shiksha
 * @author Ravi Raj<ravi.raj@shiksha.com>
 * @link https://www.shiksha.com
 */

class sawatchdog extends MX_Controller
{

    var $shiksha_db = null;

   /**
    * The default constructor for sawatchdog.
    * @access   public
    */

    public function __construct()
    {
        parent::Controller();
        $this->shiksha_db = $this->url_manager->CheckDB(null,$dbgroup='shiksha',$flag_connect='yes',$flag_alert='yes');
    }
    /*
        :::CRON:::
        curl "https://www.local-shiksha.com/searchAgents/searchAgents_Server/matchingLeads" > /home/raviraj/cron.log 2>&1
        **** In order for a file lock to work correctly it must handle, Atomicity / Race Conditions, and Signaling.
        Use a simplistic lockfile to ensure that convert.php isn't executed multiple times. Something like:
        if (file_exists('/tmp/convert.lock')) {
            exit();
        }
        touch('/tmp/convert.lock');
        // cron code start here
        // cron code end here
        unlink('/tmp/convert.lock');
        :::POINTS:::
        Don't produce any stdout when your cron'ed application completes successfully.
        Don't pipe any output to /dev/null.
        Do produce meaningful stderr output when something goes wrong.
        Do set a $MAILTO address in the crontab to send that error output to LDB team.
        :::OTHER CRONS FOR SA:::
        /searchAgents/searchAgents_Server/updateLeftoverStatus
        /searchAgents/searchAgents/SADeliveryOption/everyhour
        /searchAgents/searchAgents_Server/SAMISReportGenerator
    */
    function checkAllisWell()
    {
        $this->killNotifInterval = 20;
        $this->failureNotifInterval = 6;
        $this->fileForPid = '/tmp/sa_cron.lock';
        $flag_sendmail = false;
        $dbHandle = $this->shiksha_db;
        $sql = "select status,shell_pid,failed_count from  `SALeadAllocationCron` where process = 'ALLOCATION'";
        $query = $dbHandle->query($sql);
        // it must be one row
        if ($query->num_rows() == 1)
        {
            $row = $query->result_array();
            /* if cron is still running */
            if ($row[0]['status'] == 'ON')
            {
                $shellCommand = 'ps ' . $row[0]['shell_pid'] . ' | grep ' . $row[0]['shell_pid'];
                if (shell_exec ( $shellCommand ))
                {
                    /* pid exist now check failed_count value */
                    if ($row[0]['failed_count'] == $this->killNotifInterval)
                    {
                        $pid = $row[0]['shell_pid'];
                        /* kill Notify count reached .. plz send mail */
                        $flag_sendmail = true;
                        $msg = 'cron kill notify reached. however it will manage itself but need to cross check';
                    }
                    else if ( (($row[0]['failed_count']) % ($this->failureNotifInterval) == 0) && ($row[0]['failed_count'] > 0))
                    {
                        /* failed count reached .. plz send mail */
                        $flag_sendmail = true;
                        $msg = 'cron failed count is reached.however it will manage itself but need to cross check';
                    }
                } else {
                    /* No pid exist !!! */
                    /* send mail        */
                    $flag_sendmail = true;
                    $msg = 'cron status in db is on but shell pid not found. pls look issue ASAP';
                }
            }
            else if ($row[0]['status'] == 'OFF')
            {
                /* if cron is not running */
                shell_exec ( 'ps ux | awk \'/matchingLeads/ && !/awk/ {print $2}\' >' . $this->fileForPid );
                $Handle = fopen ( $this->fileForPid, 'r' );
                $pid = fread ( $Handle, 5 );
                fclose ( $Handle );
                /* if pid null then send mail that cron is not running */
                if (empty($pid))
                {
                    $flag_sendmail = true;
                    $msg = 'cron status in db is off and shell pid get empty. pls look issue ASAP';
                }
            }
            else
            {
                /* notify cron is not running */
                $flag_sendmail = true;
                $msg = 'crons is not running.';
            }
        }
        else
        {
               /* notify that more than one instance are running */
               $flag_sendmail = true;
               $msg = "cron's more than 1 instances are running. please look issue ASAP";
        }

        if ($flag_sendmail)
        {
            $to  = 'ravi.raj@shiksha.com';
            $subject = 'Search Agent Matching Cron Stopped !!!';
            // message
            $message = '';
            $message .= "\n at " . date('l jS \of F Y h:i:s A');
            $message .= "\n Error details ::" . $msg;
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            // Additional headers
            $headers .= 'To: Ankur Gupta <ankur.gupta@shiksha.com>' . "\r\n";
            $headers .= 'From: info <info@shiksha.com>' . "\r\n";
            $headers .= 'Cc: Amit Kuksal <amit.kuksal@shiksha.com>' . "\r\n";
            $headers .= 'Bcc: sachin Singhal <sachin.singhal@brijj.com>' . "\r\n";
            // Mail it
            @mail($to, $subject, $message, $headers);
        }
    }

}

?>