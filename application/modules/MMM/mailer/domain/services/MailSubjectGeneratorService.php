<?php

class MailSubjectGeneratorService
{
    private $CI;
    private $model;
    private $subjectConfig = array();

    function __construct(MailerModel $model)
    {
        $this->CI = & get_instance();
        $this->model = $model;
        
        $this->CI->load->library('mailer/ProductMailerConfig');
        $this->subjectConfig = ProductMailerConfig::getSubjectsForMailers();
    }

    public function setSubjectConfig($subjectConfig)
    {
        $this->subjectConfig = $subjectConfig;
    }

    public function generate($userIds, $mailer)
    {
        $params['boundrySet'] = $userIds;
        $params['mailerId'] = $mailer->getId();
        
        $subjects = array();
        
        if(array_key_exists($mailer->getId(),$this->subjectConfig)){
            $userIdWithCount =  $this->model->getMailerSentforUser($params);
            foreach($userIds as $userId) {
                $subjects[$userId] = $this->subjectConfig[$mailer->getId()][$userIdWithCount[$userId]];
            }
            unset($userIdWithCount);
        }
        unset($params);
        unset($userIds);
        
        return $subjects;
    }
}
