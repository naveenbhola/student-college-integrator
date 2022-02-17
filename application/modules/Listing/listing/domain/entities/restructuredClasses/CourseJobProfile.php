<?php

class CourseJobProfile{
    private $courseId;
    private $percentageEmployed;
    private $averageSalary;
    private $averageSalaryCurrencyId;
    private $popularSectors;
    private $internships;
    private $isInternshipAvailable;
    private $internshipsLink;
    private $careerServicesLink;

    /**
     * @return mixed
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * @param mixed $courseId
     */
    public function setCourseId($courseId)
    {
        $this->courseId = $courseId;
    }

    /**
     * @return mixed
     */
    public function getPercentageEmployed()
    {
        return $this->percentageEmployed;
    }

    /**
     * @param mixed $percentageEmployed
     */
    public function setPercentageEmployed($percentageEmployed)
    {
        $this->percentageEmployed = $percentageEmployed;
    }

    /**
     * @return mixed
     */
    public function getAverageSalary()
    {
        return $this->averageSalary;
    }

    /**
     * @param mixed $averageSalary
     */
    public function setAverageSalary($averageSalary)
    {
        $this->averageSalary = $averageSalary;
    }

    /**
     * @return mixed
     */
    public function getAverageSalaryCurrencyId()
    {
        return $this->averageSalaryCurrencyId;
    }

    /**
     * @param mixed $averageSalaryCurrencyId
     */
    public function setAverageSalaryCurrencyId($averageSalaryCurrencyId)
    {
        $this->averageSalaryCurrencyId = $averageSalaryCurrencyId;
    }

    /**
     * @return mixed
     */
    public function getPopularSectors()
    {
        return $this->popularSectors;
    }

    /**
     * @param mixed $popularSectors
     */
    public function setPopularSectors($popularSectors)
    {
        $this->popularSectors = $popularSectors;
    }

    /**
     * @return mixed
     */
    public function getInternships()
    {
        return $this->internships;
    }

    /**
     * @param mixed $internships
     */
    public function setInternships($internships)
    {
        $this->internships = $internships;
    }

    /**
     * @return mixed
     */
    public function getisInternshipAvailable()
    {
        return $this->isInternshipAvailable;
    }

    /**
     * @param mixed $isInternshipAvailable
     */
    public function setIsInternshipAvailable($isInternshipAvailable)
    {
        $this->isInternshipAvailable = $isInternshipAvailable;
    }

    /**
     * @return mixed
     */
    public function getInternshipsLink()
    {
        return $this->internshipsLink;
    }

    /**
     * @param mixed $internshipsLink
     */
    public function setInternshipsLink($internshipsLink)
    {
        $this->internshipsLink = $internshipsLink;
    }

    /**
     * @return mixed
     */
    public function getCareerServicesLink()
    {
        return $this->careerServicesLink;
    }

    /**
     * @param mixed $careerServicesLink
     */
    public function setCareerServicesLink($careerServicesLink)
    {
        $this->careerServicesLink = $careerServicesLink;
    }


    function __set($property,$value)
    {
        $this->$property = $value;
    }

}

?>