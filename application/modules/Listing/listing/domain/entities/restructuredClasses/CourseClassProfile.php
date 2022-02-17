<?php

class CourseClassProfile{

    private $courseId;
    private $averageWorkExperience;
    private $averageGpa;
    private $averageXiiPercentage;
    private $averageGmatScore;
    private $averageAge;
    private $percentageInternationalStudents;


//    function __construct()
//    {
//
//    }
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
//
//    /**
//     * @return mixed
//     */
//    public function getAverageWorkExperience()
//    {
//        return $this->averageWorkExperience;
//    }
//
//    /**
//     * @param mixed $averageWorkExperience
//     */
//    public function setAverageWorkExperience($averageWorkExperience)
//    {
//        $this->averageWorkExperience = $averageWorkExperience;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getAverageGpa()
//    {
//        return $this->averageGpa;
//    }
//
//    /**
//     * @param mixed $averageGpa
//     */
//    public function setAverageGpa($averageGpa)
//    {
//        $this->averageGpa = $averageGpa;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getAverageXiiPercentage()
//    {
//        return $this->averageXiiPercentage;
//    }
//
//    /**
//     * @param mixed $averageXiiPercentage
//     */
//    public function setAverageXiiPercentage($averageXiiPercentage)
//    {
//        $this->averageXiiPercentage = $averageXiiPercentage;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getAverageGmatScore()
//    {
//        return $this->averageGmatScore;
//    }
//
//    /**
//     * @param mixed $averageGmatScore
//     */
//    public function setAverageGmatScore($averageGmatScore)
//    {
//        $this->averageGmatScore = $averageGmatScore;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getAverageAge()
//    {
//        return $this->averageAge;
//    }
//
//    /**
//     * @param mixed $averageAge
//     */
//    public function setAverageAge($averageAge)
//    {
//        $this->averageAge = $averageAge;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getPercentageInternationalStudents()
//    {
//        return $this->percentageInternationalStudents;
//    }
//
//    /**
//     * @param mixed $percentageInternationalStudents
//     */
//    public function setPercentageInternationalStudents($percentageInternationalStudents)
//    {
//        $this->percentageInternationalStudents = $percentageInternationalStudents;
//    }

    function __construct()
    {

    }

    public function getAverageWorkExperience()
    {
        return $this->averageWorkExperience;
    }

    public function getAverageGPA()
    {
        return $this->averageGpa;
    }

    public function getAverageXIIPercentage()
    {
        return $this->averageXiiPercentage;
    }

    public function getAverageGMATScore()
    {
        return $this->averageGmatScore;
    }

    public function getAverageAge()
    {
        return $this->averageAge;
    }

    public function getPercenatgeInternationalStudents()
    {
        return $this->percentageInternationalStudents;
    }

    function __set($property,$value)
    {
        $this->$property = $value;
    }


}

?>