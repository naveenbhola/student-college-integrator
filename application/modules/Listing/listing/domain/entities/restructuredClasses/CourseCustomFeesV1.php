<?php

class CourseCustomFeesV1{
    private $customFeeName;
    private $customFeeValue;

    /**
     * @return mixed
     */
    public function getCustomFeeName()
    {
        return $this->customFeeName;
    }

    /**
     * @param mixed $customFeeName
     */
    public function setCustomFeeName($customFeeName)
    {
        $this->customFeeName = $customFeeName;
    }

    /**
     * @return mixed
     */
    public function getCustomFeeValue()
    {
        return $this->customFeeValue;
    }

    /**
     * @param mixed $customFeeValue
     */
    public function setCustomFeeValue($customFeeValue)
    {
        $this->customFeeValue = $customFeeValue;
    }

    function __set($property,$value)
    {
        $this->$property = $value;
    }

}

?>