<?php

class StatusCodeMonitoring extends MX_Controller
{
        function sendDailyMail()
        {
                $this->load->model('common/errorexceptionlogmodel');
                // get the objects of the model
                $this->ErrorExceptionLogModelObj = new ErrorExceptionLogModel;

		$date = date("Y-m-d", strtotime("-1 day"));

                $data = $this->ErrorExceptionLogModelObj->getStatusCodeData($date);
		if(count($data) > 0) {

                $message = "<table border='1'><thead><tr><th>Status Code</th><th>Num Requests</th></tr></thead><tbody>";
		foreach($data as $d) {
			$message .= "<tr><td>".$d['status_code']."</td><td>".$d['num_requests']."</td></tr>";
		}
		$message .= "</tbody></table>";
		
                $email_from = "Production Reports <ProductionReports@shiksha.com>"; // Who the email is from
                $email_subject = "HTTP Status Code Report for ".$date; // The Subject of the email 
                $email_message = $message;

                // $email_to = "ankur.gupta@shiksha.com,amit.kuksal@shiksha.com,vikas.k@shiksha.com,sukhdeep.kaur@99acres.com,abhinav.k@shiksha.com,aditya.roshan@shiksha.com,romil.goel@shiksha.com,pranjul.raizada@shiksha.com"; // Who the email is to
                $email_to = "ShikshaProdEmergencyTeam@shiksha.com"; // Who the email is to

                $headers = "From: ".$email_from;
                $semi_rand = md5(time());
                $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
                $headers .= "\nMIME-Version: 1.0\n" .
                            "Content-Type: multipart/mixed;\n" .
                            " boundary=\"{$mime_boundary}\"";

                $email_message .= "This is a multi-part message in MIME format.\n\n" .
"--{$mime_boundary}\n" .
"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
"Content-Transfer-Encoding: 7bit\n\n" .
$email_message .= "\n\n";

                @mail($email_to, $email_subject, $email_message, $headers);

                error_log($numErrors);
		}
	}
}
