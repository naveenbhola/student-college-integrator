<?php
require_once(APPPATH . '/controllers/test/Toast.php');

class my_Test_Post extends Toast {

    private $urlPrefix = "http://shikshatest03.infoedge.com/v1/";
    private $headerValues = array('SOURCE: AndroidShiksha','authKey:55ba14620e26e');

    function __construct() {
        parent::Toast(__FILE__); // Remember this
    }

    //Common function to make CURL call to our API
    private function makeCurlCall($url, $post,$header=array()){
        $allHeaders = $this->headerValues;
        if(!empty($header))
        {
                foreach($header as $key=>$value)
                {
                        $allHeaders[] = $key.': '.$value;
                }
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->urlPrefix.$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$allHeaders);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //Test entity rating api without entityId
    public function test_setEntityRating_withoutEntityId() {

        $url = "AnAPost/setEntityRating";
        $entityId = "";
        $entityType = "Answer";
        $digVal = 1;
        $pageType = "qna";

        $post = "entityId=$entityId&entityType=$entityType&digVal=$digVal&pageType=$pageType";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'entityId', 'errorMessage' => 'Please enter your entityId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test entity rating api without digVal
    public function test_setEntityRating_withoutDigVal() {

        $url = "AnAPost/setEntityRating";
        $entityId = 3161864;
        $entityType = "Answer";
        $digVal = "";
        $pageType = "qna";

        $post = "entityId=$entityId&entityType=$entityType&digVal=$digVal&pageType=$pageType";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'digVal', 'errorMessage' => 'Please enter your digVal.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test entity rating api with digVal having more than 3 digits
    public function test_setEntityRating_withIncorrectDigVal() {

        $url = "AnAPost/setEntityRating";
        $entityId = 3161864;
        $entityType = "Answer";
        $digVal = 65567567;
        $pageType = "qna";

        $post = "entityId=$entityId&entityType=$entityType&digVal=$digVal&pageType=$pageType";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'digVal', 'errorMessage' => 'Please fill the digVal with 1 to 3 digits')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test entity rating api with digVal except 1 or 0
    public function test_setEntityRating_unexpectedDigVal() {

        $url = "AnAPost/setEntityRating";
        $entityId = 3161864;
        $entityType = "Answer";
        $digVal = 32;
        $pageType = "qna";

        $post = "entityId=$entityId&entityType=$entityType&digVal=$digVal&pageType=$pageType";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'dig value is not correct',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }


    //Test entity rating api with correct digVal
    public function test_setEntityRating_withCorrectParams() {

        $url = "AnAPost/setEntityRating";
        $entityId = 3161864;
        $entityType = "Answer";
        $digVal = 1;
        $pageType = "qna";

        $post = "entityId=$entityId&entityType=$entityType&digVal=$digVal&pageType=$pageType";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Thanks for rating this answer',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test entity rating api without pageType
    public function test_setEntityRating_withOutPageType() {

        $url = "AnAPost/setEntityRating";
        $entityId = 3161864;
        $entityType = "Answer";
        $digVal = 1;
        $pageType = "";

        $post = "entityId=$entityId&entityType=$entityType&digVal=$digVal&pageType=$pageType";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Thanks for rating this answer',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test entity rating api with owner userId
    public function test_setEntityRating_withOwnerUserId() {

        $url = "AnAPost/setEntityRating";
        $entityId = 2875000;
        $entityType = "Answer";
        $digVal = 1;
        $pageType = "";

        $post = "entityId=$entityId&entityType=$entityType&digVal=$digVal&pageType=$pageType";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'You can not rate your own Answer.',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test delete api for entity without msgId
    public function test_deleteEntityFromCMS_withOutMsgId() {

        $url = "AnAPost/deleteEntityFromCMS";
        $msgId = "";
        $threadId = "";
        $entityType ="";
        $ownerUserId ="";

        $post = "msgId=$msgId&threadId=$threadId&entityType=$entityType";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'msgId', 'errorMessage' => 'Please enter your msgId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test delete api for entity without threadId
    public function test_deleteEntityFromCMS_withOutThreadId() {

        $url = "AnAPost/deleteEntityFromCMS";
        $msgId = "2875000";
        $threadId = "";
        $entityType ="Answer";
        $ownerUserId ="11";

        $post = "msgId=$msgId&threadId=$threadId&entityType=$entityType";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'threadId', 'errorMessage' => 'Please enter your threadId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test delete api for entity without ownerUserId
    public function test_deleteEntityFromCMS_withOutOwnerUserId() {

        $url = "AnAPost/deleteEntityFromCMS";
        $msgId = "2875000";
        $threadId = "2873132";
        $entityType ="Answer";
        $ownerUserId ="";

        $post = "msgId=$msgId&threadId=$threadId&entityType=$entityType";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'userValidate' => '11',
                                'result' => 'deleted',
                                'msgId' => '2875000',
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test delete api for entity with correct params
    public function test_deleteEntityFromCMS_withCorrectParams() {

        $url = "AnAPost/deleteEntityFromCMS";
        $msgId = "2875000";
        $threadId = "2873132";
        $entityType ="Answer";
        $ownerUserId ="";

        $post = "msgId=$msgId&threadId=$threadId&entityType=$entityType";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'userValidate'=> "11",
                                'result'=> "deleted",
                                'msgId'=> "2875000",
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_postCommentOnAnswer_withNoQuestionId() {
        $url = "AnAPost/postComment";
        $topicId = "";
        $parentId = "3233255";
	$commentText = "commentText";
        $type ="question";
        $requestIP ="127.0.0.1";

        $post = "topicId=$topicId&parentId=$parentId&type=$type&requestIP=$requestIP&commentText=$commentText";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'topicId', 'errorMessage' => 'Please enter your topicId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test post comment api with correct params
    public function test_postCommentOnAnswer_withCorrectParams() {

        $url = "AnAPost/postComment";
        $topicId = "3232251";
        $parentId = "3233255";
	$commentText = "Unit testing Text";
        $type ="question";
        $requestIP ="127.0.0.1";

        $post = "topicId=$topicId&parentId=$parentId&type=$type&requestIP=$requestIP&commentText=$commentText";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
	unset($outputArray['commentId']);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_postCommentOnDiscussion_withNoDiscussionId() {
        $url = "AnAPost/postComment";
        $topicId = "";
        $parentId = "3233255";
	$commentText = "commentText";
        $type ="discussion";
        $requestIP ="127.0.0.1";

        $post = "topicId=$topicId&parentId=$parentId&type=$type&requestIP=$requestIP&commentText=$commentText";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'topicId', 'errorMessage' => 'Please enter your topicId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test post comment api with correct params
    public function test_postCommentOnDiscussion_withCorrectParams() {

        $url = "AnAPost/postComment";
        $topicId = "3233252";
        $parentId = "3233252";
	$commentText = "Unit testing Text";
        $type ="discussion";
        $requestIP ="127.0.0.1";

        $post = "topicId=$topicId&parentId=$parentId&type=$type&requestIP=$requestIP&commentText=$commentText";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
	unset($outputArray['commentId']);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_postReplyOnDiscussion_withNoCommentId() {
        $url = "AnAPost/postComment";
        $topicId = "3233252";
        $parentId = "";
	$commentText = "commentText";
        $type ="discussion";
        $requestIP ="127.0.0.1";

        $post = "topicId=$topicId&parentId=$parentId&type=$type&requestIP=$requestIP&commentText=$commentText";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'parentId', 'errorMessage' => 'Please enter your parentId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test post comment api with correct params
    public function test_postReplyOnDiscussion_withCorrectParams() {

        $url = "AnAPost/postComment";
        $topicId = "3233252";
        $parentId = "3233263";
	$commentText = "Unit testing Text";
        $type ="discussion";
        $requestIP ="127.0.0.1";

        $post = "topicId=$topicId&parentId=$parentId&type=$type&requestIP=$requestIP&commentText=$commentText";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
	unset($outputArray['commentId']);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    

    public function test_editCommentOnAnswer_withNoQuestionId() {
        $url = "AnAPost/postComment";
        $topicId = "";
        $parentId = "3233255";
	$commentText = "commentText";
        $type ="question";
        $requestIP ="127.0.0.1";
	$editEntityId = "";

        $post = "topicId=$topicId&parentId=$parentId&type=$type&requestIP=$requestIP&commentText=$commentText&editEntityId=$editEntityId";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'topicId', 'errorMessage' => 'Please enter your topicId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test post comment api with correct params
    public function test_editCommentOnAnswer_withCorrectParams() {

        $url = "AnAPost/postComment";
        $topicId = "3232251";
        $parentId = "3233255";
	$commentText = "Unit testing Text 11";
        $type ="question";
        $requestIP ="127.0.0.1";
	$editEntityId = "3233272";

        $post = "topicId=$topicId&parentId=$parentId&type=$type&requestIP=$requestIP&commentText=$commentText&editEntityId=$editEntityId";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        //Check if the Output is same as expected output
        $expectedOutput = array(
				'message' => 'Comment Edited',
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_editCommentOnDiscussion_withNoDiscussionId() {
        $url = "AnAPost/postComment";
        $topicId = "";
        $parentId = "3233255";
	$commentText = "commentText";
        $type ="discussion";
        $requestIP ="127.0.0.1";
	$editEntityId = "";

        $post = "topicId=$topicId&parentId=$parentId&type=$type&requestIP=$requestIP&commentText=$commentText&editEntityId=$editEntityId";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'topicId', 'errorMessage' => 'Please enter your topicId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test post comment api with correct params
    public function test_editCommentOnDiscussion_withCorrectParams() {

        $url = "AnAPost/postComment";
        $topicId = "3233252";
        $parentId = "3233252";
	$commentText = "Unit testing Text11";
        $type ="discussion";
        $requestIP ="127.0.0.1";
	$editEntityId="3233271";

        $post = "topicId=$topicId&parentId=$parentId&type=$type&requestIP=$requestIP&commentText=$commentText&editEntityId=$editEntityId";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        //Check if the Output is same as expected output
        $expectedOutput = array(
				'message' => 'Comment Edited',
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_editReplyOnDiscussion_withNoCommentId() {
        $url = "AnAPost/postComment";
        $topicId = "3233252";
        $parentId = "";
	$commentText = "commentText";
        $type ="discussion";
        $requestIP ="127.0.0.1";
	$editEntityId = "";

        $post = "topicId=$topicId&parentId=$parentId&type=$type&requestIP=$requestIP&commentText=$commentText&editEntityId=$editEntityId";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'parentId', 'errorMessage' => 'Please enter your parentId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test post comment api with correct params
    public function test_editReplyOnDiscussion_withCorrectParams() {

        $url = "AnAPost/postComment";
        $topicId = "3233252";
        $parentId = "3233263";
	$commentText = "Unit testing Text11";
        $type ="discussion";
        $requestIP ="127.0.0.1";
	$editEntityId = "3233274";

        $post = "topicId=$topicId&parentId=$parentId&type=$type&requestIP=$requestIP&commentText=$commentText&editEntityId=$editEntityId";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        //Check if the Output is same as expected output
        $expectedOutput = array(
				'message' => 'Comment Edited',
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_shareEntity_withoutEntityId() {

        $url = "AnAPost/shareEntity";
        $entityId = "";
        $entityType ="discussion";
	$destination = "facebook";

        $post = "entityId=$entityId&entityType=$entityType&destination=$destination";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'entityId', 'errorMessage' => 'Please enter your entityId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    public function test_shareEntity_withCorrectParams() {

        $url = "AnAPost/shareEntity";
        $entityId = "3233252";
        $entityType ="discussion";
	$destination = "facebook";

        $post = "entityId=$entityId&entityType=$entityType&destination=$destination";

        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

}
