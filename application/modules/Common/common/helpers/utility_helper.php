<?php
// for checking if the id's used are integer and greater than zero. Can be used to filter id's before calling findMultiple
// returns true for only positive integer ID's in scaler and string format both
function isValidId($Id){
        if(!filter_var($Id, FILTER_VALIDATE_INT)===false && (int)($Id)>0){
                return true;	
        }
        else{
                return false;	
        } 
}

function to_xml(SimpleXMLElement $object,array $data){
    foreach($data as $key=>$value){
        if(is_array($value)){
            $newObject = $object->addChild($key);
            to_xml($newObject,$value);
        }
        else{
            $object->addChild($key,$value);
        }
    }
}
function trimAssociativeArray(&$data){
    foreach ($data as $key=>$value){
        if(is_array($value)){
            trimAssociativeArray($value);
        }
        else{
            $data[$key] = trim($data[$key]);
        }
    }
}
function xml_to_array($xml){
//    return (array)simplexml_load_string($xml);
    $xml = utf8_encode($xml);
    $returnArray =  json_decode(json_encode((array)simplexml_load_string($xml)),1);
    if(empty($returnArray)){
        $CI = &get_instance();
        $lib = $CI->load->library('common/studyAbroadCommonLib');
        $lib->selfMailer("XML to array conversion failed for DHL", $xml);
    }
    return $returnArray;
}
function utility_mergeArrays($ids,$arrays)
{
    $merged_arrays = array();

    foreach($ids as $id)
    {
            $merged_arrays[$id] = array();
            foreach($arrays as $array)
            {
                    if(isset($array[$id]))
                    {
                            $merged_arrays[$id] = array_merge($merged_arrays[$id],$array[$id]);
                    }
            }
    }
    return $merged_arrays;
}

function utility_encodeXmlRpcResponse($response)
{
    $response = base64_encode(gzcompress(json_encode($response)));
    $response = array($response,'string');
    return $response;
}

function utility_decodeXmlRpcResponse($response)
{
    $response = json_decode(gzuncompress(base64_decode($response)),true);
    return $response;
}

function utility_import($file,$class_name='')
{
    if(!$class_name || !class_exists($class_name))
    {
            $file = str_replace('.','/',$file);
            require_once APPPATH.'/modules/'.$file.EXT;
    }
}

function utility_displayException($e,$error_log=FALSE)
{
    if($error_log)
    {
            error_log("EXCEPTION: ".$e->getMessage()." @ ".$e->getFile()." LINE ".$e->getLine());
    }
}

function _p($data)
{
    echo '<pre>'.print_r($data,TRUE).'</pre>';
}

function json_error($message)
{
    $return  = array();
    $return['status'] = 'Error';
    $return['message'] = $message;
    return json_encode($return);
}

function json_success($data)
{
    $return  = array();
    $return['status'] = 'Success';
    $return = array_merge($return,$data);
    return json_encode($return);
}

function getStandardDate($date)
{
    $dateParts = explode('/',$date);
    return $dateParts[2].'-'.$dateParts[1].'-'.$dateParts[0];
}

function getUserProfilePic($profile_pic)
{
    $image_name = end(explode('/',$profile_pic));
    $image_name_parts = explode('.',$image_name);
    
    $new_image_name_parts = array();
    for($i=0;$i<count($image_name_parts);$i++) {
            if($i == count($image_name_parts)-2) {
                    $image_name_parts[$i] = $image_name_parts[$i].'_m';	
            }
            $new_image_name_parts[] = $image_name_parts[$i];
    }
    
    $image_parts = explode('/',$profile_pic);
    
    $new_image_name = array();
    for($i=0;$i<count($image_parts);$i++) {
            if($i == count($image_parts)-1) {
                    $image_parts[$i] = implode('.',$new_image_name_parts);
            }
            $new_image_name[] = $image_parts[$i];
    }
    
    return implode('/',$new_image_name);
}

/* currently doesn't support mail attachments
* changelog: sprint : nov 8 to 21: attachment supported
$mailData['recipients'] = array(
                   'to' => array('mailid1','mailid2'...'mailidn'),
                   'cc' => array('mailid1','mailid2'...'mailidn'),
                   'bcc'=> array('mailid1','mailid2'...'mailidn')
                   );
$mailData['sender'] = 'maildId'; //SA_ADMIN_EMAIL
$mailData['subject'] = 'maildsubject';
$mailData['mailContent'] = 'mailbody';
$mailData['fileUrl'] = url of the file attached
*/
function sendMails($mailData)
{
    $to         = implode(',',$mailData['recipients']['to']);
    $subject    = $mailData['subject'];
    $body       = $mailData['mailContent'];
    $from       = $mailData['sender'];
    
    $cc="";
    if(!empty($mailData['recipients']['cc']))
    {
            $cc = implode(',',$mailData['recipients']['cc']);
    }
    $bcc="";
    if(!empty($mailData['recipients']['bcc']))
    {
            $bcc = implode(',',$mailData['recipients']['bcc']);
    }
    // prepare headers based whether attchment is available or not
    if(empty($mailData['fileUrl'])){
            // Always set content-type when sending HTML email
            $headers  = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From:'.$from . "\r\n";
            $headers .= 'Cc:'.$cc . "\r\n";
            $headers .= 'Bcc:'.$bcc . "\r\n";
            return mail($to,$subject,$body,$headers);
    }else{
            $extension = pathinfo($mailData['fileUrl'], PATHINFO_EXTENSION);
            $fileName = pathinfo($mailData['fileUrl'], PATHINFO_FILENAME);
            // get file
            //$fileData = file_get_contents($mailData['fileUrl']);
            $fileData = fileGetContentsViaCURL($mailData['fileUrl']);
            $mime = getMIMETypeByExtension($extension);
            $multipartSep = '-----'.md5(time()).'-----';
            $headers = array(
                                    "From: $from",
                                    //"Reply-To: $from",
                                    "Content-Type: multipart/mixed; boundary=\"$multipartSep\"",
                                    "Cc: ".$cc,
                                    "Bcc: ".$bcc
                            );
            $attachment = chunk_split(base64_encode($fileData)); 
            $body = "--$multipartSep\r\n"
                            . "Content-Type: text/html; charset=ISO-8859-1; format=flowed\r\n"
                            . "Content-Transfer-Encoding: 7bit\r\n"
                            . "\r\n"
                            . "$body\r\n"
                            . "--$multipartSep\r\n"
                            . "Content-Type: ".$mime."\r\n"
                            . "Content-Transfer-Encoding: base64\r\n"
                            . "Content-Disposition: attachment; filename=\"".$fileName.".".$extension."\"\r\n"
                            . "\r\n"
                            . "$attachment\r\n"
                            . "--$multipartSep--";
            $returnpath = "-f" . $from;
            return mail($to, $subject, $body, implode("\r\n", $headers),$returnpath); 
    }
}
/*
 * used to create file attachments for sendMails function in this file
 */
function fileGetContentsViaCURL($url)
{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $fileData = curl_exec($ch);
        curl_close($ch);
        return $fileData;
}
/*
* function to get mime types for a file extension
* @params: file extension
* @return: mime type string
*/
function getMIMETypeByExtension($extension)
{
    // Load the mime types
    @include(APPPATH.'config/mimes'.EXT);
    if ( ! isset($mimes[$extension]))
    {
            // not found
            $mime = 'application/octet-stream';
    }
    else
    {
            $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
    }
    return $mime;
}

/*
Function To Validate mobile No and Email address & mobile numbers
---------------------
These should be valid
---------------------
abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@letters-in-local.org   valid
01234567890@numbers-in-local.net    valid
&'*+-./=?^_{}~@other-valid-characters-in-local.net  invalid
mixed-1234-in-{+^}-local@sld.net    invalid
a@single-character-in-local.org valid
"quoted"@sld.com    invalid
"\e\s\c\a\p\e\d"@sld.com    invalid
"quoted-at-sign@sld.org"@sld.com    invalid
"escaped\"quote"@sld.com    invalid
"back\slash"@sld.com    invalid
one-character-third-level@a.example.com valid
single-character-in-sld@x.org   valid
local@dash-in-sld.com   valid
letters-in-sld@123.com  valid
one-letter-sld@x.org    valid
uncommon-tld@sld.museum valid
uncommon-tld@sld.travel valid
uncommon-tld@sld.mobi   valid
country-code-tld@sld.uk valid
country-code-tld@sld.rw valid
local@sld.newTLD    valid
punycode-numbers-in-tld@sld.xn--3e0b707e    invalid
the-total-length@of-an-entire-address-cannot-be-longer-than-two-hundred-and-fifty-four-characters-and-this-address-is-254-characters-exactly-so-it-should-be-valid-and-im-going-to-add-some-more-words-
here-to-increase-the-lenght-blah-blah-blah-blah-bla.org  valid
local@sub.domains.com   valid
bracketed-IP-instead-of-domain@[127.0.0.1]  invalid
-----------------------
These should be invalid
-----------------------
@missing-local.org  invalid
! #$%`|@invalid-characters-in-local.org invalid
(),:;`|@more-invalid-characters-in-local.org    invalid
<>@[]\`|@even-more-invalid-characters-in-local.org  invalid
.local-starts-with-dot@sld.com  valid
local-ends-with-dot.@sld.com    valid
two..consecutive-dots@sld.com   valid
partially."quoted"@sld.com  invalid
the-local-part-is-invalid-if-it-is-longer-than-sixty-four-characters@sld.net    valid
missing-sld@.com    invalid
sld-starts-with-dashsh@-sld.com valid
sld-ends-with-dash@sld-.com valid
invalid-characters-in-sld@! "#$%(),/;<>_[]`|.org    invalid
missing-dot-before-tld@com  invalid
missing-tld@sld.    invalid
the-total-length@of-an-entire-address-cannot-be-longer-than-two-hundred-and-fifty-four-characters-and-this-address-is-255-characters-exactly-so-it-should-be-invalid-and-im-going-to-add-some-more-words
-here-to-increase-the-lenght-blah-blah-blah-blah-bl.org valid
missing-at-sign.net invalid
unbracketed-IP@127.0.0.1    invalid
invalid-ip@127.0.0.1.26 valid
another-invalid-ip@127.0.0.256  valid
IP-and-port@127.0.0.1:25    invalid
+++++++++++++++++++++
Total Valid: 16/25
Total Invalid: 13/22
+++++++++++++++++++++
*/
function validateEmailMobile($what,$data,$isdCode=INDIA_ISD_CODE)
{
switch($what)
{
    // validate a phone number
    case 'mobile':
        if($isdCode == INDIA_ISD_CODE){
            $pattern = "/^([6-9]{1})([0-9]{9})$/i";
        }
        else{
            $pattern = "/([0-9]{6,20})$/i";
        }
    break;
    // validate email address
    case 'email':
        $pattern = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/i";
    break;
    default:
        return false;
    break;
}
return preg_match($pattern, $data) ? true : false;
}

