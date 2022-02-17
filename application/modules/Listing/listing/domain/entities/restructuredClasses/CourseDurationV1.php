<?php

class CourseDurationV1{

    private $durationValue;
    private $durationValueRange;
    private $durationUnit;


    /**
     * @return mixed
     */
    public function getDurationValue()
    {
        return $this->durationValue;
    }

    /**
     * @param mixed $durationValue
     */
    public function setDurationValue($durationValue)
    {
        $this->durationValue = $durationValue;
    }

    /**
     * @return mixed
     */
    public function getDurationValueRange()
    {
        return $this->durationValueRange;
    }

    /**
     * @param mixed $durationValueRange
     */
    public function setDurationValueRange($durationValueRange)
    {
        $this->durationValueRange = $durationValueRange;
    }

    /**
     * @return mixed
     */
    public function getDurationUnit()
    {
        return $this->durationUnit;
    }

    /**
     * @param mixed $durationUnit
     */
    public function setDurationUnit($durationUnit)
    {
        $this->durationUnit = $durationUnit;
    }

    function __set($property,$value)
    {
        $this->$property = $value;
    }


}

?>