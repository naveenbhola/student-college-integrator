<?php

/**
 * File representing a field in registration form
 */
namespace registration\libraries;

/**
 * Class representing a field in registration form
 */
class RegistrationFormField
{
    /**
     * unique id of the field
     * @var string 
     */
    private $id;
    
    /**
     * Variable $isVisible 
     * @var bool 
     */
    private $isVisible;
    
    /**
     * $isMandatory Variable
     * @var bool
     */
    private $isMandatory;
    
    /**
     * Variable $order
     * @var integer
     */
    private $order;
    
    /**
     * Variable $values
     * @var array
     */
    private $values;
    
    /**
     * Variable $preSelectedValues
     * @var array
     */
    private $preSelectedValues;
    
    /**
     * variable $label
     * @var string
     */
    private $label;
    
    /**
     * Variable $step
     * @var int
     */
    private $step;
    
    /**
     * variable $type
     * @var string
     */
    private $type;
    
    /**
     * Variable $isCustom
     * @var bool
     */
    private $isCustom;
    
    /**
     * Caption 
     * @var string
     */
    private $caption;
    
    /**
     * String of validation
     * @var string
     */
    private $validations;
    
    /**
     * Form Object
     * @var object \registration\libraries\Forms\AbstractForm
     */
    private $form;
    
    /**
     * Value source of the field i.e. a class that provides possible values of the field
     * If the field is mode, value source will be \registration\libraries\FieldValueSources\Mode
     * 
     * @var \registration\libraries\FieldValueSources\ValueSourceAbstract
     */
    private $valueSource;
    
    /**
     * Constructor
     *
     * @param  string $id
     */
    function __construct($id)
    {
        $this->id = $id;
        $this->isVisible = FALSE;
        $this->isMandatory = FALSE;
        $this->isCustom = FALSE;
    }
    
    /**
     * Set field id
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * Get field id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set pre-selected values of the field
     * The field will come pre-selected/checked/filled with these values
     *
     * @param array $values
     */
    public function setPreSelectedValues($values = array())
    {
        $this->preSelectedValues = $values;
    }
    
    /**
     * Get pre-selected values of the field
     *
     * @return array
     */
    public function getPreSelectedValues()
    {
        return $this->preSelectedValues;
    }
    
    /**
     * Set order of the field
     * Used to decide in which order fields of the form come
     *
     * @param integer $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }
    
    /**
     * Get order of the field
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }
    
    /**
     * Set whether the field is visible or not
     *
     * @param bool $visible
     */
    public function setVisible($visible = TRUE)
    {
        $this->isVisible = $visible;
    }
    
    /**
     * Check whether the field is visible or not
     *
     * @return bool
     */
    public function isVisible()
    {
        return $this->isVisible;
    }
    
    /**
     * Set whether the field is mandatory or not
     *
     * @param bool $mandatory
     */
    public function setMandatory($mandatory = TRUE)
    {
        $this->isMandatory = $mandatory;
    }
    
    /**
     * Check whether the field is mandatory or not
     *
     * @return bool
     */
    public function isMandatory()
    {
        return $this->isMandatory;
    }
    
    /**
     * Set whether the field is custom or not
     *
     * @param bool $custom
     */
    public function setCustom($custom = TRUE)
    {
        $this->isCustom = $custom;
    }
    
    /**
     * Check whether the field is custom or not
     *
     * @return bool
     */
    public function isCustom()
    {
        return $this->isCustom;
    }
    
    /**
     * Set value source of the field
     * Works only for core (non-custom) fields
     *
     * @param object $valueSource | value source class instance
     */
    public function setValueSource(\registration\libraries\FieldValueSources\ValueSourceAbstract $valueSource = NULL)
    {
        $this->valueSource = $valueSource;
    }
    
    /**
     * Set values of the field
     * Works only for custom fields
     *
     * @param array $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }
    
    /**
     * Get values of the field
     * If field is custom - return values previously set using setValue() as it is
     * else fetch values from ValueSource
     *
     * @param array $params
     * @return array
     */
    public function getValues($params = array())
    {
        if($this->isCustom()) {
            return $this->values;
        }
        else {
            if(!$this->valueSource) {
                return array();
            }
            
            $params['form'] = $this->form;
            return $this->valueSource->getValues($params);
        }
    }

    /**
     * Get SA shikhsa Apply values of the field
     * If field is custom - return values previously set using setValue() as it is
     * else fetch values from ValueSource
     *
     * @param array $params
     * @return array
     */

    public function getSAValues($params = array()){
        if($this->isCustom()) {
            return $this->values;
        }
        else {
            if(!$this->valueSource) {
                return array();
            }
            
            $params['form'] = $this->form;
            return $this->valueSource->getSAValues($params);
        }
    }
    
    /**
     * Function to get the Value source of the field
     *
     * @param array $params 
     */
    public function getValueSource($params = array())
    {
        return $this->valueSource;
    }
    
    /**
     * Set step of the field
     *
     * @param int $step
     */
    public function setStep($step)
    {
        $this->step = $step;
    }
    
    /**
     * Get step of the field
     *
     * @return int
     */
    public function getStep()
    {
        return $this->step;
    }
    
    /**
     * Set type of the field (checkbox, radio, select etc)
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * Get type of the field
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Set label of the field
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }
    
    /**
     * Get label of the field
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
    
    /**
     * Set caption of the field
     *
     * @param string $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }
    
    /**
     * Get caption of the field
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }
    
    /**
     * Set field validations
     *
     * @param string $validations
     */
    public function setValidations($validations)
    {
        $this->validations = $validations;
    }
    
    /**
     * Get field validations
     *
     * @return string
     */
    public function getValidations()
    {
        return $this->validations;
    }
    /**
     * Set the form
     *
     * @param object $form object of \registration\libraries\Forms\AbstractForm
     */
    public function setForm(\registration\libraries\Forms\AbstractForm $form)
    {
        $this->form = $form;
    }
    /**
     * Function to get the Form
     *
     * @return object \registration\libraries\Forms\AbstractForm
     */
    public function getForm()
    {
        return $this->form;
    }
}