function validateMobile($data,$country)
{
switch($country)
{
    // validate a phone number
    case 2:
        $pattern = "/^([6-9]{1})([0-9]{9})$/i";
    break;
    
    default:
        return true;
    break;
}
return preg_match($pattern, $data) ? true : false;
}

/**
* Indents a flat JSON string to make it more human-readable.
* @param string $json The original JSON string to process.
* @return string Indented version of the original JSON string.
*/
function indent_json($json) {
$result      = '';
$pos         = 0;
$strLen      = strlen($json);
$indentStr   = '  ';
$newLine     = "\n";
$prevChar    = '';
$outOfQuotes = true;

for ($i=0; $i<=$strLen; $i++) {

    // Grab the next character in the string.
    $char = substr($json, $i, 1);

    // Are we inside a quoted string?
    if ($char == '"' && $prevChar != '\\') {
        $outOfQuotes = !$outOfQuotes;

    // If this character is the end of an element,
    // output a new line and indent the next line.
    } else if(($char == '}' || $char == ']') && $outOfQuotes) {
        $result .= $newLine;
        $pos --;
        for ($j=0; $j<$pos; $j++) {
            $result .= $indentStr;
        }
    }

    // Add the character to the result string.
    $result .= $char;

    // If the last character was the beginning of an element,
    // output a new line and indent the next line.
    if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
        $result .= $newLine;
        if ($char == '{' || $char == '[') {
            $pos ++;
        }

        for ($j = 0; $j < $pos; $j++) {
            $result .= $indentStr;
        }
    }

    $prevChar = $char;
}

return $result;
}

/**
* Remove special characters which throws null after json_encode.
* @param string $json The original JSON string to process.
* @return string
* $array = json_decode(safeJSON_chars($iso_8859_1_data));
*/
function safeJSON_chars($data)
{
$data = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data);
$data = str_replace(array("\n","\r"),"",$data);
$data = str_replace('\\', '\\\\', $data);
$aux = str_split($data);
foreach($aux as $a) {
    $a1 = urlencode($a);
    $aa = explode("%", $a1);
    foreach($aa as $v) {
        if($v!="") {
            if(hexdec($v)>127) {
            $data = str_replace($a,"&#".hexdec($v).";",$data);
            }
        }
    }
}
return $data;
}

/**
* get HTTP code
* @param string url
* @return http status code
*/

function get_http_code($url)
{
/*
*** will add here following lines later
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_TIMEOUT, 2);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
*/
$handle = curl_init($url);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,TRUE);
$response = curl_exec($handle);
$httpCode = curl_getinfo($handle,CURLINFO_HTTP_CODE);
curl_close($handle);
return $httpCode;
}

/**
* Get Full URL
* @return string
*/

function get_full_url()
{
$ci=& get_instance();
$return = $ci->config->site_url().$ci->uri->uri_string();
if(count($_GET) > 0)
{
  $get =  array();
  foreach($_GET as $key => $val)
  {
     $get[] = $key.'='.$val;
  }
  $return .= '?'.implode('&',$get);
}
return $return;
}

/**
* Encodes a string as base64, and sanitizes it for use in a CI URI.
*
* @param string $str The string to encode
* @return string
*/
function url_base64_encode(&$str="")
{
return strtr(
        base64_encode($str),
        array(
            '+' => '.',
            '=' => '-',
            '/' => '~'
        )
    );
}

/**
* Decodes a base64 string that was encoded by ci_base64_encode.
*
* @param object $str The base64 string to decode.
* @return string
*/
function url_base64_decode(&$str="")
{
return base64_decode(strtr(
        $str,
        array(
            '.' => '+',
            '-' => '=',
            '~' => '/'
        )
    ));
}
function get_institue_salient_feature($feature_id) {
    $salient_feature = "";
    switch($feature_id) {

            case 7 :
                    $salient_feature =  'Placement Guaranteed ';
                    break;

            case 9 :
                    $salient_feature = 'Placement Assured';
                    break;

            case 8 :
                    $salient_feature = '100% Placement Record';
                    break;


            case 3 :
                    $salient_feature = 'Foreign Travel';
                    break;


            case 6 :
                    $salient_feature = 'Foreign Exchange';
                    break;


            case 16 :
                    $salient_feature = 'Free SAP Training';
                    break;

            case 17 :
                    $salient_feature = 'Free English Language Training';
                    break;

            case 18 :
                    $salient_feature = 'Free Soft Skills Training';
                    break;

            case 19 :
                    $salient_feature = 'Free Foreign Language Training';
                    break;

            case 1 :
                    $salient_feature = 'Free Laptop';
                    break;

            case 10 :
                    $salient_feature = 'PGDM+MBA';
                    break;

            case 12 :
                    $salient_feature = 'In-Campus Hostel';
                    break;

            case 14 :
                    $salient_feature = 'Transport Facility';
                    break;

            case 20 :
                    $salient_feature = 'WiFi Campus';
                    break;

            case 22 :
                    $salient_feature = 'AC Campus';
                    break;

    }
    return $salient_feature;
}


function _date_range_limit($start, $end, $adj, $a, $b, $result)
{
if ($result[$a] < $start) {
    $result[$b] -= intval(($start - $result[$a] - 1) / $adj) + 1;
    $result[$a] += $adj * intval(($start - $result[$a] - 1) / $adj + 1);
}

if ($result[$a] >= $end) {
    $result[$b] += intval($result[$a] / $adj);
    $result[$a] -= $adj * intval($result[$a] / $adj);
}

return $result;
}

