<?php
require_once(APPPATH . '/controllers/test/Toast.php');

class my_Test_Tags extends Toast {

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

    public function test_tagDetailPage_withoutTagId() {

        $url = "Tags/getTagDetailPage";
        $post = "";
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
                                'error' => array(array('field' => 'tagId', 'errorMessage' => 'Please enter your tagId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_tagDetailPage_withExtraLongTagId() {

        $url = "Tags/getTagDetailPage/48543756845356356";
        $post = "";
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
                                'error' => array(array('field' => 'tagId', 'errorMessage' => 'Please fill the tagId with 1 to 10 digits')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    public function test_tagDetailPage_withNonExistingTagId() {

        $url = "Tags/getTagDetailPage/485437568";
        $post = "";
        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Tag doesn\'t exist.',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    public function test_tagDetailPage_withTagId() {

        $url = "Tags/getTagDetailPage/589555";
        $post = "";
        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        unset ($outputArray['content']);
        unset ($outputArray['relatedTags']);
        
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'tagName' => 'Oncology state run college',
                                'tagType' => 'Specialization varients synonym',
                                'description' => '',
                                'questionCount' => '0',
                                'discussionCount' => '0',
                                'followerCount' => '0',
                                'expertCount' => '0',
                                'isUserFollowing' => 'false',
                                'showActiveUserRecommendationsAtPostion' => 4,
                                'showTagsRecommendationsAtPostion' => 9,
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
