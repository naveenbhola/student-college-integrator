<?php
class CourseExamV1{
    private $examId;
    private $examCutoff;
    private $accepted;
    private $examComments;

    /**
     * @return mixed
     */
    public function getExamId()
    {
        return $this->examId;
    }

    /**
     * @param mixed $examId
     */
    public function setExamId($examId)
    {
        $this->examId = $examId;
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
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * @param mixed $accepted
     */
    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;
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