function _date_range_limit_days($base, $result)
{
$days_in_month_leap = array(31, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
$days_in_month = array(31, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

_date_range_limit(1, 13, 12, "m", "y", $base);

$year = $base["y"];
$month = $base["m"];

if (!$result["invert"]) {
    while ($result["d"] < 0) {
        $month--;
        if ($month < 1) {
            $month += 12;
            $year--;
        }

        $leapyear = $year % 400 == 0 || ($year % 100 != 0 && $year % 4 == 0);
        $days = $leapyear ? $days_in_month_leap[$month] : $days_in_month[$month];

        $result["d"] += $days;
        $result["m"]--;
    }
} else {
    while ($result["d"] < 0) {
        $leapyear = $year % 400 == 0 || ($year % 100 != 0 && $year % 4 == 0);
        $days = $leapyear ? $days_in_month_leap[$month] : $days_in_month[$month];

        $result["d"] += $days;
        $result["m"]--;

        $month++;
        if ($month > 12) {
            $month -= 12;
            $year++;
        }
    }
}

return $result;
}

function _date_normalize($base, $result)
{
$result = _date_range_limit(0, 60, 60, "s", "i", $result);
$result = _date_range_limit(0, 60, 60, "i", "h", $result);
$result = _date_range_limit(0, 24, 24, "h", "d", $result);
$result = _date_range_limit(0, 12, 12, "m", "y", $result);

$result = _date_range_limit_days($base, $result);

$result = _date_range_limit(0, 12, 12, "m", "y", $result);

return $result;
}

/**
* Accepts two unix timestamps.
*/
function _date_diff($one, $two)
{
$invert = false;
if ($one > $two) {
    list($one, $two) = array($two, $one);
    $invert = true;
}

$key = array("y", "m", "d", "h", "i", "s");
$a = array_combine($key, array_map("intval", explode(" ", date("Y m d H i s", $one))));
$b = array_combine($key, array_map("intval", explode(" ", date("Y m d H i s", $two))));

$result = array();
$result["y"] = $b["y"] - $a["y"];
$result["m"] = $b["m"] - $a["m"];
$result["d"] = $b["d"] - $a["d"];
$result["invert"] = $invert ? 1 : 0;
$result["days"] = intval(abs(($one - $two)/86400));

if ($invert) {
    _date_normalize($a, $result);
} else {
    _date_normalize($b, $result);
}

return $result;
}

function getTimeDifference($date1,$date2)
{
    $result = _date_diff(strtotime($date1), strtotime($date2));
    if(is_array($result)){
            $years = $result['y'];
            $months = $result['m'];
            $months = ($result['y']*12)+$result['m'];
    }
    return array('years' => (string) $years,'months' => (string) $months);
}

function displayDataInForm($data)
{
    if($data) {
            if($data == ' ') {
                    echo '&nbsp;';
            }
            else {
                    return $data;
            }
    }
    else if($data === 0 || $data === '0') {
            return 0;
    }
    else {
            return '&nbsp;';
    }
}

function displayFormDataInBoxes($numOfBoxes,$data)
{
    $data = html_entity_decode($data);
    $displayData = '';

    for($i=0;$i<$numOfBoxes;$i++){
            if($data[$i]=='' || $data[$i]==' ')
                $displayData .= "<td>&nbsp;</td>";
            else
                $displayData .= "<td>".$data[$i]."</td>";
    }

    return $displayData;
}
/*************************************/
//Code to generate PDF from HTML Start
/*************************************/
function createPDF($userType,$userId,$formId,$cId,$loggedInUserId ){
    $loggedInUserId = encode($loggedInUserId);
    if($userType=='enterprise'){
         $url = escapeshellarg(SHIKSHA_HOME.'/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?userId='.$userId.'&formId='.$formId.'&cId='.$cId.'&viewForm=true&loggedInUserId='.$loggedInUserId.'&generate=pdf');
    }else{
         $url  = escapeshellarg(SHIKSHA_HOME.'/Online/OnlineForms/displayForm/'.$cId.'/0/'.($loggedInUserId?$loggedInUserId:'0').'/pdf/');
    }
    shell_exec("/usr/local/bin/wkhtmltopdf $url /tmp/tmp_pdf.pdf");
    $str = file_get_contents("/tmp/tmp_pdf.pdf");
    header('Content-Type: application/pdf');
    header('Content-Length: '.strlen($str));
    header('Content-Disposition: inline; filename="OnlineForm.pdf"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    ini_set('zlib.output_compression','0');
    unlink('/tmp/tmp_pdf.pdf');
    die($str);
}
/*************************************/
//Code to generate PDF from HTML End
/*************************************/

/***************************************/
//Code to Encrypt Logged-In User Id Start
/***************************************/
$skey = 'S#1k$h@__Rec0__1';

        function safe_b64encode($string)
        {
            $data = base64_encode($string);
            $data = str_replace(array('+','/','='),array('-','_',''),$data);
            return $data;
        }

        function safe_b64decode($string)
        {
            $data = str_replace(array('-','_'),array('+','/'),$string);
            $mod4 = strlen($data) % 4;
            if ($mod4)
            {
                $data .= substr('====', $mod4);
            }
            return base64_decode($data);
        }

        function encode($value)
        {
            if(!$value)
            {
                return false;
            }
            $skey = 'S#1k$h@__Rec0__1';
            $text = $value;
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);
            return trim(safe_b64encode($crypttext));
        }

        function decode($value)
        {
            if(!$value)
            {
                return false;
            }
            $skey = 'S#1k$h@__Rec0__1';
            $crypttext = safe_b64decode($value);
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
            return trim($decrypttext);
        }

/***************************************/
//Code to Encrypt Logged-In User Id End
/***************************************/


function getCatPageUrl($url,$request,$page,$region=-1){
            return SHIKSHA_HOME.'/categoryList/CategoryList/categoryPage/'.$url."-".($region!=-1?$region:$request->getRegionId())."-none-".($page?$page:1)."-".$request->isNaukrilearningPage();
}

function html_escape($var) {
    if (is_array($var)) {
            return array_map('html_escape', $var);
    } else {
            if (version_compare(PHP_VERSION, '5.2.3') >= 0) {
                    return str_replace('\\\\','\\',addslashes(htmlspecialchars($var, ENT_QUOTES, config_item('charset'), FALSE)));
                    } else {
                            return addslashes(str_replace(
                                    array('&amp;&amp;', '&amp;&lt;', '&amp;&gt;', '&amp;&quot;', '&amp;&#039;', ' '),
                                    array('&amp;', '&lt:', '&gt;', '&quot;', '&#039;','&nbsp;'),
                                    htmlspecialchars($var, ENT_QUOTES, config_item('charset')))
                            );
            }
    }
}


/*
*  Adding code for making seperate log file per day
*  API is called from sadduser in Register_Server
*/

function logToFile($id,$mobile,$parametertype,$guid,$xml,$xmlguid) 
{ 
    error_log("logToFileerror"); 

    $filename = '/data/smsoutputlogfile_'.date("j M Y ");
    $myFile = $filename.'.txt';
    
    if(!$fh = fopen($myFile, 'a')) {
        return true;
    }
    

    fwrite($fh,"==============================================================\n"); 

    if($parametertype == 'sms') 
    { 

            fwrite($fh,date("j M Y H:i:s")."mobile: ".$mobile."\n"); 
            fwrite($fh,date("j M Y H:i:s")."guid: ".$guid."\n"); 
            fwrite($fh,date("j M Y H:i:s")."smsoutputid: ".$id."\n"); 

            fwrite($fh,date("j M Y H:i:s")."xml: ".$xml."\n"); 
            fwrite($fh,date("j M Y H:i:s")."xmlguid: ".$xmlguid."\n"); 
    } 
    else if($parametertype == 'register') 
    { 
            fwrite($fh,"-----------------------------------------------------------\n"); 

            fwrite($fh,date("j M Y H:i:s")."userid: ".$id."\n"); 
            fwrite($fh,date("j M Y H:i:s")."mobile: ".$mobile."\n"); 


    } 

    fclose($fh); 

    return true; 

} 

/*
function microtime_float() { 
list($usec, $sec) = explode(" ", microtime()); 
return ((float) $usec + (float) $sec); 
} 
*/
function titleCase($string, $delimiters = array(" ", "-", "O'"), $exceptions = array("to","for", "a", "the", "of", "by", "and", "with", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X")) {
   /*
    * Exceptions in lower case are words you don't want converted
    * Exceptions all in upper case are any words you don't want converted to title case
    *   but should be converted to upper case, e.g.:
    *   king henry viii or king henry Viii should be King Henry VIII
    */
   foreach ($delimiters as $delimiter){
           $words = explode($delimiter, $string);
           $newwords = array();
           foreach ($words as $word){
                   if (in_array(strtoupper($word), $exceptions)){
                           // check exceptions list for any words that should be in upper case
                           $word = strtoupper($word);
                   } elseif (!in_array($word, $exceptions)){
                           // convert to uppercase
                           $word = ucfirst($word);
                   }
                   array_push($newwords, $word);
           }
           $string = join($delimiter, $newwords);
   }
   return $string;
} 

function js_revision($JSVERSION,$filename){
global $js_revisions;
$ret = $JSVERSION;
if(array_key_exists($filename,$js_revisions)){
    $ret .= "-".$js_revisions[$filename];
}
return $ret;        
}

/*
*  Adding code for making seperate log file per day
*  API is called from sadduser in Register_Server
*/
/*
function logToFile($id,$mobile,$parametertype,$guid,$xml,$xmlguid) 
{ 
    error_log("logToFileerror"); 

    $filename = '/tmp/smsoutputlogfile_'.date("j M Y ");
    $myFile = $filename.'.txt';
    $fh = fopen($myFile, 'a') or die("can't open file"); 


    fwrite($fh,"==============================================================\n"); 

    if($parametertype == 'sms') 
    { 

            fwrite($fh,date("j M Y H:i:s")."mobile: ".$mobile."\n"); 
            fwrite($fh,date("j M Y H:i:s")."guid: ".$guid."\n"); 
            fwrite($fh,date("j M Y H:i:s")."smsoutputid: ".$id."\n"); 

            fwrite($fh,date("j M Y H:i:s")."xml: ".$xml."\n"); 
            fwrite($fh,date("j M Y H:i:s")."xmlguid: ".$xmlguid."\n"); 
    } 
    else if($parametertype == 'register') 
    { 
            fwrite($fh,"-----------------------------------------------------------\n"); 

            fwrite($fh,date("j M Y H:i:s")."userid: ".$id."\n"); 
            fwrite($fh,date("j M Y H:i:s")."mobile: ".$mobile."\n"); 


    } 

    fclose($fh); 

    return true; 

} 
*/

function microtime_float() { 
list($usec, $sec) = explode(" ", microtime()); 
return ((float) $usec + (float) $sec); 
} 
/*
function titleCase($string, $delimiters = array(" ", "-", "O'"), $exceptions = array("to","for", "a", "the", "of", "by", "and", "with", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X")) {
   foreach ($delimiters as $delimiter){
           $words = explode($delimiter, $string);
           $newwords = array();
           foreach ($words as $word){
                   if (in_array(strtoupper($word), $exceptions)){
                           // check exceptions list for any words that should be in upper case
                           $word = strtoupper($word);
                   } elseif (!in_array($word, $exceptions)){
                           // convert to uppercase
                           $word = ucfirst($word);
                   }
                   array_push($newwords, $word);
           }
           $string = join($delimiter, $newwords);
   }
   return $string;
}
*/ 

/**
*   Functions helps to convert PHP array to JS array
*
*
*/

function format_js_str($s)
{
return '"'.addcslashes($s, "\0..\37\"\\").'"';
}

/**
*   Functions convert PHP array to JS array
*
*
*/

function php_array_to_js_array($array)
{
$temp=array();
foreach ($array as $value)
$temp[] = format_js_str($value);
return '['.implode(',', $temp).']';
}


function conversionTrackingCode()
{
    //return '<script type="text/javascript"> if (!window.mstag) mstag = {loadTag : function(){},time : (new Date()).getTime()};</script> <script id="mstag_tops" type="text/javascript" src="//flex.atdmt.com/mstag/site/8c2d7f99-8e7a-43ad-971d-cb864a32476e/mstag.js"></script> <script type="text/javascript"> mstag.loadTag("analytics", {dedup:"1",domainId:"1505745",type:"1",actionid:"55663"})</script> <noscript> <iframe src="//flex.atdmt.com/mstag/tag/8c2d7f99-8e7a-43ad-971d-cb864a32476e/analytics.html?dedup=1&domainId=1505745&type=1&actionid=55663" frameborder="0" scrolling="no" width="1" height="1" style="visibility:hidden;display:none"> </iframe> </noscript>';
    return '<img src="https://flex.atdmt.com/mstag/tag/8c2d7f99-8e7a-43ad-971d-cb864a32476e/analytics.html?dedup=1&domainId=1505745&type=1&actionid=55663" frameborder="0" scrolling="no" width="1" height="1" style="visibility:hidden;display:none">';
}

function getJSWithVersion($filename,$path='shikshaDesktop')
{
	if(!in_array($path, array('abroadMobile1','abroadMobileVendor1'))){
    	if(!DEBUG_ON){
            ensureFilledVersionArray($path,'js');
            global $jsFilesVersionArray;
            foreach ($jsFilesVersionArray[$path] as $key => $value) {
                if($value['originalPath'] == $filename.".js"){
                    if($path == 'pwa_mobile')
                    {
                        if(ENVIRONMENT == 'production')
                            return $value['versionedPath'];
                        else
                            return $filename.'.js';
                    }
                    else
                        return "build/".$value['versionedPath'];
                }
            }
        }else{
            return $filename.'.js';
        }
    }else{      
        global $jsToBeExcluded;
        global $js_revisions;
        
        $jsVersionDate = '';
        $jsVersionIncremental = '';
        
        if(!in_array($filename,$jsToBeExcluded)) {
         $jsVersionDate = JSVERSION;
        }
        
        if(isset($js_revisions[$filename])) {
         $jsVersionIncremental = $js_revisions[$filename];
        }
        
        return $filename.$jsVersionDate.$jsVersionIncremental.'.js';
    }
}

function getCSSWithVersion($filename,$path = 'shikshaDesktop')
{
    if(!DEBUG_ON){
        ensureFilledVersionArray($path,'css');
        global $cssFilesVersionArray;
        foreach ($cssFilesVersionArray[$path] as $key => $value) {
            if($value['originalPath'] == $filename.".min.css"){
                return "build/".$value['versionedPath'];
            }
        }
    }
    else{
        return $filename.'.css';
    }
    // include FCPATH."globalconfig/css_revisions.php";
    
    // $cssVersionDate = CSSVERSION;
    // $cssVersionIncremental = '';
    
    // if(isset($css_revisions[$filename])) {
    //         $cssVersionIncremental = $css_revisions[$filename];
    // }
    
    // return $filename.$cssVersionDate.$cssVersionIncremental.'.css';
}

function ensureFilledVersionArray($path,$type = 'js'){
    global $jsFilesVersionArray;
    global $cssFilesVersionArray;
    if($type == 'js'){
        if(empty($jsFilesVersionArray[$path])){
            if($path == 'nationalMobileVendor'){
                $jsFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/mobile5_vendor_vm_js.js'),true);
            }else if($path == 'nationalMobileBoomerang'){
                $jsFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/mobile5_vendor_boomerang_vm_js.js'),true);
            }else if($path == 'abroadMobile'){
                $jsFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/sa_mobile_vm_js.js'),true);
            }else if($path == 'abroadMobileVendor'){
                $jsFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/mobileSA_vendor_vm_js.js'),true);
            }else if($path == 'nationalMIS'){
                $jsFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/trackingMIS_vm_js.js'),true);
            }else if($path == 'nationalMobile'){
                $jsFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/nat_mobile_vm_js.js'),true);
            }else if($path == 'pwa_mobile'){
                $jsFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/pwa/mappings/pwa_mobile_vm_js.js'),true);
            }else if($path == 'responsiveAssets'){
                $jsFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/responsive_assets_vm_js.js'),true);
            }else{
                $jsFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/desk_vm_js.js'),true);
            }
        }
    }
    else if($type == 'css'){
        if(empty($cssFilesVersionArray[$path])){
            if($path == 'nationalMobileVendor'){
                $cssFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/mobile5_vendor_vm_css.js'),true);
            }else if($path == 'abroadMobile'){
                $cssFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/sa_mobile_vm_css.js'),true);
            }else if($path == 'nationalMIS'){
                $cssFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/trackingMIS_vm_css.js'),true);
            }else if($path == 'nationalMobile'){
                $cssFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/nat_mobile_vm_css.js'),true);
            }else if($path == 'responsiveAssets'){
                $cssFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/responsive_assets_vm_css.js'),true);
            }else{
                $cssFilesVersionArray[$path] = json_decode(file_get_contents(FCPATH.'/public/mappings/desk_vm_css.js'),true);
            }
        }
    }
}

function addJSVariables(){
global $js_revisions;
echo "var JSURL = '".JSURL."', "; 
echo "JSVERSION = '".JSVERSION."', "; 
echo "JSREVISIONS = ".json_encode($js_revisions).";\n"; 
}

function addHtmlVersionVariables(){
require_once FCPATH."globalconfig/html_revisions.php";

global $html_revisions;
echo "var HTMLVERSION = '".HTMLVERSION."';\n";
echo "var HTMLREVISIONS = ".json_encode($html_revisions).";\n";
}

function getLocationsCityWise($locations){
    $returnArray = array();
    foreach($locations as $location){
            $city = $location->getCity();
            $locality = $location->getLocality();
            $localityId = $locality?$locality->getId():0;
            if($localityId){
                    $localityName = $locality->getName();
            }else{
                    $localityId = 0;
                    $localityName = "All Localities";
            }
            $returnArray[$city->getId()]['name'] = $city->getName();
            $returnArray[$city->getId()]['localities'][$localityId]['name'] = $localityName;
    }
    return $returnArray;
}

function getCurrentPageURL()
{
    $pageURL = 'http';
    $ports = array(80);
    if ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") || ((isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https"))) {$pageURL .= "s";$ports[] = 443;}
    $pageURL .= "://";
    if (!in_array($_SERVER["SERVER_PORT"],$ports)) {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }

    $CI      = & get_instance(); 
    $pageURL = $CI->security->xss_clean($pageURL); 
    
    return $pageURL;
}

