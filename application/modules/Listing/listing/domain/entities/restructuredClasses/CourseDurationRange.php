<?php
class CourseDurationRange{
    private $minDuration;
    private $maxDuration;

    /**
     * @return mixed
     */
    public function getMinDuration()
    {
        return $this->minDuration;
    }

    /**
     * @param mixed $minDuration
     */
    public function setMinDuration($minDuration)
    {
        $this->minDuration = $minDuration;
    }

    /**
     * @return mixed
     */
    public function getMaxDuration()
    {
        return $this->maxDuration;
    }

    /**
     * @param mixed $maxDuration
     */
    public function setMaxDuration($maxDuration)
    {
        $this->maxDuration = $maxDuration;
    }

    function __set($property,$value)
    {
        $this->$property = $value;
    }
}