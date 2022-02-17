<?php
/**
 * File representing an abstract MMP form
 */
namespace registration\libraries\Forms\MMP;

/**
 * Class representing an abstract MMP form
 */
abstract class AbstractMMP extends \registration\libraries\Forms\AbstractForm
{
    /**
     * @var integer MMP form id
     */
    protected $mmpFormId;
    
    /**
     * @var integer ID of a course in MMP form
     */
    protected $courseId;
    
    /**
     * @var integer ID of a course group in MMP form
     */
    protected $courseGroupId;
    
    /**
     * @var array MMP form details
     */
    protected $mmpDetails;
    
    /**
     * Constructor
     * 
     * @param integer $mmpFormId
     * @param integer $courseId
     * @param integer $courseGroupId
     */ 
    function __construct($mmpFormId,$courseId,$courseGroupId)
    {
        parent::__construct();
        
        $this->mmpFormId = $mmpFormId;
        $this->courseId = $courseId;
        $this->courseGroupId = $courseGroupId;
    }
    
    /**
     * Set MMP Form details
     *
     * @param array $mmpDetails
     */
    public function setMMPDetails($mmpDetails)
    {
        $this->mmpDetails = $mmpDetails;
    }
    
    /**
     * Get MMP Form details
     *
     * @return array $mmpDetails
     */
    public function getMMPDetails()
    {
        if(!$this->mmpDetails) {
            $this->mmpDetails = $this->mmpModel->getMMPDetails($this->mmpFormId);
        }
        return $this->mmpDetails;
    }
    
    /**
     * Get rules to be applied on field of the form
     * 
     * @return array 
     */
    public function getRules()
    {
        $masterRules = \registration\builders\RegistrationBuilder::getMasterRules(array('mmpId' => $this->mmpFormId,'courseGroup' => $this->getGroupType()));
        $customRules = $this->getCustomRules();
        return array_merge($masterRules,$customRules);
    }
}