function getCurrentPageURLWithoutQueryParams(){
    $pageURL = 'http';
    $ports = array(80);
    if ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") || ((isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https"))) {$pageURL .= "s";$ports[] = 443;}
    $pageURL .= "://";
    if (!in_array($_SERVER["SERVER_PORT"],$ports)) {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_URL"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_URL"];
    }

    $CI      = & get_instance(); 
    $pageURL = $CI->security->xss_clean($pageURL); 
    
    return $pageURL;
}

function getPlural($count, $str, $appendCount = true){
    $returnString = $str;
    if($count == 1){
            $returnString = $str;
    } else {
            $returnString = $str . 's';
    }
    if($appendCount){
            $returnString = $count . ' ' . $returnString;
    }
    return $returnString;
}

function getUserProfileLink($userDisplayName){
    return "/getUserProfile/" . $userDisplayName;
}

function getImageUrlBySize($imageUrl, $size = "big"){
    $imageData = explode("_", $imageUrl);
    $imageFinalPath = $imageUrl;
    $flag = false;
    if(count($imageData) == 2){
            $imagePrefixPath = $imageData[0];
            $imageSuffixData = explode(".", $imageData[1]);
            $imageSuffixPath = $imageSuffixData[1];
            $flag = true;
    } else {
            $index = strrpos($imageUrl, ".");
            if($index < strlen($imageUrl)){
                    $imagePrefixPath = substr($imageUrl, 0, $index);
                    $imageType = substr($imageUrl, $index+1);
                    $imageSuffixPath = $imageType;
                    $flag = true;
            }
    }
    if($flag){
            switch($size){
                    case 'big':
                            $imageFinalPath =  $imagePrefixPath . "_b." . $imageSuffixPath;
                            break;
                    
                    case 'small':
                            $imageFinalPath =  $imagePrefixPath . "_s." . $imageSuffixPath;
                            break;
                    
                    case 'medium':
                            $imageFinalPath =  $imagePrefixPath . "_m." . $imageSuffixPath;
                            break;
                    default:
                            $imageFinalPath =  $imagePrefixPath . "_".$size."." . $imageSuffixPath;
                            break;
            }		
    }
    return $imageFinalPath;
}

function changeUrlParamValue($url, $paramName, $newParamValue){
    $currentURL = $url;
    $explode = explode("?", $currentURL);
    $queryString = "";
    if(count($explode) > 1){
            $queryString = $explode[1];
    }
    $positionOfParam = strpos($queryString, $paramName);
if($positionOfParam !== false){
            $index = strrpos($currentURL, $paramName);
    $index = $index + strlen($paramName);
    $index = $index + 1; //+1 to take care of = sign
            $prefixStr = substr($currentURL, 0, $index);
            $ampersandIndex = strpos($currentURL, "&", $index);
            if($ampersandIndex == -1 || $ampersandIndex == ""){
        $ampersandIndex = strlen($currentURL);
            }
    $suffixStr = substr($currentURL, $ampersandIndex+1);
    $currentURL = trim($prefixStr) . trim($newParamValue) . "&" . trim($suffixStr);
} else {
    $currentURL = $currentURL . "&" . $paramName . "=" . urlencode(trim($newParamValue)) . "&";
}
    return $currentURL;
}

function changeUrlParamAndEmptyParamList($url, $paramName, $newParamValue, $removeList = array()){
    if(is_array($removeList) && !empty($removeList)){
            foreach($removeList as $param){
                    $url = changeUrlParamValue($url, $param, "");
            }
    }
    $url = changeUrlParamValue($url, $paramName, $newParamValue);
    return $url;
}

function makeSpaceSeparated($value, $separator = "-"){
    $valueArr = explode($separator, $value);
    $value = implode(" ", $valueArr);
    return ucwords($value);
}


function convertArrayToQueryString($params = array()){
    $validParams = array(
                                            'keyword',
                                            'start',
                                            'institute_rows',
                                            'content_rows',
                                            'country_id',
                                            'city_id',
                                            'zone_id',
                                            'locality_id',
                                            'course_level',
                                            'course_type',
                                            'min_duration',
                                            'max_duration',
                                            'search_type',
                                            'search_data_type',
                                            'sort_type',
                                            'utm_campaign',
                                            'utm_medium',
                                            'utm_source',
                                            'from_page',
                                            'autosuggestor_suggestion_shown',
                                            'tsr',
                                            'search_unique_insert_id',
                                            'show_featured_results',
                                            'show_sponsored_results',
                                            'show_banner_results',
                                            'ignore_institute_ids',
                                    'cpgs_param'
                                            );
    $queryString = "";
    if(!empty($params)){
            foreach($params as $key => $value){
                    if(in_array($key, $validParams)){
                            $queryString .= $key .'='. urlencode(trim($value)) . '&';
                    }
            }
    }
    return $queryString;
}

/**
* [API that return false if ping url timeout occoured]
* @return float Actual time taken to ping the server, FALSE if timeout or HTTP error status occurs
* var_dump(ping("http://lfvsfcp10203.dn.net:8983/solr/admin/ping")); 
* http://lfvsfcp10203.dn.net:8983/solr/admin/ping
*
*/

if (! function_exists('ping_url'))
{
function ping_url($pingUrl,$timeout = 15)
{
    header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");                                    // HTTP/1.0
    $start = microtime(true);
    
    // when using timeout in context and file_get_contents
    // it seems to take twice the timout value
    $timeout = (float) $timeout / 2;

    if ($timeout <= 0.0)
    {
        $timeout = -1;
    }
    
    $context = stream_context_create(
        array(
            'http' => array(
                'method' => 'HEAD',
                'timeout' => $timeout
            )
        )
    );
    
    // attempt a HEAD request to the ping page
    $ping = @file_get_contents($pingUrl, false, $context);
    
    // result is false if there was a timeout
    // or if the HTTP status was not 200
    if ($ping !== false)
    {
        return microtime(true) - $start;
    }
    else
    {
        return false;
    }
       
}
}

if (! function_exists('isCaptchaFreeReferer'))
{
function isCaptchaFreeReferer($refererUrl)
{
            if(strpos($refererUrl,"http://googleads.g.doubleclick.net") === 0 || strpos($refererUrl,"http://www.google.co") === 0 || strpos($refererUrl,"http://www.googleadservices.com") === 0) {
                    return TRUE;
            }
            return FALSE;
    }
}



function valid_url($str){
       $pattern = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
            if (!preg_match($pattern, $str))
            {
                    return FALSE;
            }

            return TRUE;
}

function datediff($d1,$d2)
{
    if ($d1 < $d2){
            $temp = $d2;
            $d2 = $d1;
            $d1 = $temp;
    }
    else {
            $temp = $d1; //temp can be used for day count if required
    }
    $d1 = date_parse($d1);
    $d2 = date_parse($d2);
    //days
    if ($d1['day'] >= $d2['day']){
            $diff['day'] = $d1['day'] - $d2['day'];
    }
    else {
            $d1['month']--;
            $diff['day'] = date("t",$temp)-$d2['day']+$d1['day'];
    }
    //months
    if ($d1['month'] >= $d2['month']){
            $diff['month'] = $d1['month'] - $d2['month'];
    }
    else {
            $d1['year']--;
            $diff['month'] = 12-$d2['month']+$d1['month'];
    }
    //years
    $diff['year'] = $d1['year'] - $d2['year'];
    
     if($diff['year']>0)
    {
            return $diff['year']." year";
    }
    else if($diff['month']>0)
    {
            return $diff['month']." month";
    }
    else
    {
            return $diff['day'];
    }
}


function formatArticleTitle($content, $charToDisplay) {
if(strlen($content) <= $charToDisplay)
    return($content);
else
    return (preg_replace('/\s+?(\S+)?$/', '', substr($content, 0, $charToDisplay))."...") ;
}

