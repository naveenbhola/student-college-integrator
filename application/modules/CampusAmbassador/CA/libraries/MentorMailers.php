<?php 
class MentorMailers
{
    function __construct()
    {
        $this->CI = & get_instance();
    }
    
    /**
    * This function sends an email to internal team
    * @author Virender Singh <virender.singh@shiksha.com>
    * @date   2015-07-22
    * @param  $mailTemplateType mailer template type
    * @param  $mdata data to be sent in mail
    * @behavior makes entry in tMailQueue
    * @return none
    */
    function mentorshipProgramInternalTeamMailers($mailTemplateType, $mdata)
    {
        $emails = array('aneeket.barua@shiksha.com', 'neda.ishtiaq@shiksha.com', 'megha.bhardwaj@shiksha.com', 'ambreen.khan@shiksha.com');
        $this->CI->load->library('alerts_client');
        $alertClient = new Alerts_client();
        switch($mailTemplateType)
        {
            case 'chatSchedulingActionMailer':
                $mailBody = $this->CI->load->view('mentorship/chatSchedulingMailerOnAction', $mdata, true);
                for($i=0; $i<count($emails); $i++)
                {
                    $alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emails[$i], $mdata['mailSubject'], $mailBody, "html", '');
                }
                break;
        }
    }

    function sendMenteeRegistrationMailertoMentee($menteeId){

    // Get Mentee data using MenteeId
        $this->CI->load->model('mentormodel');
        $this->mentormodel = new mentormodel();
        $result = $this->mentormodel->getMenteeDetails($menteeId);
        $menteeData['type'] = 'MenteeRegistrationMailtoMentee';
        foreach($result as $value) {
            $menteeData['main'] = $value;
            $menteeData['main']['location'][] = $value['prefCollegeLocation1'];
            $menteeData['main']['location'][] = $value['prefCollegeLocation2'];
            $menteeData['main']['location'][] = $value['prefCollegeLocation3'];
            $menteeData['main']['location'] = array_filter($menteeData['main']['location']);
            $menteeData['main']['branches'][] = $value['prefEngBranche1'];
            $menteeData['main']['branches'][] = $value['prefEngBranche2'];
            $menteeData['main']['branches'][] = $value['prefEngBranche3'];
            $menteeData['main']['branches'] = array_filter($menteeData['main']['branches']);

        }
        $emailAndName = $this->mentormodel->getMenteeEmailIdAndName($menteeData['main']['userId']);
        foreach($emailAndName as $value) {
            $menteeData['other'] = $value;
        }
        $examTaken = $this->mentormodel->getMenteeExamTaken($menteeId);
        foreach($examTaken as $value) {
            $menteeData['examName'][] = $value['examName'];
        }   
        $userEmail = $emailAndName[0]['email'];  
        Modules::run('systemMailer/SystemMailer/menteeRegistrationMailtoMentee',$userEmail,$menteeData);
        $this->sendMenteeRegistrationMailertoInternalTeam($menteeData['other']['firstname'],$menteeData['other']['lastname'],$menteeData['other']['email'],$menteeData['other']['mobile'] );
}
        


        function sendMenteeRegistrationMailertoInternalTeam($firstname, $lastname, $email, $mobile){
        
        $this->CI->load->library('alerts_client');
        $alertClient = new Alerts_client();
        $subject = "New mentee enrolled for mentorship program";
        $emails = array('aneeket.barua@shiksha.com', 'neda.ishtiaq@shiksha.com','megha.bhardwaj@shiksha.com');
        $contentforInnerMail = '<p>Hi,</p>
        <p>New submission has been made by the following mentee for mentorship program :</p>
        <p>&nbsp;</p>
        <div>
        <table>
        <tr>
            <td>Name: </td>
            <td>'.$firstname.'&nbsp;'.$lastname.'</td>
        </tr>
        <tr>
            <td>Email: </td>
            <td>'.$email.'</td>
        </tr>
        <tr>
            <td>Mobile: </td>
            <td>'.$mobile.'</td>
        </tr>
        </table>
        <p>&nbsp;</p>
        <p>Please use the Mentorship moderation panel to assign a mentor for this user.</p>
        </div>
        <p>&nbsp;</p>
        <p>Best wishes,</p>
        <p>Shiksha.com</p>';
        for($i=0; $i<count($emails); $i++)
        {
            $alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emails[$i], $subject, $contentforInnerMail, "html", '');
        }

}

    function sendChatCompletionUpdatetoInternalTeam($menteeFname,$menteeLname,$mentorFname,$mentorLname,$slotTime,$CMSLink){
        
        $this->CI->load->library('alerts_client');
        $alertClient = new Alerts_client();
        $subject = "New chat session completed";
        $emails = array('aneeket.barua@shiksha.com', 'neda.ishtiaq@shiksha.com', 'megha.bhardwaj@shiksha.com', 'ambreen.khan@shiksha.com');
        $contentforInnerMail = '<p>Hi,</p>
        <p>&nbsp;</p>
        <p>The following chat has been completed</p>
        <p>Mentor name : '.$mentorFname.' '.$mentorLname.'</p>
        <p>Mentee name : '.$menteeFname.' '.$menteeLname.'</p>
        <p>Chat slot : '.$slotTime.'</p>
        <div>
        
        <a href="'.$CMSLink.'">View moderation panel</a>
        </div>
        <p>&nbsp;</p>
        <p>Regards,</p>
        <p>Shiksha.com</p>';
        for($i=0; $i<count($emails); $i++)
        {
            $alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emails[$i], $subject, $contentforInnerMail, "html", '');
        }

}


};?>