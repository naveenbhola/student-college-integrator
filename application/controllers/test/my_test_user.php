<?php
require_once(APPPATH . '/controllers/test/Toast.php');

class my_Test_User extends Toast {

    private $urlPrefix = "http://shikshatest03.infoedge.com/v1/";
    private $headerValues = array('SOURCE: AndroidShiksha','authKey: 55ba14620e26e');
    
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

    //Test Login API with incorrect Email address
    public function test_Login_WrongEmail() {

        $url = "User/login";
        $post = "email=pisces.ankur&password=P@ssw0rd";
        $output = $this->makeCurlCall($url, $post);
        
        //Now, parse the output
        $outputArray = json_decode($output,true);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '',
                                'error' => array(
						array(
							'field' => 'email',
							'errorMessage'  => 'Please enter correct email'
						     )
						),
				'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test Login API with Incorrect Credentials i.e. Email and Password will not match
    public function test_Login_IncorrectCredentials() {

        $url = "User/login";
        $post = "email=pisces.ankur@gmail.com&password=sksjfng";
        $output = $this->makeCurlCall($url, $post);
        
        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Invalid Email / Password',
                                'authChecksum' => '',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test Login API with Correct Credentials i.e. Email and Password will match
    public function test_Login_CorrectCredentials() {

        $url = "User/login";
        $post = "email=pisces.ankur@gmail.com&password=P@ssw0rd";
        $output = $this->makeCurlCall($url, $post);

        //Now, parse the output
        $outputArray = json_decode($output,true);
	unset($outputArray['showIntermediate']);
	unset($outputArray['picUrl']);
        //Check if the Output is same as expected output
        $expectedOutput = array('userId' => '2134483',
                                'firstName' => 'Ankur',
                                'lastName' => 'Gupta',
                                'displayName' => 'Ankur258451',
                                'email' => 'pisces.ankur@gmail.com',
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb1321d4bf4c8e55b90b2a0a53faacf6bfdb7c114df76e3ae6c964bca243c810a701fe8ddde719ef095dea7fabdd0f546a604cecd0bfa58e88c45dc3a05200b7ffaf2cc77b72e1d96fe0d1871a7b0d1a5007a77ed43130c8bab44fa9f45bb4cfdb4a231b7ace3aadc7fc6a4bbf83daeb4dcfed1de6809a0c926b7cde549b8d3a011958056ae934024c5a2f8c45aeb83e8b104ced14abd7228d3364f971faff5e7fd77',
                                'error' => '',
                                'notificationCount' => '0'
                                );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    public function test_ResetPassword_InvalidIdentifier() {

        $url = "User/resetPassword";
        $post = "identifier=dnfkjsfn&password=P@ssw0rd";
        $output = $this->makeCurlCall($url, $post);
        
        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '',
                                'error' => array(
                                                array(
                                                        'field' => 'email',
                                                        'errorMessage'  => 'Please enter your email.'
                                                     )
                                                ),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_ResetPassword_PasswordMissing() {

        $url = "User/resetPassword";
        $post = "identifier=ODQ3YTFiN2YwMjBiNzc4OTg5M2Y0OTFjMGVlN2JhYjh8fGh0dHA6Ly93d3cuc2hpa3NoYS5jb20vc2hpa3NoYS9pbmRleC98fHBpc2Nlcy5hbmt1ckBnbWFpbC5jb20=&password=";
        $output = $this->makeCurlCall($url, $post);
        
        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '',
                                'error' => array(
                                                array(
                                                        'field' => 'password',
                                                        'errorMessage'  => 'Please enter your password.'
                                                     )
                                                ),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_ResetPassword_CorrectCredentials() {

        $url = "User/resetPassword";
        $post = "identifier=ODQ3YTFiN2YwMjBiNzc4OTg5M2Y0OTFjMGVlN2JhYjh8fGh0dHA6Ly93d3cuc2hpa3NoYS5jb20vc2hpa3NoYS9pbmRleC98fHBpc2Nlcy5hbmt1ckBnbWFpbC5jb20=&password=P@ssw0rd";
        $output = $this->makeCurlCall($url, $post);
        
        //Now, parse the output
        $outputArray = json_decode($output,true);
	unset($outputArray['showIntermediate']);
	unset($outputArray['picUrl']);
        //Check if the Output is same as expected output
        $expectedOutput = array('userId' => '2134483',
                                'firstName' => 'Ankur',
                                'lastName' => 'Gupta',
                                'displayName' => 'Ankur258451',
                                'email' => 'pisces.ankur@gmail.com',
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb1321d4bf4c8e55b90b2a0a53faacf6bfdb7c114df76e3ae6c964bca243c810a701fe8ddde719ef095dea7fabdd0f546a604cecd0bfa58e88c45dc3a05200b7ffaf2cc77b72e1d96fe0d1871a7b0d1a5007a77ed43130c8bab44fa9f45bb4cfdb4a231b7ace3aadc7fc6a4bbf83daeb4dcfed1de6809a0c926b7cde549b8d3a011958056ae934024c5a2f8c45aeb83e8b104ced14abd7228d3364f971faff5e7fd77',
                                'error' => '',
                                'notificationCount' => '0'
                                );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_ForgotPassword_WrongEmail() {

        $url = "User/forgotPassword";
        $post = "email=pisces.ankur";
        $output = $this->makeCurlCall($url, $post);
        
        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '',
                                'error' => array(
                                                array(
                                                        'field' => 'email',
                                                        'errorMessage'  => 'Please enter correct email'
                                                     )
                                                ),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    public function test_ForgotPassword_NonExistentEmail() {

        $url = "User/forgotPassword";
        $post = "email=pisces.ankurskfjgfg134@sdfgsgg.com";
        $output = $this->makeCurlCall($url, $post);
        
        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Invalid User',
                                'authChecksum' => '',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_ForgotPassword_CorrectEmail() {

        $url = "User/forgotPassword";
        $post = "email=pisces.ankur@gmail.com";
        $output = $this->makeCurlCall($url, $post);
        
        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Forgot password mail sent successfully.',
                                'authChecksum' => '',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test Register API with incorrect Email address
    public function test_Register_WrongEmail() {

        $url = "User/register";
        $post = "email=pisces.ankur&password=P@ssw0rd&firstName=Ankur&lastName=Gupta";
        $output = $this->makeCurlCall($url, $post);
        
        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '',
                                'error' => array(
                                                array(
                                                        'field' => 'email',
                                                        'errorMessage'  => 'Please enter correct email'
                                                     )
                                                ),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test Register API with Already Registered Email address
    public function test_Register_AlreadyRegisteredEmail() {

        $url = "User/register";
        $post = "email=pisces.ankur@gmail.com&password=P@ssw0rd&firstName=Ankur&lastName=Gupta";
        $output = $this->makeCurlCall($url, $post);
        
        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '',
                                'error' => array(
                                                array(
                                                        'field' => 'email',
                                                        'errorMessage'  => 'Email is already registered.'
                                                     )
                                                ),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test Register API with Already Registered Email address
    public function test_Register_IncorrectPassword() {

        $url = "User/register";
        $post = "email=pisces.ankurasfdaf@gmail.com&password=P@s&firstName=Ankur&lastName=Gupta";
        $output = $this->makeCurlCall($url, $post);
        
        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '',
                                'error' => array(
                                                array(
                                                        'field' => 'password',
                                                        'errorMessage'  => 'The password must contain atleast 6 characters.'
                                                     )
                                                ),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test Register API with Name having Shiksha word inside it.
    public function test_Register_IncorrectName() {

        $url = "User/register";
        $post = "email=pisces.ankurasfdaf@gmail.com&password=P@ssw0rd&firstName=123shikshabc&lastName=Gupta";
        $output = $this->makeCurlCall($url, $post);
        
        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '',
                                'error' => array(
                                                array(
                                                        'field' => 'firstName',
                                                        'errorMessage'  => 'This username is not allowed.'
                                                     )
                                                ),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test Register API with Correct Credentials
    public function test_Register_CorrectCredentials() {

        $email = 'pisces.ankur'.rand(1000,1000000).'@gmail.com';
        $url = "User/register";
        $post = "email=$email&password=P@ssw0rd&firstName=Ankur&lastName=Gupta";
        $output = $this->makeCurlCall($url, $post);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        unset ($outputArray['userId']);
        unset ($outputArray['displayName']);
        unset ($outputArray['authChecksum']);
        unset ($outputArray['picUrl']);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'firstName' => 'Ankur',
                                'lastName' => 'Gupta',
                                'email' => $email,
                                'showIntermediate' => true,
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'error' => '',
                                'notificationCount' => '0'
                                );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test Insert user profile data with incorrect userId
    public function test_InsertUserProfile_WrongUserId() {

        $url = "User/insertUserProfileData";
        $post = "";
        $output = $this->makeCurlCall($url, $post);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '',
                                'error' => array(
                                                array(
                                                        'field' => 'userId',
                                                        'errorMessage'  => 'Please enter your userId.'
                                                     )
                                                ),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    //Test Insert user profile data with correct userId
    public function test_InsertUserProfile_correctUserId() {

        $url = "User/insertUserProfileData";
        $post = "";
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


    //Test Insert user profile data with expertType and organization name
    public function test_InsertUserProfile_WithExpertAndOrgName() {

        $url = "User/insertUserProfileData";
        $post = "expertType=Career Counselor&organisationName=infoedge pvt ltd";
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

    //Test Insert user profile data without organization name
    public function test_InsertUserProfile_WithoutOrgName() {

        $url = "User/insertUserProfileData";
        $post = "expertType=Others";
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

    public function test_fbLogin_RegisterSuccessful() {

        $url = "User/fbLogin";
        // $email = "romil.goel".rand(1000,1000000)."@shiksha.com";
        $email = "jeevansathitech@gmail.com";
        $fname = "Mayer";
        $lname  = "John";
        $fbpic = "https://fbcdn-profiles-a.akamaihd.net/hprofile-ak-xaf1/v/t1.0-1/c35.0.50.50/p50x50/11147581_1377431605918863_9155351237465059573_n.jpg?oh=7215b917990989f391bd85eb01fc11fb";
        $fbUserId = "1435222756806414";
        $fbAccessToken = "CAAFqA9aNIdYBACwuqeBZBPzK9kz6jFob6HSY6STTMUjhYYemo3PsMmcqv6nnxHX8dcFK4HIpDnm6ZAElXyeQGjnZB6gmnE4IeBkEh8hslZAI65wwrRkkNozjk697T156uvAR8ujcuZC5ch3DUevLZAeqWiCE8rI3N8pCBxTZCXZAAzMoxEBoKrGFl0iZBJRpLReIzZCMGdhWGO6O3g6uPiZCZBKi2bIkp63uNvRWBTzzEJmQBAZDZD";

        $post = "email=$email&firstName=$fname&lastName=$lname&facebookPicUrl=$fbpic&facebookUserId=$fbUserId&facebookAccessToken=$fbAccessToken";
        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);


        //Now, parse the output
        $outputArray = json_decode($output,true);
        
        // unset the variable response
        unset($outputArray['userId']);
        unset($outputArray['showIntermediate']);
        unset($outputArray['authChecksum']);
        

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'email' => $email,
                                'firstName' => $fname,
                                'lastName' => $lname,
                                'picUrl' => $fbpic,
                                'responseMessage' => 'Success',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_fbLogin_WrongFBAccessToken() {

        $url = "User/fbLogin";
        $email = "jeevansathitech@gmail.com";
        $fname = "Mayer";
        $lname  = "John";
        $fbpic = "https://fbcdn-profiles-a.akamaihd.net/hprofile-ak-xaf1/v/t1.0-1/c35.0.50.50/p50x50/11147581_1377431605918863_9155351237465059573_n.jpg?oh=7215b917990989f391bd85eb01fc11fb";
        $fbUserId = "1435222756806414";
        $fbAccessToken = "R8ujcuZC5ch3DUevLZAeqWiCE8rI3N8pCBxTZCXZAAzMoxEBoKrGFl0iZBJRpLReIzZCMGdhWGO6O3g6uPiZCZBKi2bIkp63uNvRWBTzzEJmQBAZDZD";

        $post = "email=$email&firstName=$fname&lastName=$lname&facebookPicUrl=$fbpic&facebookUserId=$fbUserId&facebookAccessToken=$fbAccessToken";
        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);


        //Now, parse the output
        $outputArray = json_decode($output,true);
        
        // unset the variable response
        unset($outputArray['userId']);
        unset($outputArray['showIntermediate']);
        unset($outputArray['authChecksum']);
        

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Invalid OAuth access token.',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_fbLogin_FBAccessTokenNotProvided() {

        $url = "User/fbLogin";
        $email = "jeevansathitech@gmail.com";
        $fname = "Mayer";
        $lname  = "John";
        $fbpic = "https://fbcdn-profiles-a.akamaihd.net/hprofile-ak-xaf1/v/t1.0-1/c35.0.50.50/p50x50/11147581_1377431605918863_9155351237465059573_n.jpg?oh=7215b917990989f391bd85eb01fc11fb";
        $fbUserId = "1435222756806414";
        $fbAccessToken = "";

        $post = "email=$email&firstName=$fname&lastName=$lname&facebookPicUrl=$fbpic&facebookUserId=$fbUserId&facebookAccessToken=$fbAccessToken";
        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);


        //Now, parse the output
        $outputArray = json_decode($output,true);
        
        // unset the variable response
        unset($outputArray['userId']);
        unset($outputArray['showIntermediate']);
        unset($outputArray['authChecksum']);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'error' => array(array('field' => 'facebookAccessToken', 'errorMessage' => 'Please enter your facebookAccessToken.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_fbLogin_EmailIdDidntMatch() {

        $url = "User/fbLogin";
        // $email = "romil.goel".rand(1000,1000000)."@shiksha.com";
        $email = "abc@gmail.com";
        $fname = "Mayer";
        $lname  = "John";
        $fbpic = "https://fbcdn-profiles-a.akamaihd.net/hprofile-ak-xaf1/v/t1.0-1/c35.0.50.50/p50x50/11147581_1377431605918863_9155351237465059573_n.jpg?oh=7215b917990989f391bd85eb01fc11fb";
        $fbUserId = "1435222756806414";
        $fbAccessToken = "CAAFqA9aNIdYBACwuqeBZBPzK9kz6jFob6HSY6STTMUjhYYemo3PsMmcqv6nnxHX8dcFK4HIpDnm6ZAElXyeQGjnZB6gmnE4IeBkEh8hslZAI65wwrRkkNozjk697T156uvAR8ujcuZC5ch3DUevLZAeqWiCE8rI3N8pCBxTZCXZAAzMoxEBoKrGFl0iZBJRpLReIzZCMGdhWGO6O3g6uPiZCZBKi2bIkp63uNvRWBTzzEJmQBAZDZD";

        $post = "email=$email&firstName=$fname&lastName=$lname&facebookPicUrl=$fbpic&facebookUserId=$fbUserId&facebookAccessToken=$fbAccessToken";
        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);


        //Now, parse the output
        $outputArray = json_decode($output,true);
        
        // unset the variable response
        unset($outputArray['userId']);
        unset($outputArray['showIntermediate']);
        unset($outputArray['authChecksum']);
	unset($outputArray['responseMessage']);        
error_log("4nov::::".print_r($outputArray,true));
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }

    public function test_fbLogin_FBUserIdNotGiven() {

        $url = "User/fbLogin";
        // $email = "romil.goel".rand(1000,1000000)."@shiksha.com";
        $email = "abc@gmail.com";
        $fname = "Mayer";
        $lname  = "John";
        $fbpic = "https://fbcdn-profiles-a.akamaihd.net/hprofile-ak-xaf1/v/t1.0-1/c35.0.50.50/p50x50/11147581_1377431605918863_9155351237465059573_n.jpg?oh=7215b917990989f391bd85eb01fc11fb";
        $fbUserId = "";
        $fbAccessToken = "CAAFqA9aNIdYBACwuqeBZBPzK9kz6jFob6HSY6STTMUjhYYemo3PsMmcqv6nnxHX8dcFK4HIpDnm6ZAElXyeQGjnZB6gmnE4IeBkEh8hslZAI65wwrRkkNozjk697T156uvAR8ujcuZC5ch3DUevLZAeqWiCE8rI3N8pCBxTZCXZAAzMoxEBoKrGFl0iZBJRpLReIzZCMGdhWGO6O3g6uPiZCZBKi2bIkp63uNvRWBTzzEJmQBAZDZD";

        $post = "email=$email&firstName=$fname&lastName=$lname&facebookPicUrl=$fbpic&facebookUserId=$fbUserId&facebookAccessToken=$fbAccessToken";
        $header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);


        //Now, parse the output
        $outputArray = json_decode($output,true);
        
        // unset the variable response
        unset($outputArray['userId']);
        unset($outputArray['showIntermediate']);
        unset($outputArray['authChecksum']);
        

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'error' => array(array('field' => 'facebookUserId', 'errorMessage' => 'Please enter your facebookUserId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
}

