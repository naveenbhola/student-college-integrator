<?php

class Response implements \JsonSerializable
{
    private $textResponsesArray;
    private $optionResponses;
    private $finalTextResponse ;
    private $disableTextBox;
    private $optionsPosition;
    private $isOptionsResponse;
    private $isTextResponse;
    
    
    private $customData;
    private $nextRequestType;
    private $isErrorInResponse;
    private $errorMessage;
    private $disableSendButton;

    // Options 
    private $attachClickHandler;
    private $attachOnClass;
    private $attachOnId ;
    private $attrForClick;
    private $clickHandlerName;

    // Key Up in TextBox
    private $attachKeyUpHandler;
    private $attrForKeyUp;
    private $keyUpHandleName;
    private $autoSuggestorFor;

    private $isOptionsMultiSelect;
    private $isCustomDataForTextRequest;

    function __construct()
    {
        $this->textResponsesArray = array();
        $this->optionResponses = "";
        $this->finalTextResponse = "";
        $this->disableTextBox = false;
        $this->optionsPosition = 'top';
        $this->isOptionsResponse = false;
        $this->isTextResponse = true;
        $this->attachClickHandler = false;
        $this->attachOnClass = false;
        $this->attachOnId = false;
        $this->attrForClick = ".optionsUl-Li";
        $this->clickHandlerName = "clickForOptions";
        $this->nextRequestType = "";
        $this->isErrorInResponse = false;
        $this->errorMessage = "";
        $this->customData = array();
        $this->disableSendButton = false;

        $this->attachKeyUpHandler = false;
        $this->attrForKeyUp = "#chatBox";
        $this->keyUpHandleName = "keyUpHandleForChatBox";
        $this->autoSuggestorFor = "";

        $this->isOptionsMultiSelect = false;
        $this->isCustomDataForTextRequest = false;

        
    }
    
   /* public function iterateObject(){
        _p($this);
    }
*/  
    public function JsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }
    public function getTextResponsesArray()
    {
        return $this->textResponsesArray;
    }
    
    public function getOptionResponses()
    {
        return $this->optionResponses;
    }

    public function getFinalTextResponse(){
        return $this->finalTextResponse;
    }

    public function getDisableTextBox(){
        return $this->disableTextBox;
    }

    public function getOptionsPosition(){
        return $this->optionsPosition;
    }

    public function getIsOptionsResponse(){
        return $this->isOptionsResponse;
    }

    public function getIsTextResponse(){
        return $this->isTextResponse;
    }

    public function getAttachClickhandler(){
        return $this->attachClickHandler;
    }

    public function getAttachOnClass(){
        return $this->attachOnClass;
    }

    public function getAttachOnId(){
        return $this->attachOnId;
    }

    public function getAttrForClick(){
        return $this->attrForClick;
    }

    public function getClickHandlerName(){
        return $this->clickHandlerName;
    }

    public function getCustomData(){
        return $this->customData;
    }

    public function setTextResponsesArray($textResponsesArray)
    {
        $this->textResponsesArray = $textResponsesArray;
    }
    
    public function setOptionResponses($optionResponses)
    {
        $this->optionResponses = $optionResponses;
    }

    public function setFinalTextResponse($finalTextResponse){
        $this->finalTextResponse = $finalTextResponse;
    }

    public function setDisableTextBox($disableTextBox){
        $this->disableTextBox = $disableTextBox;
    }

    public function setOptionsPosition($optionsPosition){
        $this->optionsPosition = $optionsPosition;
    }

    public function setIsOptionsResponse($isOptionsResponse){
        $this->isOptionsResponse = $isOptionsResponse;
    }

    public function setIsTextResponse($isTextResponse){
        $this->isTextResponse = $isTextResponse;
    }

    public function setAttachClickhandler($attachClickHandler){
        $this->attachClickHandler = $attachClickHandler;
    }

    public function setAttachOnClass($attachOnClass){
        $this->attachOnClass = $attachOnClass;
    }

    public function setAttachOnId($attachOnId){
        $this->attachOnId = $attachOnId;
    }

    public function setAttrForClick($attrForClick){
        $this->attrForClick = $attrForClick;
    }

    public function setClickHandlerName($clickHandlerName){
        $this->clickHandlerName = $clickHandlerName;
    }

    public function setCustomData($customData){
        $this->customData = $customData;
    }

    public function setNextRequestType($nextRequestType){
        $this->nextRequestType = $nextRequestType;
    }

    public function getNextRequestType($nextRequestType){
        return $this->nextRequestType;
    }
    
    public function setIsErrorInResponse($isErrorInResponse){
        $this->isErrorInResponse = $isErrorInResponse;
    }

    public function getIsErrorInResponse(){
        return $this->isErrorInResponse;
    }

    public function setErrorMessage($errorMessage){
        $this->errorMessage = $errorMessage;
    }

    public function getErrorMessage(){
        return $this->errorMessage;
    }

    public function setDisableSendButton($disableSendButton){
        $this->disableSendButton = $disableSendButton;
    }

    public function getDisableSendButton($disableSendButton){
        return $disableSendButton;
    }

    public function setAttachKeyUpHandler($attachKeyUpHandler){
        $this->attachKeyUpHandler = $attachKeyUpHandler;
    }

    public function getAttachKeyUpHandler(){
        return $this->attachKeyUpHandler;
    }

    public function getAttrForKeyUp(){
        return $this->attrForKeyUp;
    }
    
    public function setAttrForKeyUp($attrForKeyUp){
        $this->attrForKeyUp = $attrForKeyUp;
    }
        
    public function setKeyUpHandlerName($keyUpHandleName){
        $this->keyUpHandleName = $keyUpHandleName;;
    }

    public function setAutoSuggestorFor($autoSuggestorFor){
        $this->autoSuggestorFor = $autoSuggestorFor;
    }

    public function getAutoSuggestorFor(){
        return $this->autoSuggestorFor;
    }

    public function setIsOptionsMultiSelect($isOptionsMultiSelect){
        $this->isOptionsMultiSelect = $isOptionsMultiSelect;
    }

    public function getIsOptionsMultiSelect(){
        return $this->isOptionsMultiSelect;
    }

    public function getIsCustomDataForTextRequest(){
        return $this->isCustomDataForTextRequest;
    }

    public function setIsCustomDataForTextRequest($isCustomDataForTextRequest){
        $this->isCustomDataForTextRequest = $isCustomDataForTextRequest;
    }

}