function limitTextLength($content, $charToDisplay){
 if(strlen($content) > $charToDisplay){
    return (substr($content, 0, $charToDisplay).'..');
}else{
    return $content;
}
}

function generateRandomAlphanumericGUID($length = 15){
    $characters = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $max = strlen($characters) - 1;
    $guid = '';
    for ($i = 0; $i < $length; $i++) {
            $guid .= $characters[mt_rand(0, $max)];
    }
    return $guid;
}

/*
function to truncate string
*/
//param string $text String to truncate.
//param integer $length Length of returned string, including ellipsis.
//param string $ending Ending to be appended to the trimmed string.
//param boolean $exact If false, $text will not be cut mid-word
//param boolean $considerHtml If true, HTML tags would be handled correctly
//return string Trimmed string.

function truncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
    if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                    return $text;
            }
            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $total_length = strlen($ending);
            $open_tags = array();
            $truncate = '';
            foreach ($lines as $line_matchings) {
                    // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                    if (!empty($line_matchings[1])) {
                            // if it's an "empty element" with or without xhtml-conform closing slash
                            if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                                    // do nothing
                            // if tag is a closing tag
                            } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                                    // delete tag from $open_tags list
                                    $pos = array_search($tag_matchings[1], $open_tags);
                                    if ($pos !== false) {
                                    unset($open_tags[$pos]);
                                    }
                            // if tag is an opening tag
                            } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                                    // add tag to the beginning of $open_tags list
                                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                            }
                            // add html-tag to $truncate'd text
                            $truncate .= $line_matchings[1];
                    }
                    // calculate the length of the plain text part of the line; handle entities as one character
                    $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                    if ($total_length+$content_length> $length) {
                            // the number of characters which are left
                            $left = $length - $total_length;
                            $entities_length = 0;
                            // search for html entities
                            if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                                    // calculate the real length of all entities in the legal range
                                    foreach ($entities[0] as $entity) {
                                            if ($entity[1]+1-$entities_length <= $left) {
                                                    $left--;
                                                    $entities_length += strlen($entity[0]);
                                            } else {
                                                    // no more characters left
                                                    break;
                                            }
                                    }
                            }
                            $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                            // maximum lenght is reached, so get off the loop
                            break;
                    } else {
                            $truncate .= $line_matchings[2];
                            $total_length += $content_length;
                    }
                    // if the maximum length is reached, get off the loop
                    if($total_length>= $length) {
                            break;
                    }
            }
    } else {
            if (strlen($text) <= $length) {
                    return $text;
            } else {
                    $truncate = substr($text, 0, $length - strlen($ending));
            }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = strrpos($truncate, ' ');
            if (isset($spacepos)) {
                    // ...and cut the text in this position
                    $truncate = substr($truncate, 0, $spacepos);
            }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                    $truncate .= '</' . $tag . '>';
            }
    }
    return $truncate;
}

function largeImageUrl($imageUrl){
    $newUrl = preg_replace('/_m(\.gif|\.jpg|\.png|\.jpeg)/', '$1', $imageUrl);
    return $newUrl;
}


function verifyCaptcha($securityCodeVar,$value,$reset = 0){
    $CI = & get_instance();
    $CI->load->library('common/captcha/Captcha');
    $captcha = new \Captcha();
    if($captcha->verify($securityCodeVar,$value,$reset)) {
            return TRUE;
    }else{
            return FALSE;
    }
}

function sessionId(){
    $CI = & get_instance();
    $CI->load->library('session');
    return $CI->session->userdata('session_id');
}

function getVisitorId()
{
    global $uniqueVisitorId;
    return $uniqueVisitorId;
}

function getVisitorSessionId()
{
    global $visitSessionId;
    return $visitSessionId;
}

if (! function_exists('storeTempUserData'))
{
function storeTempUserData($key,$value,$expire = 0)
{
            $expire = intval($expire);
            $expireTime = $expire > 0 ? time() + $expire : 0;
            setcookie($key,$value,$expireTime,'/',COOKIEDOMAIN);
    }
}

if (! function_exists('deleteTempUserData'))
{
function deleteTempUserData($key)
{
            unset($_COOKIE[$key]);
            setcookie($key,'',time()-3600,'/',COOKIEDOMAIN);
    }
}

if (! function_exists('getTempUserData'))
{
function getTempUserData($key)
{
            return $_COOKIE[$key];
    }
}

function doesExamScoreSatisfiesScoreRange($examName, $examScore, $mbaExamList, $engineeringExamList, $filterExams) {
    $satisfies = FALSE;
    $customExamsList = array('CMAT', 'GMAT');
    if(in_array($examName, $mbaExamList)){
            $userSelectedFilterValue = $filterExams[$examName];
            if(isset($userSelectedFilterValue) && $userSelectedFilterValue == 0 && !in_array($examName, $customExamsList)){
                    $satisfies = TRUE;
                    return $satisfies;
            }
            if(in_array($examName, $customExamsList)){
                    $explode = explode("-", $userSelectedFilterValue);
                    $max = false;
                    if(count($explode) > 1){
                            $min = $explode[0];
                            $max = $explode[1];
                    } else {
                            $min = $explode[0];
                    }
                    if($min == 0 && !empty($max)){
                            $min = 1;
                    } else if($min == 0 && empty($max)){
                            $max = 10000000000000; //infinity
                    } else if($min > 0 && empty($max)) {
                            $max = 10000000000000; //infinity
                    }
                    if( ($examScore >= $min && $examScore <= $max) || empty($examScore)){
                            $satisfies = TRUE;
                            return $satisfies;
                    }
            } else {
                    if($examName == "MAT") {
                            if($userSelectedFilterValue == 54) {
                                    if(0 < $examScore && $examScore <= $userSelectedFilterValue) {
                                            $satisfies = TRUE;
                                            return $satisfies;
                                    }
                            } else if($userSelectedFilterValue == 75) {
                                    if($userSelectedFilterValue < $examScore && $examScore <= 100) {
                                            $satisfies = TRUE;
                                            return $satisfies;
                                    }
                            } else {
                                    if(($userSelectedFilterValue - 5) <= $examScore && $examScore <= ( $userSelectedFilterValue + 5)) {
                                            $satisfies = TRUE;
                                            return $satisfies;
                                    }
                            }
                    } else if($examName == "XAT" || $examName == "NMAT") {
                            if($userSelectedFilterValue == 54) {
                                    if(0 < $examScore && $examScore <= $userSelectedFilterValue) {
                                            $satisfies = TRUE;
                                            return $satisfies;
                                    }
                            } else if($userSelectedFilterValue == 85) {
                                    if($userSelectedFilterValue < $examScore && $examScore <= 100) {
                                            $satisfies = TRUE;
                                            return $satisfies;
                                    }
                            } else {
                                    if(($userSelectedFilterValue - 5) <= $examScore && $examScore <= ( $userSelectedFilterValue + 5)) {
                                            $satisfies = TRUE;
                                            return $satisfies;
                                    }
                            }
                    } else {
                            if($userSelectedFilterValue == 54) {
                                    if(0 < $examScore && $examScore <= $userSelectedFilterValue) {
                                            $satisfies = TRUE;
                                            return $satisfies;
                                    }
                            } else if($userSelectedFilterValue == 95) {
                                    if($userSelectedFilterValue <= $examScore && $examScore <= ($userSelectedFilterValue + 5)) {
                                            $satisfies = TRUE;
                                            return $satisfies;
                                    }
                            } else {
                                    if(($userSelectedFilterValue - 5) <= $examScore && $examScore <= ( $userSelectedFilterValue + 5)) {
                                            $satisfies = TRUE;
                                            return $satisfies;
                                    }
                            }
                    }
            }
    } else if(in_array($examName, $engineeringExamList)){
            $userSelectedFilterValue = $filterExams[$examName];
            if(isset($userSelectedFilterValue) && $userSelectedFilterValue == 0){
                    $satisfies = TRUE;
                    return $satisfies;
            }
            if(in_array($examName, $GLOBALS['ENGINEERING_EXAMS_REQUIRED_SCORES'])){
                    if($userSelectedFilterValue >= $examScore){
                            $satisfies = TRUE;
                            return $satisfies;
                    }
                    if($examScore == 0){ //special condition: if examscore is empty, consider it.
                            $satisfies = TRUE;
                            return $satisfies;
                    }
            } else {
                    if($userSelectedFilterValue > 0 &&  $userSelectedFilterValue <= $examScore){
                            $satisfies = TRUE;
                            return $satisfies;
                    }
                    if($examScore == 0){ //special condition: if examscore is empty, consider it.
                            $satisfies = TRUE;
                            return $satisfies;
                    }
            }
    }
    return $satisfies;
}

function doesExamIncludedInSorters($examName, $sorters) {
    $satisfies = FALSE;
    if(array_key_exists('sortBy', $sorters)) {
            $params = $sorters['params'];
            if(!empty($params)){
                    $sorterExamName = $params['exam'];
                    if($sorterExamName == $examName){
                            $satisfies = TRUE;
                    }
            }
    }
    return $satisfies;
}

function isExamMarksTypeValid($subCategoryId, $examName = FALSE, $examMarksType = FALSE){
    $marksType = FALSE; 
    $engineering_exams_exception_list = $GLOBALS['ENGINEERING_EXAMS_REQUIRED_SCORES'];
    $mba_exams_exception_list 		  = $GLOBALS['MBA_EXAMS_REQUIRED_SCORES'];
    //Assumptions is that exams are coming here are MBA or engineering based only
    if($subCategoryId == 23) {
            if(in_array($examName, $mba_exams_exception_list)){
                    if($examMarksType == "total_marks"){
                            $marksType = TRUE;
                    }
            } else {
                    if($examMarksType == "percentile"){
                            $marksType = TRUE;
                    }
            }
    } else if($subCategoryId == 56){
            if(in_array($examName, $engineering_exams_exception_list)){
                    if($examMarksType == "total_marks"){
                            $marksType = TRUE;
                    }
            } else {
                    if($examMarksType == "rank"){
                            $marksType = TRUE;
                    }
            }
    } else {
            $marksType = TRUE; 
    }
    return $marksType;
}

function maxValueForExam($examName = NULL) {
    $maxValue = FALSE;
    if(empty($examName)){
            return $maxValue;
    }
    $examRange = array();
    if($examName == "MAT"){
            global $MBA_PERCENTILE_RANGE_MAT;
            $examRange = $MBA_PERCENTILE_RANGE_MAT;
    } else if($examName == "XAT"){
            global $MBA_PERCENTILE_RANGE_XAT;
            $examRange = $MBA_PERCENTILE_RANGE_XAT;
    } else if($examName == "NMAT"){
            global $MBA_PERCENTILE_RANGE_NMAT;
            $examRange = $MBA_PERCENTILE_RANGE_NMAT;
    }
    if(!empty($examRange)){
            $size = count($examRange);
            $keys = array_keys($examRange);
            $lastKey = $keys[$size-1];
            $maxValue = $examRange[$lastKey];
    }
    return $maxValue;
}

