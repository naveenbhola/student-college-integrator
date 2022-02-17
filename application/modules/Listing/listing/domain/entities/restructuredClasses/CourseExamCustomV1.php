<?php

class CourseExamCustomV1{
    private $examName;
    private $examCutoff;
    private $examComments;

    /**
     * @return mixed
     */
    public function getExamName()
    {
        return $this->examName;
    }

    /**
     * @param mixed $examName
     */
    public function setExamName($examName)
    {
        $this->examName = $examName;
    }

    /**
     * @return mixed
     */
    public function getExamCutoff()
    {
        return $this->examCutoff;
    }

    /**
     * @param mixed $examCutoff
     */
    public function setExamCutoff($examCutoff)
    {
        $this->examCutoff = $examCutoff;
    }

    /**
     * @return mixed
     */
    public function getExamComments()
    {
        return $this->examComments;
    }

    /**
     * @param mixed $examComments
     */
    public function setExamComments($examComments)
    {
        $this->examComments = $examComments;
    }

    function __set($property,$value)
    {
        $this->$property = $value;
    }

}

?>