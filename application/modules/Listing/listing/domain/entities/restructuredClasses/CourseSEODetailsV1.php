<?php
class CourseSEODetailsV1{

    private $description;
    private $keyword;
    private $title;

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description,$courseName)
    {
            $this->description = "View details for ".$courseName." at {univName}. See details like fees, admissions, scholarship and others.";
    }

    /**
     * @return mixed
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param mixed $keyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title,$courseName)
    {
            $this->title = $courseName." from {univName}, {countryName} | Shiksha.com";

    }

    function __set($property,$value)
    {
        $this->$property = $value;
    }


}

?>