/*
*this function
* creates an ordinal suffix that can be appended to a rank type number
* e.g. 1'st', 2'nd', 3'rd'....
*/
function ordinal($num) {
$ones = $num % 10;
$tens = floor($num / 10) % 10;
if ($tens == 1) {
    $suff = "th";
} else {
    switch ($ones) {
        case 1 : $suff = "st"; break;
        case 2 : $suff = "nd"; break;
        case 3 : $suff = "rd"; break;
        default : $suff = "th";
    }
}
return $suff;
}
/* checks if char is vowel*/
function checkifvowel($string) {
    $v = strtolower($string[0]);
    if ($v == "a" || $v == "e" || $v == "i" || $v == "o" || $v == "u"){
        return "an";
    }
    else{
        return "a";
    }
}
/*
*this function
* converts a number into text formatted as in indey currency format
* e.g. 1234 -> 1,234 
*/
function moneyFormatIndia($num){
    $num = ltrim($num,'0');
$explrestunits = "" ;
if(strlen($num)>3){
    $lastthree = substr($num, strlen($num)-3, strlen($num));
    $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
    $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
    $expunit = str_split($restunits, 2);
    for($i=0; $i<sizeof($expunit); $i++){
        // creates each of the 2's group and adds a comma to the end
        if($i==0)
        {
            $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
        }else{
            $explrestunits .= $expunit[$i].",";
        }
    }
    $thecash = $explrestunits.$lastthree;
} else {
    $thecash = $num;
}
return $thecash; // writes the final format where $currency is the currency symbol.
}
/*escape string using mysql_real_escape_string*/
function escapeMyString($variable){
    if(mysql_real_escape_string($variable))
            return mysql_real_escape_string($variable);
    else
            return mysql_escape_string($variable);
}

function isMobileRequest()
{
    global $flag_mobile_user_agent;
    $ignoreMR = ignoreMobileRequest();
    if(($_COOKIE['ci_mobile'] == 'mobile' || $flag_mobile_user_agent == 'mobile') && !$ignoreMR) {
            return TRUE;
    }else {
            return FALSE;
    }
}
// this function is used to open desktop page on mobile device for online form, course homepage, career page
function ignoreMobileRequest(){
    $status = false;
    $pages  = array('-application-form-','/careers/');
    $url    = strtolower($_SERVER['HTTP_REFERER']);
    $careerUrl = SHIKSHA_HOME."/careers";
    foreach ($pages as $key => $findString) {
        if(!empty($url) && (strpos($url, $findString) || $url == $careerUrl)){
            $status = true;
            break;
        }
    }
    return $status;
}

/*
* function to get size of a remote file
* params: url of remote file, a flag that will return size with formatting if set to true
* returnValue : string of size of remote file followed by unit (different units [MB,KB,B])
* dependency : formatFileSize for conversion to size in different units [MB,KB,B]
*/
function getRemoteFileSize($url = "", $formattingRequired = true)
{
// Assume failure (if url doesn't exist, returns -1)
$result = -1;

if((ENVIRONMENT == "production" || ENVIRONMENT == "beta") && strpos($url, "https://images.shiksha.com/") !== false) {
	$url = str_replace("https://images.shiksha.com/" , MEDIADATA_INTERNAL_DOMAIN, $url);
}

$curl = curl_init( $url );

// Issue a HEAD request and follow any redirects.
curl_setopt( $curl, CURLOPT_NOBODY, true );
curl_setopt( $curl, CURLOPT_HEADER, true );
curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
//curl_setopt( $curl, CURLOPT_USERAGENT, get_user_agent_string() );
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);       
$data = curl_exec( $curl );
curl_close( $curl );

if( $data ) {
  $content_length = "unknown";
  $status = "unknown";

  if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
    $status = (int)$matches[1];
  }

  if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
    $content_length = (int)$matches[1];
  }

  if( $status == 200 || ($status > 300 && $status <= 308) ) {
    $result = $content_length;
  }
  
  //if formatting is required
  if($formattingRequired){
    // format size (in B/KB/MB)
    $result = formatFileSize($result);
  }
}

// if formatting is not required, size is returned as it is
return $result;
}
/*
* function to format size of a file
* input: size in bytes
* string of size followed by unit (different units [MB,KB,B])
*/
function formatFileSize($size)
{
    if($size > 0)
    {
        if($size<1024){ 
            return $size." B";
        }
        else if($size>1024 && round(($size/1024),1)<1024){ 
            return round(($size/1024),1)." KB";
        }
        else if(round(($size/1024),1)>1024){
            return round((($size/1024)/1024),1)." MB";
        }
    }
    else
    {
            return "0 B";
    }
}

/**
* Function to get the session identifier for the current request (used in case of brochure download from mail[abroad])
**/	
function getNewSessionId()
{
// start the session
session_start();

// get the session id
$id =  session_id();

//destroy the session data
session_destroy();

return $id;
}

function removeSpacesFromString($str) {
    return str_replace(' ', '', $str);
}
/*
* This function checks if current time is falling in working hours or not
* working hours are monday to friday , 9 am to 6:30 pm
* (required before sending mails to RMS counsellor before sending an sms, so that they do not get sms at )
* params (all optional): takes a custom datetime string instead of taking the current datetime string,
* 				  start hour & minute value
* 				  end hour & minute value
* return value: true if currently in working hours , false otherwise 
*/
function checkIfCurrentTimeIsInWorkingHours($customDate = "" , $startHour = 9, $startMinute = 0, $endHour = 18, $endMinute = 30)
{
    $flag = true; // assume we are in working hours
    if($customDate == "")
    {
            $customDate = date("Y-m-d H:i:s");
    }
    $dateObj = date_create_from_format("Y-m-d H:i:s",$customDate);
    $day = date_format($dateObj,'N');
    $hour = date_format($dateObj,'G');
    $minute = date_format($dateObj,'i');
    //echo "<br>day".$day;
    //echo "<br>hour".$hour;
    //echo "<br>minute".$minute;
    //$hour = 18;
    //$minute = 30;
    // monday(1) to friday(5) & 9 am to 6:30
    if($day >= 1 && $day <= 5 && $hour >= $startHour && $hour <= $endHour )
    {
            if($hour == $endHour && $minute >$endMinute ) // hour is 18 (6 pm) & minutes above 30 i.e. 6:30 pm
            {
                    $flag = false;
            }
    }
    else{
            $flag = false;
    }
    $data = array('flag'=>$flag);
    return $data;

}

/*
* checks if user is on mobile site or not
* returns :
* 1. 'abroad'/'national' based on which mobile site is being viewed by the user
* 2. false if user is on desktop
*/
function isMobileSite()
{
    if(
       ($_COOKIE['user_force_cookie'] != 'YES') && // user hasn't forced desktop site
       ($_COOKIE['ci_mobile'] == 'mobile') 
      )
    {
            return $_COOKIE['mobile_site_user'];
    }
    else
    {
            return false;
    }
}


