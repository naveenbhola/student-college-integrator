<?php

class RecruitingCompanyV1{
    private $companyId;
    private $companyName;
    private $companyOrder;
    private $logoUrl;
    private $courseId;

    /**
     * @return mixed
     */
    function __construct()
    {

    }

//    public function getCompanyId()
//    {
//        return $this->companyId;
//    }

//    /**
//     * @param mixed $companyId
//     */
//    public function setCompanyId($companyId)
//    {
//        $this->companyId = $companyId;
//    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return trim($this->companyName);
    }

    public function getLogoURL()
    {
        if(!empty($this->logoUrl)){
            return MEDIAHOSTURL.$this->logoUrl;
        }
        return;
    }


//    /**
//     * @param mixed $companyName
//     */
//    public function setCompanyName($companyName)
//    {
//        $this->companyName = $companyName;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getCompanyOrder()
//    {
//        return $this->companyOrder;
//    }
//
//    /**
//     * @param mixed $companyOrder
//     */
//    public function setCompanyOrder($companyOrder)
//    {
//        $this->companyOrder = $companyOrder;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getLogoUrl()
//    {
//        return $this->logoUrl;
//    }
//
//    /**
//     * @param mixed $logoUrl
//     */
//    public function setLogoUrl($logoUrl)
//    {
//        $this->logoUrl = $logoUrl;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getCourseId()
//    {
//        return $this->courseId;
//    }
//
//    /**
//     * @param mixed $courseId
//     */
//    public function setCourseId($courseId)
//    {
//        $this->courseId = $courseId;
//    }

    function __set($property,$value)
    {
        $this->$property = $value;
    }

}

?>