function logPerformanceData($startTime) {
if(LOG_PERFORMANCE_DATA) {
$traceData   = debug_backtrace();
$functionDetail = $traceData[1]['class']." : ".$traceData[1]['function']." : ".$traceData[0]['line'];  
error_log("\narray( section => '".$functionDetail."', PeakMemoryUsage => '".memory_get_peak_usage(TRUE)."', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,LOG_PERFORMANCE_DATA_FILE_NAME);
unset($traceData);
}
}

function getLogTimeMemStr($startTime = 0, $startMemory = 0) {
$timeEnd = microtime_float();
$time = $timeEnd - $startTime;
$timeStr = "Time taken: ".round(($time*1000), 4)." ms"; //in ms

$memUsed  = round(((memory_get_usage() - $startMemory)/1024/1024), 4);
$memAllocated  = memory_get_peak_usage(true)/1024/1024;
$memStr = "Memory used: ".$memUsed." MB | Memory limit (allocated): ".$memAllocated." MB"; //in MB

return $timeStr." | ".$memStr;
}

function resizeImage($imgUrl,$size){
    $replaceString = "_".$size.".";
    if(strpos($imgUrl, "_s.") != FALSE){
            return str_replace("_s.", $replaceString, $imgUrl);
    }elseif(strpos($imgUrl,"_m.") != FALSE){
            return str_replace("_m.", $replaceString, $imgUrl);
    }elseif(strpos($imgUrl,"_") == FALSE){
            return str_replace(".", $replaceString, $imgUrl);
    }else{
            return $imgUrl;
    }
}
/*
* function to run process in background (non-blocking)
* @param: $command to be executed in shell, optional log-file to keep the output from the command execution into.
*/
function runProcessInBackground($command, $logFile = "")
{
    if($logFile == "")
    {
            $logFile = "/tmp/tempExec.txt";
    }
    exec($command." >> ".$logFile." &");
}

/*
* Function encode's data to SHA-256 hash
* @params : $data to be encoded
*/
function sha256($data){
return hash('sha256', passwordSalt.$data);
}

function getBeaconTrackingURL($pageIdentifier, $pageEntityId = 0, $extraData = array())
{
    $rand = rand(1000000,9999999);
    $referer = urlencode($_SERVER['HTTP_REFERER']);
    $pageURL = urlencode(getCurrentPageURL());
    $extraData = urlencode(json_encode($extraData));
    
    $url = BEACON_TRACK_URL.'/'.$rand.'/'.$pageIdentifier.'/'.$pageEntityId.'?pageURL='.$pageURL.'&pageReferer='.$referer.'&extraData='.$extraData;
    return $url;
}

function getBeaconURL($beaconTrackData, $ampViewFlag= false)
{
    $rand = rand(1000000,9999999);
    $pageEntityId   = $beaconTrackData['pageEntityId'];
    $pageIdentifier = $beaconTrackData['pageIdentifier'];
    $productId      = $beaconTrackData['productId'];
    $url = BEACON_URL.'/'.$rand.'/'.$productId.'/'.$pageEntityId.'+'.$pageIdentifier;
    
    if($ampViewFlag){
        return $url;
    }else{
        echo "<img id='beacon_index_img' src='$url' width='1' height='1' style='display:none;' />";
    }
    return $url;
}


//$ampVuewFlag parameter is used for AMP Pages
function loadBeaconTracker($beaconTrackData,$ampViewFlag= false)
{
    $pageIdentifier = 'Unidentified';
    $pageEntityId = 0;
    $extraData = array();
    //if(is_array($beaconTrackData) && count($beaconTrackData) > 0) {
            $pageIdentifier = $beaconTrackData['pageIdentifier'];
            $pageEntityId = $beaconTrackData['pageEntityId'];
            $extraData = $beaconTrackData['extraData'];
            $beaconTrackURL = getBeaconTrackingURL($pageIdentifier, $pageEntityId, $extraData);
            if($ampViewFlag)
            {
                //echo "<amp-img id='beacon_track_img' src='$beaconTrackURL' width='1' height='1' class='amp-viewed-res'></amp-img>";    
                return $beaconTrackURL;
            }
            else
            {
                echo "<img id='beacon_track_img' src='$beaconTrackURL' width='1' height='1' style='display:none;' />";    
            }
    //}
}

function sanitize_output($buffer) {
$search = array(
    '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
    '/[^\S ]+\</s',  // strip whitespaces before tags, except space
    '/(\s)+/s'       // shorten multiple whitespace sequences
);
$replace = array('>', '<', '\\1' );
$buffer = preg_replace($search, $replace, $buffer);
return $buffer;
}

function html_compress($buffer) {

    $rules = '%# Collapse whitespace everywhere but in blacklisted elements.
          (?>             # Match all whitespans other than single space.
            [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
          | \s{2,}        # or two or more consecutive-any-whitespace.
          ) # Note: The remaining regex consumes no text at all...
          (?=             # Ensure we are not in a blacklist tag.
            [^<]*+        # Either zero or more non-"<" {normal*}
            (?:           # Begin {(special normal*)*} construct
              <           # or a < starting a non-blacklist tag.
              (?!/?(?:textarea|pre|script)\b)
              [^<]*+      # more non-"<" {normal*}
            )*+           # Finish "unrolling-the-loop"
            (?:           # Begin alternation group.
              <           # Either a blacklist start tag.
              (?>textarea|pre|script)\b
            | \z          # or end of file.
            )             # End alternation group.
          )  # If we made it here, we are not in a blacklist tag.
          %Six';

      $new_buffer = preg_replace($rules, " ", $buffer);

      // We are going to check if processing has working
    if ($new_buffer === null)
    {
      $new_buffer = $buffer;
    }
    return $new_buffer;
}

function getDNSPrefetchLinks($pageType) {
    $dns_prefetch = array();
    /*Try to keep it less than 8 as browser's have max 8 worker thread to prefetch, if more then it could be slow*/
    switch($pageType) //PageType can be Mobile, Mobile_Srp,Desktop, Desktop_srp etc.
    {
        case 'MOBILE':{
            $dns_prefetch = array(
             '//'.CSSURL //for css
            ,'//'.JSURL // for JS
            ,'//'.IMGURL // for static images
            ,MEDIAHOSTURL // for media images
            ,'https://script.crazyegg.com' //Heatmap
            ,'https://track.99acres.com' //JSB9 pixel
            ,'//ask.shiksha.com' //ANA
        );
        }break;
    
    }
   /*list is still not complete 
     *we can identify using network panel in browser but it can change in future.
    */
    return $dns_prefetch;
}

function isOfficeIP()
{
    if(ENVIRONMENT != 'production') {
            return TRUE;
    }
    else {
            $CI = & get_instance();
            $CI->config->load('officeip');
            $officeIPList = $CI->config->item('officeIPList');
    
            $currentClientIP = trim($_SERVER['HTTP_TRUE_CLIENT_IP']);
    
            return in_array($currentClientIP, $officeIPList);
    }
}

function escapeStrForJsonEncoding($str){
return str_replace("'","\u0027",$str);
}

function escapeArrForJsonEncoding($arr){
if(is_array($arr)){
    foreach($arr as $key => $val){
        if(is_array($val)){
            $arr[$key] = escapeArrForJsonEncoding($val);
        }
        else{
            $arr[$key] = escapeStrForJsonEncoding($val);
        }
    }
}
else{
    $arr = escapeStrForJsonEncoding($arr);
}

return $arr;
}

function getShortIndianDisplableAmount($amount, $decimalPointPosition = 2){
if($amount == 0){
    return "";
}
if($amount < 1000){
    $finalAmount = number_format($amount, $decimalPointPosition, '.', '');
}else if($amount < 100000){
    $finalAmount = $amount / 1000;
    $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
    $finalAmount .= " K";
}else if($amount < 10000000){
    $finalAmount = $amount / 100000;
    $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
    $finalAmount .= " L";
}else{
    $finalAmount = $amount / 10000000;
    $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
    $finalAmount .= " Cr";
}
$finalAmount = "Rs ".$finalAmount;
return $finalAmount;
}

function getLongIndianDisplableAmount($amount)
{
    if($amount < 1000){
        return '';
    }
    else if($amount < 100000){
        $finalAmount = intval($amount / 1000);
        $finalAmount .= " thousand";
    }
    else if($amount < 10000000){
        $finalAmount = intval($amount / 100000);
        $finalAmount .= ($finalAmount == 1)? " lakh" : " lakhs";
    }
    else{
        $finalAmount = intval($amount / 10000000);
        $finalAmount .= ($finalAmount == 1)? " crore" : " crores";
    }
    $finalAmount = "Rs ".$finalAmount;
    return $finalAmount;
} 

function isValidAbroadCourseObject($course){
    if(!is_object($course) || $course instanceof Course || $course->getId() == "")
    {
            return false;
    }
    return true;
}

function isValidAbroadUniversityObject($univObj){
    if(!is_object($univObj) || !($univObj instanceof University) || $univObj->getId() == "")
    {
            return false;
    }
    return true;
}

function isValidAbroadDepartmentObject($depObj){
    if(!is_object($depObj) || !($depObj instanceof AbroadInstitute) || $depObj->getId() == "")
    {
        return false;
    }
    return true;
}

/*
 * function to create a circular progress representation via SVG
 * @params: array consisting of :
 * - center(X,Y) of circle,
 * - start(X,Y) of progress path,
 * - radius of circle,
 * - circStrokeColor,
 * - pathStrokeColor,
 * - strokeWidth (same for circle & progress path),
 * - percentFill (percentage of progress 0-100%)
 */
function createStaticRadialProgress($params)
{
   // convert progress percentage to angle taken from center to top
   $angle = $params['percentFill']*(360/100);
   // if angle > 180, we need a longer arc between start & end coordinates
   $largeArcFlag = ($angle>180?1:0);
   /* calculate end points:
	*	endX = centerX +radius*(sin(angle))
	*  endY = centerY -radius*(cos(angle))
	*/
   $endX = $params['center'][0] + ($params['radius']*(sin(deg2rad($angle))));
   $endY = $params['center'][1] - ($params['radius']*(cos(deg2rad($angle))));
   // create SVG elements
   $svgString = '<svg width="'.$params['svgWidth'].'" height="'.$params['svgHeight'].'" viewPort="0 0 '.$params['svgWidth'].' '.$params['svgHeight'].'" version="1.1" xmlns="http://www.w3.org/2000/svg">';
   $svgString .='<style type="text/css" ><![CDATA[circle.circleClass {stroke: '.$params['circStrokeColor'].';stroke-width: '.$params['strokeWidth'].'px;fill:   none;}]]></style>';
   $svgString .='<circle  class="circleClass" cx="'.$params['center'][0].'" cy="'.$params['center'][1].'" r="'.$params['radius'].'"/>';
   $svgString .='<path d="M'.$params['start'][0].','.$params['start'][1].' A'.$params['radius'].','.$params['radius'].' 0 '.$largeArcFlag.',1 '.$endX.','.$endY.'" style="stroke:'.$params['pathStrokeColor'].'; stroke-width:'.$params['strokeWidth'].'px;fill:none;"/>';
   $svgString .='</svg>';
   return $svgString;
}


function sanitizeUrlString( $urlString )
    {
        // sanitize the URL
        $urlString = str_replace(array(' ','/','(',')',','), '-', $urlString);
        $urlString = str_replace(".", "", $urlString); // eg. M.Phil = mphil
        $urlString = str_replace("&", "and", $urlString); 
        $urlString = preg_replace('!-+!', '-', $urlString);
        $urlString = strtolower(trim($urlString, '-'));

        // return the sanitized URL
        return $urlString;
    }
function verifyCSRF(){
    $CI = & get_instance();
    $CI->load->library('security');
    return $CI->security->verifyCSRF();
}

function getCSRFToken(){
    $CI = & get_instance();
    $CI->load->library('security');
    $csrfData = array('name'=>$CI->security->csrf_token_name, 'value'=>$CI->security->csrf_hash);
    return $csrfData;
}

if (! function_exists('makeShikshaHTTPUrl'))
{
    function makeShikshaHTTPUrl($url)
    {
        $url = str_replace("https://", "http://", $url);               
        return $url;
    }
}

if (! function_exists('isShowingAds'))
{
    function isShowingAds($product,$bannerProperties=array())
    {
        if(strtolower($product) == 'ranking'){
            return true;
        }else if(in_array(strtolower($product), array('articles', 'forums', 'careerproduct', 'exampage', 'rnrcategorypage', 'ranking','category', 'categoryheader', 'mba', 'articlesd','gradheader','testprep','anadesktopv2')) && !in_array($bannerProperties['pageId'], array('DISCUSSION_DETAIL','TAGS'))){
            return true;
        } else if($bannerProperties['pageId'] == 'EXAM'){
            return true;
        }
        return false;
    }
}


function urlsafe_b64encode($string) {
    return str_replace(array('+','/','='),array('-','_',''), base64_encode($string));
}




if(! function_exists('updateGoogleCDNcacheForAMP'))
{
    function updateGoogleCDNcacheForAMP($url,$isRemove = false)
    {

        $timestamp=time();

        if(ENVIRONMENT == 'development' || ENVIRONMENT == "production") // || ENVIRONMENT == 'beta'
        {
        
            if(empty($url))
                return;

            $url = str_replace("https://","",$url);

            $cdnCommand = 'c';

            $ampBaseUrl = "https://www-shiksha-com.cdn.ampproject.org";
            $signatureUrl = '/update-cache/c/s/'.$url.'?amp_action=flush&amp_ts='.$timestamp;
            if($isRemove)
                $cdnCommand = 'i';

            $pkeyid = openssl_pkey_get_private("file://private-key.pem");

            openssl_sign($signatureUrl, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

            openssl_free_key($pkeyid);

            $signature = urlsafe_b64encode($signature);

            $ampUrl = $ampBaseUrl.$signatureUrl."&amp_url_signature=".$signature;

            $c = curl_init();
            curl_setopt($c, CURLOPT_URL,$ampUrl);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
            $output =  curl_exec($c);
            curl_close($c);
            return $output;
        }
        
    }
}

if(!function_exists('writeToLog')) {
    function writeToLog($logMessage, $logFile) {
        if(LISTINGS_LOGGING == 1) {
            error_log($logMessage.PHP_EOL , 3, $logFile);
        }
    }
}

if(!function_exists('moneyAmountFormattor')){
    function moneyAmountFormattor($amount, $currencyId, $roundOffFlag = 0){
        if($currencyId == 1)
            $currencyType = 'en_IN';
        else
            $currencyType = 'en_US.UTF-8';
        setlocale(LC_MONETARY, $currencyType);
        if($roundOffFlag)
        {
            $amount = money_format('%!.0n', $amount);
        }
        else if (ctype_digit($amount) )
        {
            // if whole number use this format 
            $amount = money_format('%!.0n', $amount);
        }
        else
        {
            // if not whole number
            $amount = money_format('%!i', $amount);
        }
        return $amount;
    }
}

if(!function_exists('getIndianDisplableAmount')){
    function getIndianDisplableAmount($amount, $decimalPointPosition = 1)
    {
        if($amount < 1000)
            $finalAmount = number_format($amount, $decimalPointPosition, '.', '');
        else if($amount < 100000)
        {
            $finalAmount = $amount / 1000;
            $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
            $finalAmount .= " Thousand";//($finalAmount == 1)? " Thousand" : " Thousands";
        }
        else if($amount < 10000000)
        {
            $finalAmount = $amount / 100000;
            $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
            $finalAmount .= ($finalAmount == 1)? " Lakh" : " Lakhs";
        }
        else
        {
            $finalAmount = $amount / 10000000;
            $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
            $finalAmount .= ($finalAmount == 1)? " Crore" : " Crores";
        }
        
        $finalAmount = "Rs ".$finalAmount;
        return $finalAmount;
    }
}

if(!function_exists('appendDegreeToLevel')){
    function appendDegreeToLevel($courseLevel){
        $showCourseLevels = array();
        foreach ($courseLevel as $key=>$value) {
            if($value == 'Bachelors' || $value == 'Masters'){
                $showCourseLevels[] = $value.' Degree';
            }else{
                $showCourseLevels[] = $value;
            }
        }
        return $showCourseLevels;
    }
}

if(!function_exists('getCallStack')) {
    function getCallStack($limit = null) {
        $backtrace = debug_backtrace();
        $functionTrace = array();
        $traceExcludeFunctions = array('__call','call_user_func_array','require','require_once','include','include_once','view','_ci_load', 'getCallStack');
        foreach($backtrace as $trace) {
            if($trace['line'] && !in_array($trace['function'],$traceExcludeFunctions) && strpos($trace['file'],'third_party') === FALSE && (is_null($limit) || $key < $limit)) {
                $functionTrace[] = $trace['function']." at line ".$trace['line']." in ".str_replace(APPPATH,'',str_replace(FCPATH,'',$trace['file']));
                $key++;
            }
        }
        return $functionTrace;
    }
}

if(!function_exists('time_elapsed_string')) {
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}

if(!function_exists('isTestUser')) {
    function isTestUser($mobile){  
        require FCPATH.'globalconfig/testUserConfig.php';
        if($mobile) {
            if(is_array($testUserMobileNumbers) && in_array($mobile,$testUserMobileNumbers)){
                return 1;
            }
        }
        return 0;
    }
}

if(!function_exists('sanitizeString')) {
    function sanitizeString($string){
        return preg_replace('/[^A-Za-z0-9]/', '', $string);
    }
}

function getUserProfileImageLink($imageUrl){
    if(empty($imageUrl) || strpos($imageUrl,'.gif')!== false){
        return '/public/images/studyAbroadCounsellorPage/profileDefaultNew1.jpg';
    }else{
        return $imageUrl;
    }
}

function getProfileImageAbsoluteUrl($imageUrl){
    if(strpos($imageUrl,'mediadata')){
        return MEDIAHOSTURL.$imageUrl;
    }else{
        return IMGURL_SECURE.$imageUrl;
    }
}

function formatAmountToNationalFormat($amount, $decimalPointPosition = 1, $suffix = array(" K"," L"," C"), $numberType='currency')
{
    if(count($suffix)!=3){
        return $amount;
    }
    if($amount < 1000){
        if($numberType=='currency'){
            $finalAmount = number_format($amount, $decimalPointPosition, '.', '');
        }
        elseif($numberType=='count'){
            $finalAmount = $amount;
        }
        }
    else if($amount < 100000)
    {
        $finalAmount = $amount / 1000;
        $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
        $finalAmount .= $suffix[0];
    }
    else if($amount < 10000000)
    {
        $finalAmount = $amount / 100000;
        $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
        $finalAmount .= $suffix[1];
    }
    else
    {
        $finalAmount = $amount / 10000000;
        $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
        $finalAmount .= $suffix[2];
    }
    
    return $finalAmount;
}

function implodeAnd($items = array()){
    if(empty($items)){
        return '';
    }
    if(count($items) == 1){
        return $items[0];
    }
    $lastItem = $items[count($items)-1];
    unset($items[count($items)-1]);
    return implode(', ', $items).' and '.$lastItem;
}

function getCourseLevel($level){

    $bachelors = array('bachelors', 'bachelors certificate', 'bachelors diploma');
    $masters = array('masters', 'masters certificate', 'masters diploma', 'phd');
    $level = strtolower($level);
    if(in_array($level, $bachelors)){
        return STUDY_ABROAD_BACHELORS;
    }
    elseif (in_array($level, $masters)){
        return STUDY_ABROAD_MASTERS;
    }
    return "others";
}

function getUnivDefaultImageBySize($url, $size = '172x115'){
    if(empty($url)){
        return '';
    }
    $urlParts = explode('.', $url);
    $urlPartCount = count($urlParts);
    $urlParts[$urlPartCount-2] = $urlParts[$urlPartCount-2].'_'.$size;
    return implode('.', $urlParts);
}

function isSAGlobalNavSticky($pageName)
{
    $notStickyPages  = array('','categoryPage','examContentPage','applyContentPage',
        'compareCoursesPage','editUserProfilePage','schedulePickupPage','shipmentConfirmationPage',
        'AbroadSignup','forgotPasswordPage','loginPage','rmcSuccessPage','downloadGuideThankYouPage'
    ,'downloadBrochureThankYouPage','guidePage','articlePage');
    if(in_array($pageName,$notStickyPages))
    {
        return false;
    }
    else
    {
        return true;
    }
}

// Function to return number of day type between two dates
function getNumberOfDayTypeOccurrenceBetweenDates($startDate,$endDate,$dayType ='sun')
{
    $dayTypeNumericVal = array(
        'mon' => 1,
        'tue' => 2,
        'wed' => 3,
        'thu' => 4,
        'fri' => 5,
        'sat' => 6,
        'sun' => 7,
    );
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $days = $start->diff($end, true)->days;
    //As week is of 7 days
    $count = intval($days / 7);
    //to check considered 'day type' is present in remaining days
    if($start->format('N') <= $dayTypeNumericVal[$dayType])
    {
    $count += (($start->format('N') + ($days % 7)) >= $dayTypeNumericVal[$dayType]);
    }
    else
    {
        $count += (($start->format('N')-$dayTypeNumericVal[$dayType] + ($days % 7)) >= 7);
    }

    return $count;
}

if(!function_exists('dec_enc')) {
    function dec_enc($action, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'This is my secret key';
        $secret_iv = 'This is my secret iv';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
}

//find multiple occurrences in paragraphs
if(!function_exists('findMultipleValues')) {
    function findMultipleValues($string, $rules) {
        $rules = ($rules) ? $rules : "/\b\d{4}\b/"; // default find any number (length 4) like 2018  from string 
        if (preg_match_all($rules, $string, $outPut)) {
            return $outPut[0];
        }else {
            return array();
        }
    }
}

//function to get array of all months

if(!function_exists('getAllMonthsArr')){
    function getAllMonthsArr(){
        $returnArr = array();
        for($i=1;$i<=12;$i++)
        {
            $d = date_create_from_format('n',$i);
            $returnArr[$i] = $d->format('M');
        }
        return $returnArr;
    }
}

//function to return list of years given -ive and +ive difference from current year

if(!function_exists('getYearListForGivenDifference')){
    function getYearListForGivenDifference($negativeDiff,$positiveDiff){
        $yearArr = array();
        $currYear = date("Y");
        $startYear = $currYear-$negativeDiff;
        $endYear = $currYear+$positiveDiff;
        for($year=$startYear; $year<=$endYear; $year++){
            array_push($yearArr,$year);
        }
        return $yearArr;
    }
}

function convertDateISTtoUTC($date){
    $dateObj = new DateTime($date);
    $dateObj->setTimezone(new DateTimeZone("UTC"));
    return $dateObj->format("Y-m-d H:i:s");
}

function convertDateUTCtoIST($date){
    $dateObj = new DateTime($date, new DateTimeZone('UTC'));
    $dateObj->setTimezone(new DateTimeZone('Asia/Kolkata'));
    return $dateObj->format("Y-m-d H:i:s");
}

if(function_exists('runkit_function_rename') && (phpversion() != '5.4.19')) {
    runkit_function_rename('json_encode','original_json_encode');
    runkit_function_add('json_encode','$value,$options=0,$depth=512','$options=$options|JSON_PARTIAL_OUTPUT_ON_ERROR;return original_json_encode($value, $options, $depth);');

    runkit_function_rename('json_decode','original_json_decode');
    runkit_function_add('json_decode','$json,$assoc=FALSE,$depth=512,$options=0','$json_decode_response  = original_json_decode($json,$assoc,$depth,$options);return $json_decode_response;');
}

/*function getABTestValueToShowSAssistant(){
    $varient = 1; // not show shiksha - assistant

    if(isset($_COOKIE['SAab']) && in_array($_COOKIE['SAab'], array(1,2))){
        $varient = $_COOKIE['SAab'];  // SAab
    }else{
        $randomNumber = rand() %100;
        if($randomNumber <= SHOW_SASSISTANT){  // show shiksha - assistant
            $varient = 2;
        }else{ // don't show shiksha - assistant
            $varient = 1;
        }
        setcookie('SAab',$varient, 0, '/', COOKIEDOMAIN);
    }
    return $varient;
}*/

function setUserCookie($cookieValue,$path,$domain,$timeasZero,$time){
    if (empty($timeasZero) && empty($time)){
        setcookie('user',$cookieValue,time() + (60*60*24*30*12),$path,$domain);
    }
    else if (empty($timeasZero) && !empty($time)){
        setcookie('user',$cookieValue,$time,$path,$domain);
    }
    else{
        setcookie('user',$cookieValue,'0',$path,$domain);   
    }
}
