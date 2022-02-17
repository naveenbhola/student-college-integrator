<?php

error_reporting(E_ALL);
require '/var/www/html/shiksha/shikshaConfig.php';
require_once '/var/www/html/shiksha/application/libraries/ProcessImage.php';

$hostName = MEDIADATA_DB_HOST;
	$userName = MEDIADATA_DB_USER;
	$password = MEDIADATA_DB_PASSWORD;


define ("MAX_SIZE","100");
// width and height for the thumbnail
define ("WIDTH","150");
define ("HEIGHT","100");
define("MAX_VIDEO_SIZE","10485760");
define("MAX_AUDIO_SIZE","5242880");

define('YOUTUBE_API_V3_KEY', 'AIzaSyCPLPd7UjZLX-uHC_sXvfYbm3PIbtkd18U');


//$url = 'http://images.shiksha.com';
//$url .= ($_SERVER['SERVER_PORT']!='') ? ':'. $_SERVER['SERVER_PORT'] : '';
$url = MEDIAHOSTURL;
define("URL", $url);
$con = null;
function checktype($iCount)
{
	$iFlag = 1;
	$TypeFlag = 1;
	while($iFlag < $iCount)
	{
		$size = getimagesize($_FILES['file'.$iFlag]['tmp_name']);
		$type = $size['mime'];
		if(!($type== "image/gif" || $type== "image/jpeg"|| $type=="image/jpg" || $type== "image/png"))
			{ $TypeFlag = 0; break; }
		else
			$TypeFlag = 1;
		$iFlag ++;
	}
	return $TypeFlag ;
}

function checkvideotype($type)
{
	if(!($type== "video/mp4" || $type== "video/x-ms-wm" || $type == "video/x-ms-wmv" || $type=="video/x-msvideo" || $type== "application/x-flash-video" || $type == "application/x-shockwave-flash" || $type == "video/mpeg"))
		return  0;
	else
		return 1;
}
function checkDocumenttype($type)
{
    $type = strtolower(trim($type,'"\' '));
	if(!($type == "application/msword" ||
		 $type == "application/vnd.ms-powerpoint"||
		 $type =="application/pdf" ||
		 $type == "application/vnd.ms-excel" ||
		 $type == "text/plain" ||
		 $type == "application/postscript" ||
		 $type == 'application/octet-stream'||
		 $type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" ||
		 $type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ||
		 $type == "application/vnd.openxmlformats-officedocument.presentationml.presentation"||
                 $type == 'application/vnd.ms-outlook'
		)
	)
		return  0;
	else
		return 1;
}

function checkDocumentTypeForMedia($type){
	$type = strtolower(trim($type,'"\' '));		
	if(!($type == "application/msword" ||
		 $type == "application/vnd.ms-powerpoint"||
		 $type == "application/pdf" ||
		 $type == "application/octectstream" || 
		 $type == "application/vnd.ms-excel" ||
		 $type == "application/xls" || 
		 $type == "text/plain" ||
		 $type == "application/postscript" ||
		 $type == 'application/octet-stream'||
		 $type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" ||
		 $type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ||
		 $type == "application/vnd.openxmlformats-officedocument.presentationml.presentation" ||
		 $type == "application/vnd.oasis.opendocument.text" ||
		 $type == "application/vnd.sun.xml.writer" ||
		 $type == "application/zip" ||
		 $type == "application/gzip" ||
		 $type == "application/tar" ||
		 $type == "application/rar" ||
		 $type == "application/x-gzip" ||
		 $type == "application/x-tar" || 
		 $type == "application/x-zip-compressed" ||
		 $type == "application/x-7z-compressed" ||
		 $type == "application/7z-compressed"  || 
		 $type == "text/html" ||
		 $type == "application/x-rar"
		)
	)
		return  0;
	else
		return 1;	
}

function checksize($iCount,$Max_Size)
{
	$iFlag = 1;
	$SizeFlag = 1;
	while($iFlag < $iCount)
	{
		$size = $_FILES['file'.$iFlag]['size'];		
		if($size > $Max_Size)
			{ $SizeFlag = 0;
			  break;
			}
		else
			$SizeFlag = 1;
		$iFlag ++;
	}
	return $SizeFlag;
}

//API to insert image
function insertimage($arrayofdescription,$iCount,$id,$typeofmedia)
{
    ini_set('memory_limit', '200M');
    global $hostName;
    global $userName;
    global $password;



    if($_FILES['file1']['tmp_name'] == '')
        return ("File uploading failed");

    $sizevar = '';

    if($typeofmedia == "institute" || $typeofmedia == "course" || $typeofmedia == "nationalInstitute") {
        define("MAX_IMAGE_SIZE","26214400");
        $sizevar = "25 Mb";
    } else if($typeofmedia == "scholarship" || $typeofmedia == "featured" || $typeofmedia == "notification" || $typeofmedia == "blog" || $typeofmedia == "userApplicationfile" || $typeofmedia == 'of_documents' || $typeofmedia == 'cat_page_articles_widget' || $typeofmedia == "university" || $typeofmedia == "abroadCourse" || $typeofmedia == "uploadFromCRDashboard" ||  $typeofmedia == "newProfilePage")
    {
        define("MAX_IMAGE_SIZE","5242880");
        $sizevar = "5 MB";
    }
    else if($typeofmedia == "studentDocs")
    {
        define("MAX_IMAGE_SIZE","20971520");
        $sizevar = "20 MB";
    }
    else if($typeofmedia == "homepageFeaturedCollegeBanner"){
        define("MAX_IMAGE_SIZE","15000");
        $sizevar = "15 KB";
    }
    else if($typeofmedia == "marketingImgWithTxt"){
        define("MAX_IMAGE_SIZE","15000");
        $sizevar = "15 KB";
    }
    else if($typeofmedia == "marketingOnlyImg"){
        define("MAX_IMAGE_SIZE","25000");
        $sizevar = "25 KB";
    }else if($typeofmedia == "saCounselorImage" || $typeofmedia == "rankingPublisher"  || $typeofmedia == "expertPhoto"){
    	define("MAX_IMAGE_SIZE","2097152");
        $sizevar = "2 MB";
    }else{
        define("MAX_IMAGE_SIZE","1048576");
        $sizevar = "1 Mb";
    }
    if(!checktype($iCount))
        return("Images of type jpeg,gif,png are allowed");
    if(!checksize($iCount,MAX_IMAGE_SIZE))
        return("Size limit of ".$sizevar." exceeded");

    if($typeofmedia == 'of_documents') {
        $ImagesPath = MEDIA_BASE_PATH."/onlineforms";
    } else if($typeofmedia == "cat_page_articles_widget") {
        $ImagesPath = MEDIA_BASE_PATH."/images/categoryPageWidgetsImages";
    }else if($typeofmedia == 'career_documents') {
        $ImagesPath = MEDIA_BASE_PATH."/career";
    } else if($typeofmedia == "uploadFromCRDashboard"){
        $ImagesPath = MEDIA_BASE_PATH."/CampusAmbassador";
    }elseif($typeofmedia == 'blog' || $typeofmedia =='saContentBlog' ) {
        $ImagesPath = MEDIA_BASE_PATH."/images/articles";
    }else {
        $ImagesPath = MEDIA_BASE_PATH."/images";
    }
    //$ImagesPath = "/var/www/mdb_image/server/images";

    if( !(file_exists($ImagesPath) && is_dir($ImagesPath)) ) {
        @mkdir($ImagesPath, 0777);
    }

    //Check for directory in Server
    //if(!is_dir($ImagesPath))
    //	mkdir($ImagesPath,0777);
    //      chmod($ImagesPath,0777);

    //Set the target locations for the files and move them
    $iSuccess = 1;
    $iFlag = 1;
    $mediaid = 0;
    $date = date("y.m.d");

    while($iFlag < $iCount)
    {
        //Set the values
        $name_id = time().basename($_FILES['file'.$iFlag]['tmp_name']);

        //$imageurl = URL ."/mdb_image/server/images/".$name_id;
        $media= getimagesize($_FILES['file'.$iFlag]['tmp_name']);
        $type = $media['mime'];
        //$ext = substr($type,-1* (strpos($type,"/") - 1));
        //$ext = basename($_FILES['file'.$iFlag]['tmp_name']);
        $ext = basename($type);
        $size = $_FILES['file'.$iFlag]['size'];
        $target_location = $ImagesPath."/".$name_id.'.'.$ext;
        $name = $_FILES['file'.$iFlag]['name'];
        if($typeofmedia == 'of_documents') {
            $imageurl = "/mediadata/onlineforms/".$name_id.'.'.$ext;
            $thumburl = "/mediadata/onlineforms/".$name_id."_m.".$ext;
            $thumburl1 = "/mediadata/onlineforms/".$name_id."_s.".$ext;
        } else if($typeofmedia == 'cat_page_articles_widget') {
            $imageurl = "/mediadata/images/categoryPageWidgetsImages/".$name_id.'.'.$ext;
            $thumburl = "/mediadata/images/categoryPageWidgetsImages/".$name_id."_m.".$ext;
            $thumburl1 = "/mediadata/images/categoryPageWidgetsImages/".$name_id."_s.".$ext;
        } else if($typeofmedia == 'career_documents') {
            $imageurl = "/mediadata/career/".$name_id.'.'.$ext;
            $thumburl = "/mediadata/career/".$name_id."_m.".$ext;
            $thumburl1 = "/mediadata/career/".$name_id."_s.".$ext;
        }else if($typeofmedia == 'university'){
            $imageurl = "/mediadata/images/".$name_id.'.'.$ext;
            $thumburl = "/mediadata/images/".$name_id."_m.".$ext;
            $thumburl1 = "/mediadata/images/".$name_id."_s.".$ext;
            $thumburl_location_300x200 = $ImagesPath.'/'.$name_id.'_300x200.'.$ext;
            $thumburl_location_172x115 = $ImagesPath.'/'.$name_id.'_172x115.'.$ext;
            $thumburl_location_75x50 = $ImagesPath.'/'.$name_id.'_75x50.'.$ext;
            $thumburl_location_135x90 = $ImagesPath.'/'.$name_id.'_135x90.'.$ext;
        }else if($typeofmedia == 'saContentBlog'){
            $imagePath4SA = MEDIA_BASE_PATH."/images/articles";
            $imageurl  = "/mediadata/images/articles/".$name_id.'.'.$ext;
            $thumburl  = "/mediadata/images/articles/".$name_id."_m.".$ext;
            $thumburl1 = "/mediadata/images/articles/".$name_id."_s.".$ext;
            $thumburl_location_300x200 = $imagePath4SA.'/'.$name_id.'_300x200.'.$ext;
            $thumburl_location_172x115 = $imagePath4SA.'/'.$name_id.'_172x115.'.$ext;
            $thumburl_location_75x50   = $imagePath4SA.'/'.$name_id.'_75x50.'.$ext;
            $thumburl_location_135x90  = $imagePath4SA.'/'.$name_id.'_135x90.'.$ext;
        }else if($typeofmedia =='uploadFromCRDashboard'){
            $imageurl = "/mediadata/CampusAmbassador/".$name_id.'.'.$ext;
            $thumburl = "/mediadata/CampusAmbassador/".$name_id."_m.".$ext;
            $thumburl1 = "/mediadata/CampusAmbassador/".$name_id."_s.".$ext;
        }else if($typeofmedia == 'blog'){
            $imageurl = "/mediadata/images/articles/".$name_id.'.'.$ext;
            $thumburl = "/mediadata/images/articles/".$name_id."_m.".$ext;
            $thumburl1 = "/mediadata/images/articles/".$name_id."_s.".$ext;
        }else if($typeofmedia == "newProfilePage"){
            $thumburl_location_1024 = $ImagesPath.'/'.$name_id.'.'.$ext;
            $imageurl = "/mediadata/images/".$name_id.'.'.$ext;
            $thumburl = "/mediadata/images/".$name_id."_m.".$ext;
            $thumburl1 = "/mediadata/images/".$name_id."_s.".$ext;
            $thumburl_location_300x200 = $ImagesPath.'/'.$name_id.'_300x200.'.$ext;
        }
        else if($typeofmedia == 'nationalInstitute'){

            $varientExtension = (strtolower($ext) == 'jpeg') ? 'jpg' : $ext;

            $imageurl 	  = "/mediadata/images/".$name_id.'.'.$ext;
            $thumburl     = "/mediadata/images/".$name_id."_100x78.".$varientExtension;
            $thumburl1 	  = "/mediadata/images/".$name_id."_68x54.".$varientExtension;
            $image68x54   = $ImagesPath."/".$name_id."_68x54.".$varientExtension;
            $image100x78  = $ImagesPath."/".$name_id."_100x78.".$varientExtension;
            $image135x100 = $ImagesPath."/".$name_id."_135x100.".$varientExtension;
            $image155x116 = $ImagesPath."/".$name_id."_155x116.".$varientExtension;
            $image205x160 = $ImagesPath."/".$name_id."_205x160.".$varientExtension;
            $image210x157 = $ImagesPath."/".$name_id."_210x157.".$varientExtension;
            $image270x200 = $ImagesPath."/".$name_id."_270x200.".$varientExtension;
            $imageGallery = $ImagesPath."/".$name_id."_g.".$varientExtension;
        }else if($typeofmedia == 'saCounselorImage'){
            $imageurl = "/mediadata/images/".$name_id.'.'.$ext;
            $thumburl = "/mediadata/images/".$name_id."_m.".$ext;
            $thumburl1 = "/mediadata/images/".$name_id."_s.".$ext;
            $thumburl_location_40x40 = $ImagesPath.'/'.$name_id.'_40x40.'.$ext;
            $thumburl_location_64x64 = $ImagesPath.'/'.$name_id.'_64x64.'.$ext;
            $thumburl_location_160x160 = $ImagesPath.'/'.$name_id.'_160x160.'.$ext;
            $thumburl_location_320x320 = $ImagesPath.'/'.$name_id.'_320x320.'.$ext;
            $thumburl_location_640x640 = $ImagesPath.'/'.$name_id.'_640x640.'.$ext;

        }
        else if($typeofmedia == "expertPhoto")
        {
            $imageurl = "/mediadata/images/".$name_id.'.'.$ext;
            $thumburl = "/mediadata/images/".$name_id."_m.".$ext;
            $thumburl1 = "/mediadata/images/".$name_id."_s.".$ext;

            $thumburl_28x28 = $ImagesPath.'/'.$name_id.'_28x28.'.$ext;
            $thumburl_60x60 = $ImagesPath.'/'.$name_id.'_60x60.'.$ext;
            $thumburl_72x72 = $ImagesPath.'/'.$name_id.'_72x72.'.$ext;
            $thumburl_96x96 = $ImagesPath.'/'.$name_id.'_96x96.'.$ext;
        }
        else{
            $imageurl = "/mediadata/images/".$name_id.'.'.$ext;
            $thumburl = "/mediadata/images/".$name_id."_m.".$ext;
            $thumburl1 = "/mediadata/images/".$name_id."_s.".$ext;
        }


        $thumburl_location = $ImagesPath.'/'.$name_id.'_m.'.$ext;
        $thumburl_location1 = $ImagesPath.'/'.$name_id.'_s.'.$ext;
        $thumburl_location2 = $ImagesPath.'/'.$name_id.'_t.'.$ext;


        $description = $arrayofdescription['description'.$iFlag];

        if(!(move_uploaded_file($_FILES['file'.$iFlag]['tmp_name'],$target_location)))
        {
            if($_FILES['uploadedFile']['error'] > 0)
            {
                switch($_FILES['uploadFile'] ['error'])
                {
                    case 1: echo "File exceeded maximum server upload size";
                        break;
                    case 2: echo "File exceeded maximum file size";
                        break;
                    case 3: echo "File only partially uploaded";
                        break;
                    case 4: echo "File not uploaded";
                        break;
                }
            }
            $iSuccess = 0;
            break;
        }
        else
        {
            //mysql_connect("localhost", "shiksha", "shiKm7Iv80l") OR DIE (mysql_error());
            //
            // mysql_connect($hostName, $userName, $password) OR DIE (mysql_error());


            //sql vulnerable code removed

            //                      $con = mysqli_connect($hostName, $userName, $password, "shiksha");
            // // select the db
            // // mysql_select_db ("shiksha") OR DIE ("Unable to select db".mysql_error());
            // //sql query
            //                      if (mysqli_connect_errno())
            // 			{
            // 				echo "Failed to connect to MySQL: " . mysqli_connect_error();
            // 				die;
            // 			}

            // author Pulkit vulnerable code removed above

            $conn = new mysqli($hostName,$userName,$password,"shiksha");

            if ($conn->connect_error) {
                echo ("Failed to connect to MySQL: " . $conn->connect_error);
                die;
            }

            $sql = "INSERT INTO tImageData (mediaid,type,size,name,url,description,uploadeddate,thumburl,thumburl_s) VALUES (?,?,?,?,?,?,?,?,?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('issssssss',$mediaid,$type,$size,$name,$imageurl,$description,$date,$thumburl,$thumburl1);
            $mediaid = "";
            $stmt->execute();

            if ($stmt->error){
                unlink($target_location);
                $stmt->close();
                $conn->close();
                return("File uploading failed");
            }

            /*if(!mysqli_query($con, $sql))
            {
                //Delete the saved file
                unlink($target_location);
                return("File uploading failed");
            }*/

            $mediaid = $stmt->insert_id;

            // $mediaid = mysql_insert_id();
            // $mediaid = mysqli_insert_id($con);


            $sql = "INSERT INTO tMediaMapping(typeid,mediaid,type,mediatype)values(?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iiss',$id,$mediaid,$typeofmedia,$mediatype);
            $mediatype = 'image';
            $stmt->execute();

            if ($stmt->error){
                unlink($target_location);
                $delsql = "DELETE from tImageData where mediaid = ?";
                $delStmt = $conn->prepare($sql);
                $delStmt->bind_param('i',$mediaid);
                $delStmt->execute();
                $delStmt->close();
                $conn->close();
                $stmt->close();
                return("File uploading failed");
            }
            $stmt->close();
            $conn->close();

            /*	if(!mysqli_query($con  , $sql))
                {
                    //Delete the saved file
                    unlink($target_location);
                    $delsql = "DELETE from tImageData where mediaid = $mediaid";
                    mysqli_query($con, $delsql);
                    return("File uploading failed");
                }
        */



            // call the function that will create the thumbnail. The function will get as parameters
            //the image name, the thumbnail name and the width and height desired for the thumbnail
            $returnarray[$iFlag-1]['imageurl']= $imageurl;
            if($typeofmedia == 'nationalInstitute'){

                $imagePaddingObj = new ProcessImage();
                $imagePaddingObj->load($target_location);
                $imagePaddingObj->processImage(68, 54); $imagePaddingObj->output($image68x54);
                $imagePaddingObj->processImage(100, 78); $imagePaddingObj->output($image100x78);
                $imagePaddingObj->processImage(135, 100); $imagePaddingObj->output($image135x100);
                $imagePaddingObj->processImage(155, 116); $imagePaddingObj->output($image155x116);
                $imagePaddingObj->processImage(205, 160); $imagePaddingObj->output($image205x160);
                $imagePaddingObj->processImage(210, 157); $imagePaddingObj->output($image210x157);
                $imagePaddingObj->processImage(270, 200); $imagePaddingObj->output($image270x200);

                // for gallery
                $imagePaddingObj = new ProcessImage();
                $imagePaddingObj->load($target_location);
                $imagePaddingObj->scaleImage(640, 480); $imagePaddingObj->output($imageGallery);
            }
            else if ($typeofmedia!="featured") {
                $thumb=make_thumb($target_location,$thumburl_location,117,78,$type,$mediaid);
                $thumb1=make_thumb($target_location,$thumburl_location1,58,52,$type,$mediaid);

                if($typeofmedia == 'university' || $typeofmedia == 'saContentBlog') {
                    $thumb_300x200=make_thumb($target_location,$thumburl_location_300x200,300,200,$type,$mediaid);
                    $thumb_172x115=make_thumb($target_location,$thumburl_location_172x115,172,115,$type,$mediaid);
                    $thumb_75x50=make_thumb($target_location,$thumburl_location_75x50,75,50,$type,$mediaid);
                    $thumb_135x90=make_thumb($target_location,$thumburl_location_135x90,135,90,$type,$mediaid);
                }

                if($typeofmedia == "newProfilePage"){

                    $thumb2=make_thumb($target_location,$thumburl_location_1024,1024,1024,$type,$mediaid);
                    $thumburl_location_l_url = "/mediadata/images/".$name_id.'.'.$ext;
                    $returnarray[$iFlag-1]['imageurl']= $thumburl_location_l_url;

                    $thumb3=make_thumb($target_location,$thumburl_location2,36,32,$type);
                    $thumb_300x200=make_thumb($target_location,$thumburl_location_300x200,300,200,$type,$mediaid);
                }

                if($typeofmedia == "user")
                {
                    $thumb2=make_thumb($target_location,$thumburl_location2,36,32,$type);
                }
                //Added by Ankur on 11 Oct to change the size of the Image
                if($typeofmedia == "userApplicationfile")
                {
                    $thumb2=make_thumb($target_location,$thumburl_location,195,192,$type);
                    $thumb1=make_thumb($target_location,$thumburl_location1,198,122,$type);
                }
                if($typeofmedia == 'career_documents') {
                    $thumb3=make_thumb($target_location,$thumburl_location,332,240,$type);
                    $thumb2=make_thumb($target_location,$thumburl_location1,140,58,$type);
                    //$thumb1=make_thumb($target_location,$thumburl_location2,332,240,$type);


                }
                if($typeofmedia == "institute" || $typeofmedia == "course" || $typeofmedia == "scholarship" || $typeofmedia == "notification")
                {
                    $thumburl_location_l = $ImagesPath.'/'.$name_id.'_l.'.$ext;
                    $thumb2=make_thumb($target_location,$thumburl_location_l,640,480,$type);
                    $tmpSize = getimagesize($target_location);
                    list($width, $height, $type, $attr) = $tmpSize;
                    if($width > 640 || $height > 480){
                        $thumburl_location_l_url = "/mediadata/images/".$name_id."_l.".$ext;
                        $returnarray[$iFlag-1]['imageurl']= $thumburl_location_l_url;
                    }
                }
                if($typeofmedia == "categoryselector")
                {
                    $thumburl_location_l = $ImagesPath.'/'.$name_id.'_l.'.$ext;
                    $thumb2=make_thumb($target_location,$thumburl_location_l,220,100,$type);
                    $tmpSize = getimagesize($target_location);
                    list($width, $height, $type, $attr) = $tmpSize;
                    if($width > 220 || $height > 100){
                        $thumburl_location_l_url = "/mediadata/images/".$name_id."_l.".$ext;
                        $returnarray[$iFlag-1]['imageurl']= $thumburl_location_l_url;
                    }
                }
                if($typeofmedia == "blog")
                {

                    $thumburl_location_a = $ImagesPath.'/'.$name_id.'_a.'.$ext;
                    $thumburl_location_b = $ImagesPath.'/'.$name_id.'_b.'.$ext;
                    $thumb2=make_thumb($target_location,$thumburl_location_a,259,166,$type);
                    $thumb3=make_thumb($target_location,$thumburl_location_b,171,105,$type);
                }
                if($typeofmedia == 'saCounselorImage') {
                    $thumb_40x40=make_thumb($target_location,$thumburl_location_40x40,40,40,$type,$mediaid);
                    $thumb_64x64=make_thumb($target_location,$thumburl_location_64x64,64,64,$type,$mediaid);
                    $thumb_160x160=make_thumb($target_location,$thumburl_location_160x160,160,160,$type,$mediaid);
                    $thumb_320x320=make_thumb($target_location,$thumburl_location_320x320,320,320,$type,$mediaid);
                    $thumb_640x640=make_thumb($target_location,$thumburl_location_640x640,640,640,$type,$mediaid);
                }

                if($typeofmedia == "expertPhoto")
                {
                    $thumb_28x28 = make_thumb($target_location,$thumburl_28x28,28,28,$type,$mediaid);
                    $thumb_60x60 = make_thumb($target_location,$thumburl_60x60,60,60,$type,$mediaid);
                    $thumb_72x72 = make_thumb($target_location,$thumburl_72x72,72,72,$type,$mediaid);
                    $thumb_96x96 = make_thumb($target_location,$thumburl_96x96,96,96,$type,$mediaid);

                }
            }
            else {
                $thumb = make_thumb($target_location,$thumburl_location,120,40,$type);
            }

            $returnarray[$iFlag-1]['mediaid'] = $mediaid;

            $returnarray[$iFlag-1]['thumburl']= $thumburl1;
            $returnarray[$iFlag-1]['thumburl_m']= $thumburl;
        }
        $iFlag = $iFlag + 1;
    }
    if(!$iSuccess)
        return("File uploading failed.Please try again");
    else
        $returnarray["status"] = 1;

    echo serialize($returnarray);

}

//API to create thumb for image
function make_thumb($img_name,$filename,$new_w,$new_h,$ext)
{
	//creates the new image using the appropriate function from gd library
	if( (mime_content_type($img_name) == 'image/jpeg') || (mime_content_type($img_name) == 'image/jpg') ){
		$src_img=imagecreatefromjpeg($img_name);
	} else if(mime_content_type($img_name) == 'image/png'){
		$src_img=imagecreatefrompng($img_name);
	} else if(mime_content_type($img_name) == 'image/gif'){
		$src_img=imagecreatefromgif($img_name);
	}


	//gets the dimmensions of the image
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);
	// next we will calculate the new dimmensions for the thumbnail image
	// 	1. calculate the ratio by dividing the old dimmensions with the new ones
	//	2. if the ratio for the width is higher, the width will remain the one define in WIDTH variable
	//		and the height will be calculated so the image ratio will not change
	//	3. otherwise we will use the height ratio for the image
	// as a result, only one of the dimmensions will be from the fixed ones
	$ratio1=$old_x/$new_w;
	$ratio2=$old_y/$new_h;

	$thumb_w = $new_w;
	$thumb_h = $new_h;
	if($new_w == 0)
		$thumb_w = $old_x/$ratio2;
	if($new_h == 0)
		$thumb_h = $old_y/$ratio1;

	if($new_w != 0 && $new_h != 0)
	{
		if($ratio1>$ratio2)	{
			$thumb_w=$new_w;
			$thumb_h=$old_y/$ratio1;
		}
		else	{
			$thumb_h=$new_h;
			$thumb_w=$old_x/$ratio2;
		}
	}

	// we create a new image with the new dimmensions
	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	// resize the big image to the new created one
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	// output the created image to the file. Now we will have the thumbnail into the file named by $filename
	if(!strcmp("image/png",$ext))
	{
		if(!imagepng($dst_img,$filename))
		{
			unlink($img_name);
			$sql = "DELETE from tImageData where mediaid = $mediaid";
			mysqli_query($con, $sql);
			$sql = "DELETE from tMediaMapping where mediaid = $mediaid";
			mysqli_query($con, $sql);
		}
	}
	if(!strcmp("image/gif",$ext))
	{
		if(!imagegif($dst_img,$filename))
		{
			unlink($img_name);
			$sql = "DELETE from tImageData where mediaid = $mediaid";
			mysqli_query($con, $sql);
			$sql = "DELETE from tMediaMapping where mediaid = $mediaid";
			mysqli_query($con, $sql);
		}
	}
	else
	{
		if(!imagejpeg($dst_img,$filename))
		{
			unlink($img_name);
			$sql = "DELETE from tImageData where mediaid = $mediaid";
			mysqli_query($con, $sql);
			$sql = "DELETE from tMediaMapping where mediaid = $mediaid";
			mysqli_query($con, $sql);
			//return("thumburl creation failed");
		}
	}
	//destroys source and destination images.
	imagedestroy($dst_img);
	imagedestroy($src_img);
}

function makeMultipleTwo ($value)
{
	$sType = gettype($value/2);
	if($sType == "integer")
	{
		return $value;
	} else {
		return ($value-1);
	}
}

// API to insert you tube video

function getTimeFromISOString($string){
	$string=substr($string, 2);//remove PT
	$minutes=explode('M', $string);
	$seconds=explode('S', $minutes[1]);
	return array('seconds'=>$seconds[0],'minutes'=>$minutes[0]);
}

function insertyoutubedata($arrayofurls,$iCount,$id,$typeofmedia){
	global $hostName;
	global $userName;
	global $password;

	$iFlag=1;
	$date=date("y.m.d");
	$VideosPath=MEDIA_BASE_PATH."/videos";
	//define('YOUTUBE_FEED_URL','https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&key=AIzaSyCPLPd7UjZLX-uHC_sXvfYbm3PIbtkd18U&id=');
	$YOUTUBE_FEED_URL='https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&key='.YOUTUBE_API_V3_KEY.'&id=';
	while($iFlag < $iCount){

		$videoUrl = $arrayofurls['url'.$iFlag];
		$parsedUrlArr = parse_url ( $videoUrl );
		
		parse_str ( $parsedUrlArr ['query'], $videoId );
		$jsonfilename = $VideosPath . "/" . $videoId['v']."_".$id .".json";
		
		

		if(trim($videoId['v']) == '')
			return("Invalid you tube url");
		if(preg_match ( '/youtube/', trim ( $parsedUrlArr ['host'])) || preg_match ( '/youtube/', trim ( $parsedUrlArr ['path'])))  {

			$url = $YOUTUBE_FEED_URL . $videoId ['v'];
			$out = fopen($jsonfilename,"w+");
			$handle=curl_init($url);
			curl_setopt($handle, CURLOPT_HEADER, 0);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
			$json=curl_exec($handle);
			
			fwrite($out, $json);
			fclose($out);
			//exec("/usr/bin/curl -o ". $jsonfilename ." ". $url);
			//$json = file_get_contents($jsonfilename);
			$returnurl = "https://www.youtube.com/v/" . $videoId['v'];
			
			if($json == "Invalid id")
				return "Invalid you tube url";

			$json_array = json_decode($json,true);

			if($json_array['pageInfo']['totalResults']<1){
				return "Invalid youtube video id";
			}
			else{
				$json_array=$json_array['items'][0];
			}

			$description = $arrayofurls['description'.$iFlag];
			$name = $arrayofurls['name'.$iFlag];

			
			$duration= getTimeFromISOString($json_array['contentDetails']['duration']);
			$duration=$duration["minutes"]*60+$duration['seconds'];

			$thumburl = $json_array['snippet']['thumbnails']['default']['url'];
			$thumburl_big = $json_array['snippet']['thumbnails']['medium']['url'];

			$conn = new mysqli($hostName,$userName,$password,"shiksha");

			if ($conn->connect_error) {
				echo "Failed to connect to MySQL: " .$conn->connect_error;
			  	die;
			}	


			// $con  = mysqli_connect($hostName, $userName, $password, "shiksha");

			// if (mysqli_connect_errno())
			//   {
			//   echo "Failed to connect to MySQL: " . mysqli_connect_error();
			//   die;
			//   }
			// mysql_select_db ("shiksha") OR DIE ("Unable to select db".mysql_error());

			$sql = "INSERT INTO tVideoData(mediaid,type,size,duration,bitrate,framerate,name,url,description,uploadeddate,thumburl,thumburl_big,source,filepath)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
						
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('isssisssssssss',$mediaid,$type,$size,$duration,$bitrate,$framerate,$name,$videoUrl,$description,$uploadeddate,$thumburl,$thumburl_big,$source,$jsonfilename);
			
			$mediaid="";
			$type="";
			$size = "";
			$bitrate="";
			$framerate="";
			$uploadeddate='now()';
			$source="youtube";

			$stmt->execute();

			if ($stmt->error){
				$stmt->close();
				$conn->close();
				return("file uploading failed");
			}
			
			/*if(!mysqli_query($con, $sql)){
				return("file uploading failed");
			}*/

			$mediaid = $stmt->insert_id;

			// $mediaid = mysqli_insert_id($con);

			$sql = "INSERT INTO tMediaMapping(typeid,mediaid,type,mediatype)values(?,?,?,?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('iiss',$id,$mediaid,$typeofmedia,$mediatype);
			$mediatype = 'video';
			$stmt->execute();

			if ($stmt->error){
				unlink($target_location);
				$delsql = "DELETE from tVideoData where mediaid = ?";
				$delStmt = $conn->prepare($sql);
				$delStmt->bind_param('i',$mediaid);
				$delStmt->execute();
				$delStmt->close();
				$stmt->close();
				$conn->close();
				return("File uploading failed");
			}

			$stmt->close();
			$conn->close();
				
			/*$sql = "INSERT INTO tMediaMapping(typeid,mediaid,type,mediatype)values('$id','$mediaid','$typeofmedia','video')";
			
			if(!mysqli_query($con, $sql)){
				//Delete the saved file
				unlink($target_location);
				unlink($filepath);
				$delsql = "DELETE from tVideoData where mediaid = $mediaid";
				mysqli_query($con, $delsql);
				return("file uploading failed");
			}
	*/
			$iSuccess = 1;
			$returnarray[$iFlag-1]['mediaid'] = $mediaid;
			$returnarray[$iFlag-1]['videourl']= $returnurl;
			$returnarray[$iFlag-1]['thumburl']= $thumburl;
			$returnarray[$iFlag-1]["status"] = 1;
		}

		$iFlag = $iFlag + 1;

		if(!$iSuccess){
			return("file uploading failed.Please try again");
		}
		else{
			$returnarray["status"] = 1;
		}

	}
	echo serialize($returnarray);

}

function insertyoutubedataV2($arrayofurls,$iCount,$id,$typeofmedia)
{

        global $hostName;
        global $userName;
        global $password;

        $iFlag = 1;
		$date = date("y.m.d");
		$VideosPath = MEDIA_BASE_PATH."/videos";
		define('YOUTUBE_FEED_URL','http://gdata.youtube.com/feeds/api/videos/');
		while($iFlag < $iCount)
		{
		$videoUrl = $arrayofurls['url'.$iFlag];
		error_log($videoUrl.'URL');
		$parsedUrlArr = parse_url ( $videoUrl );
		parse_str ( $parsedUrlArr ['query'], $videoId );
		$xmlfilename = $VideosPath . "/" . $videoId['v']."_".$id .".xml";
		if(trim($videoId['v']) == '')
			return("Invalid you tube url");
		if(preg_match ( '/youtube/', trim ( $parsedUrlArr ['host'])) || preg_match ( '/youtube/', trim ( $parsedUrlArr ['path'])))  {
			$url = YOUTUBE_FEED_URL . $videoId ['v'];
			error_log($url.'FINALURl');
			error_log($xmlfilename.'XMLFILENAME');
			error_log("/usr/bin/curl -o ". $xmlfilename ." ". $url);
			exec("/usr/bin/curl -o ". $xmlfilename ." ". $url);
			$returnurl = "http://www.youtube.com/v/" . $videoId['v'];
			$XML = file_get_contents($xmlfilename);
			if($XML == "Invalid id")
			return "Invalid you tube url";
			$xml_array = returnXMLarray($XML);
		//	$description = $xml_array['ENTRY']['TITLE']['content'];
			$description = $arrayofurls['description'.$iFlag];
			$name = $arrayofurls['name'.$iFlag];
			$duration = $xml_array['ENTRY']['MEDIA:GROUP']['YT:DURATION']['SECONDS'];
			//120 * 90
			$thumburl = $xml_array['ENTRY']['MEDIA:GROUP']['MEDIA:THUMBNAIL'][0]['URL'];
			//320 * 240
			$i = 0;
			$thumburl_big = '';
			while($i < count($xml_array['ENTRY']['MEDIA:GROUP']['MEDIA:THUMBNAIL']))
			{
				if($xml_array['ENTRY']['MEDIA:GROUP']['MEDIA:THUMBNAIL'][$i]['WIDTH'] == 320 && $xml_array['ENTRY']['MEDIA:GROUP']['MEDIA:THUMBNAIL'][$i]['HEIGHT'] == 240)
					$thumburl_big = $xml_array['ENTRY']['MEDIA:GROUP']['MEDIA:THUMBNAIL'][3]['URL'];
			$i++;
			}
			 $con = mysqli_connect($hostName, $userName, $password, "shiksha");
			// mysql_select_db ("shiksha") OR DIE ("Unable to select db".mysql_error());

			$sql = "INSERT INTO tVideoData(mediaid,type,size,duration,bitrate,framerate,name,url,description,uploadeddate,thumburl,thumburl_big,source,filepath)VALUES('', '', '','$duration','','','$name','$videoUrl','$description',now(),'$thumburl','$thumburl_big','youtube','$xmlfilename')";
			error_log($sql);
			if(!mysqli_query($con, $sql))
			{
				return("file uploading failed");
			}

			$mediaid = mysqli_insert_id($con);

			$sql = "INSERT INTO tMediaMapping(typeid,mediaid,type,mediatype)values('$id','$mediaid','$typeofmedia','video')";
			error_log($sql);
			if(!mysqli_query($con, $sql))
			{
				//Delete the saved file
				unlink($target_location);
				unlink($filepath);
				$delsql = "DELETE from tVideoData where mediaid = $mediaid";
				mysqli_query($con, $delsql);
				return("file uploading failed");
			}
			$iSuccess = 1;
			$returnarray[$iFlag-1]['mediaid'] = $mediaid;
			$returnarray[$iFlag-1]['videourl']= $returnurl;
			$returnarray[$iFlag-1]['thumburl']= $thumburl;
		}

			$iFlag = $iFlag + 1;
		if(!$iSuccess)
			{ return("file uploading failed.Please try again");


	                }
		else{
			$returnarray["status"] = 1;

	        }
		//echo serialize($returnarray);

		}
		echo serialize($returnarray);
}

//API to insert video
function insertvideo($arrayofdescription,$iCount,$id,$typeofmedia)
{

    global $hostName;
    global $userName;
    global $password;

	if($_FILES['file1']['tmp_name'] == '')
	return ("File uploading failed");
	if(!checksize($iCount,MAX_VIDEO_SIZE))
		return("Size limit of 10MB exceeded");
	$VideosPath = MEDIA_BASE_PATH."/videos";
	$iSuccess = 1;
	$iFlag = 1;
	$mediaid = 0;
	$date = date("y.m.d");
	while($iFlag <$iCount)
	{
		$type = $arrayofdescription['type'.$iFlag];
		if($_FILES['file'.$iFlag]['type'] != "application/octet-stream")
		{
			return("mpeg,mp4,avi,wmv,flv,swf types are supported");
		}
		if(!checkvideotype($type))
			return("mpeg,mp4,avi,wmv,flv,swf types are supported");
		$iFlag = $iFlag + 1;

		}

		$iFlag = 1;
		while($iFlag < $iCount)
		{
			$type = $arrayofdescription['type'.$iFlag];
			$ext = '';
			if($typeofmedia == "categoryselector" && $type == "application/x-shockwave-flash")
			{
				$ext = '.swf';
			}
			$name_id = time().basename($_FILES['file'.$iFlag]['tmp_name']).$ext;
			$target_location = $VideosPath.'/'.$name_id;
			$size = $_FILES['file'.$iFlag]['size'];
			if(!(move_uploaded_file($_FILES['file'.$iFlag]['tmp_name'],$target_location)))
			{
				$iSuccess = 0;
				break;
			}
			else
			{
				$Duration = '';
				$FrameRate = '';
				$BitRate = '';
				$name =  $_FILES['file'.$iFlag]['name'];
				$description = $arrayofdescription['description'.$iFlag];
				if($typeofmedia != "categoryselector")
				{
					$movie = new ffmpeg_movie($target_location);
					$srcWidth = makeMultipleTwo($movie->getFrameWidth());
					$srcHeight = makeMultipleTwo($movie->getFrameHeight());
					$srcAB = intval($movie->getAudioBitRate()/1000);
					//$srcAR = $movie->getAudioSampleRate();
					$srcAR = 22050;
					$Duration = $movie->getDuration();
					$FrameRate = $movie->getFrameRate();
					$BitRate = $movie->getBitRate();
					$frame = $movie->getFrame(1);
					$gd_image = $frame->togdimage();
					$image_name = $VideosPath."/thumb/thumb_".$name_id;
					$filepath = $VideosPath."/thumb/thumb_".$name_id;
					imagejpeg($gd_image,$image_name);
					$ffmpegPath = "/usr/local/bin/ffmpeg";
					$flvtool2Path = "/usr/local/bin/flvtool2";
					$srcFile = $target_location;
					$destFile = $target_location . ".flv";
					chmod($target_location,0777);
					exec($ffmpegPath . " -i " . $srcFile . " -ar " . $srcAR . " -ab " . $srcAB . " -f flv -s " . $srcWidth . "x" . $srcHeight . " " . $destFile);
				}
				$thumburl = "/mediadata/videos/thumb/thumb_".$name_id;
				$videourl = "/mediadata/videos/".$name_id;				
				//$thumburl = URL."/mdb_image/videos/thumb/thumb_".$name_id;
				//$videourl = URL."/mdb_image/videos/".$name_id;
			 
				$conn = new mysqli($hostName, $userName, $password, "shiksha");
				if ($conn->connect_error) {
				    die("Connection failed: " . $conn->connect_error);
				}

			// $con = mysqli_connect($hostName, $userName, $password, "shiksha");
			// mysql_select_db ("shiksha") OR DIE ("Unable to select db".mysql_error());

			$sql = "INSERT INTO tVideoData(mediaid,type,size,duration,bitrate,framerate,name,url,description,uploadeddate,thumburl,thumburl_big,source,filepath)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

			$stmt = $conn->prepare($sql);

			$stmt->bind_param('isssisssssssss',$mediaid,$type,$size,$Duration,$Bitrate,$Framerate,$name,$videourl,$description,$date,$thumburl,$thumburl_big,$source,$filepath);
			
			$mediaid="";
			$thumburl_big="";
			$source='shiksha';
			$filepath="youtube";

			$stmt->execute();

			if($stmt->error)
			{
				/*//Delete the saved file
				  unlink($target_location);
				  unlink($filepath);*/
				 $stmt->close();
				 $conn->close();
				return("file uploading failed");
			}

			$mediaid = $stmt->insert_id;

			$sql = "INSERT INTO tMediaMapping(typeid,mediaid,type,mediatype)values(?,?,?,?)";

			$stmt = $conn->prepare($sql);
			$stmt->bind_param('iiss',$id,$mediaid,$typeofmedia,$mediatype);
			$mediatype = 'video';
			$stmt->execute();

			
			if($stmt->error)
			{
				//Delete the saved file
				unlink($target_location);
				unlink($filepath);
				$delsql = "DELETE from tVideoData where mediaid = ?";
				$delStmt = $conn->prepare($sql);
				$delStmt->bind_param('i',$mediaid);
				$delStmt->execute();
				$delStmt->close();
				$stmt->close();
				$conn->close();
				return("file uploading failed");
			}
			$stmt->close();
			$conn->close();
				
			$returnarray[$iFlag-1]['mediaid'] = $mediaid;
			if($typeofmedia == "categoryselector" && $type == "application/x-shockwave-flash")
			$returnarray[$iFlag-1]['videourl'] = $videourl;
			else
			$returnarray[$iFlag-1]['videourl']= $videourl.".flv";
			$returnarray[$iFlag-1]['thumburl']= $thumburl;
		}

		$iFlag = $iFlag + 1;
	}
	if(!$iSuccess)
		return("file uploading failed.Please try again");
	else
		$returnarray["status"] = 1;
	echo serialize($returnarray);
}

//API to return array

function returnXMLarray($XML)
{
	$xml_parser = xml_parser_create();
                xml_parse_into_struct($xml_parser, $XML, $vals);
                xml_parser_free($xml_parser);
                $_tmp='';
                foreach ($vals as $xml_elem)
                {
                        $x_tag=$xml_elem['tag'];
                        $x_level=$xml_elem['level'];
                        $x_type=$xml_elem['type'];
                        if ($x_level!=1 && $x_type == 'close')
                        {
                                if (isset($multi_key[$x_tag][$x_level]))
                                        $multi_key[$x_tag][$x_level]=1;
                                else
                                        $multi_key[$x_tag][$x_level]=0;
                        }
                        if ($x_level!=1 && $x_type == 'complete')
                        {
                                if ($_tmp==$x_tag)
                                        $multi_key[$x_tag][$x_level]=1;
                                $_tmp=$x_tag;
                        }
                }
 	foreach ($vals as $xml_elem)
                {
                        $x_tag=$xml_elem['tag'];
                        $x_level=$xml_elem['level'];
                        $x_type=$xml_elem['type'];
                        if ($x_type == 'open')
                                $level[$x_level] = $x_tag;
                        $start_level = 1;
                        $php_stmt = '$xml_array';
                        if ($x_type=='close' && $x_level!=1)
                                $multi_key[$x_tag][$x_level]++;
                        while($start_level < $x_level)
                        {
                                $php_stmt .=
	'[$level['.$start_level.']]';
                                if
	(isset($multi_key[$level[$start_level]][$start_level]) &&
	$multi_key[$level[$start_level]][$start_level])
	                                        $php_stmt .=
	'['.($multi_key[$level[$start_level]][$start_level]-1).']';
	                                $start_level++;
	                        }
	                        $add='';
	                        if (isset($multi_key[$x_tag][$x_level]) &&
	$multi_key[$x_tag][$x_level] && ($x_type=='open' ||
	$x_type=='complete'))
	                        {
	                                if (!
	isset($multi_key2[$x_tag][$x_level]))
	                                        $multi_key2[$x_tag][$x_level]=0;
	                                else
	                                        $multi_key2[$x_tag][$x_level]++;

	$add='['.$multi_key2[$x_tag][$x_level].']';
	                        }
	                        if (isset($xml_elem['value']) &&
	trim($xml_elem['value'])!='' && !array_key_exists('attributes',
	$xml_elem))
	                        {
	                                if ($x_type == 'open')
	                                        $php_stmt_main=
	$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
	                                else
	                                        $php_stmt_main=
	$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
	                                eval($php_stmt_main);
	                        }
	                        if (array_key_exists('attributes',$xml_elem))
	                        {
	                                if (isset($xml_elem['value']))
	                                {
	                                        $php_stmt_main=
	$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
	                                        eval($php_stmt_main);
	                                }
	                                foreach ($xml_elem['attributes'] as
	$key=>$value)
	                                {
	                                        $php_stmt_att=
	$php_stmt.'[$x_tag]'.$add.'[$key] = $value;';
	                                        eval($php_stmt_att);
	                                }


	  }
	                }
	return $xml_array;
}

//API to insert audio
function insertaudio($arrayofdescription,$iCount,$id,$typeofmedia)
{
        global $hostName;
        global $userName;
        global $password;
		if(!checksize($iCount,MAX_AUDIO_SIZE))
			return("Size limit of 5MB exceeded");
		$AudioPath = MEDIA_BASE_PATH."/audios";
		//$AudioPath = "/var/www/mdb_image/server/audios/";
		//if(!is_dir($AudioPath))
		//mkdir($AudioPath,0777);
		//chmod($AudioPath,0777);
		$iSuccess = 1;
		$iFlag = 1;
		$mediaid = 0;
		$date = date("y.m.d");

		while($iFlag < $iCount)
		{

			$name_id = time().basename($_FILES['file'.$iFlag]['tmp_name']);
			$target_location = $AudioPath.$name_id;
			$size = $_FILES['file'.$iFlag]['size'];
			$type = $_FILES['file'.$iFlag]['type'];
			if(!(move_uploaded_file($_FILES['file'.$iFlag]['tmp_name'],$target_location)))
			{
				$iSuccess = 0;
				break;
			}
			else
			{
				$name =  $_FILES['file'.$iFlag]['name'];
				$description = $arrayofdescription['description'.$iFlag];
				$movie = new ffmpeg_movie($target_location);
				$Duration = $movie->getDuration();
				$FrameRate = $movie->getFrameRate();
				$BitRate = $movie->getBitRate();
				$Artist = $movie->getArtist();
				$Genre = $movie->getGenre();

				$audiourl = "/mediadata/audios/".$name_id;

				//$audiourl = URL."/mdb_image/audios/".$name_id;
				
				$conn = new mysqli($hostName, $userName, $password, "shiksha");

				if ($conn->connect_error) {
			    	die("Connection failed: " . $conn->connect_error);
				}

				/*$con = mysqli_connect($hostName, $userName, $password, "shiksha");
				if (mysqli_connect_errno())
				{
					echo "Failed to connect to MySQL: " . mysqli_connect_error();
					die;
				}*/
				// mysql_select_db ("shiksha") OR DIE ("Unable to select db".mysql_error());
				//mysql_connect("localhost", "shiksha", "shiKm7Iv80l") OR DIE (mysql_error());
				//mysql_select_db ("shiksha") OR DIE ("Unable to select db".mysql_error());

				$sql = "INSERT INTO tAudioData ( mediaid , type ,size,duration,bitrate, 				name,url,description,uploadeddate,artist,genre)
					VALUES
					(?,?,?,?,?,?,?,?,?,?,?)";

				$stmt = $conn->prepare($sql);
				$mediaid = '';
				$stmt->bind_param('isssissssss',$mediaid,$type,$size,$Duration,$BitRate,$name,$audiourl,$description,$date,$Artist,$Genre);

				$stmt->execute();

				if($stmt->error)
				{
					//Delete the saved file
					unlink($target_location);
					$stmt->close();
					$conn->close();
					return("file uploading failed");
				}

				$mediaid = $stmt->insert_id;


				$sql = "INSERT INTO tMediaMapping(typeid,mediaid,type,mediatype)values(?,?,?,?)";

				$stmt = $conn->prepare($sql);
				$stmt->bind_param('iiss',$id,$mediaid,$typeofmedia,$mediatype);
				$mediatype = 'audio';
				$stmt->execute();

				
				if($stmt->error)
				{
					//Delete the saved file
					unlink($target_location);
					$delsql = "DELETE from tAudioData where mediaid = ?";
					$delStmt = $conn->prepare($sql);
					$delStmt->bind_param('i',$mediaid);
					$delStmt->execute();
					$delStmt->close();
					$stmt->close();
					$conn->close();
					return("file uploading failed");
				}
				$stmt->close();
				$conn->close();
				
		/*			if(!mysqli_query($con, $sql))
				{
					//Delete the saved file
					unlink($target_location);
					$delsql = "DELETE from tAudioData where mediaid = $mediaid";
					mysqli_query($con, $delsql);
					return("file uploading failed");
				}
		*/
				$returnarray[$iFlag-1]['mediaid'] = $mediaid;
				$returnarray[$iFlag-1]['audiourl']= $audiourl;
			}

			$iFlag = $iFlag + 1;
		}
		if(!$iSuccess)
			return("file uploading failed.Please try again");
		else
			$returnarray["status"] = 1;
		echo serialize($returnarray);
}

function insertrecording($arrayofdescription,$iCount,$id,$typeofmedia){
	$AudioPath = MEDIA_BASE_PATH."/audios/consultantStudentRecordings/";
	$iSuccess = 1;
	$iFlag = 1;
	$name_id = time().basename($_FILES['file'.$iFlag]['tmp_name']);
	$target_location = $AudioPath.$name_id.".mp3";
	$size = $_FILES['file'.$iFlag]['size'];
	$type = $_FILES['file'.$iFlag]['type'];
	
	if(!(move_uploaded_file($_FILES['file'.$iFlag]['tmp_name'],$target_location))){
		$iSuccess = 0;
		return array('status'=>'error','errMsg'=>"File Upload Failed; Please Try Again");
	}
	else{
		return array('status'=>'success','url'=>"/mediadata/audios/consultantStudentRecordings/".$name_id.".mp3");
	}
}

function insertSpliceMedia($arrayofdescription,$iCount,$id,$typeofmedia,$folderPath='splice'){
    global $hostName;
    global $userName;
    global $password;
	$PdfPath =	 MEDIA_BASE_PATH."/".$folderPath."/";		
	$iSuccess = 1;
	$iFlag = 1;
	$mediaid = 0;
	$date = date("y.m.d");
	$TypeFlag = 1;
	while($iFlag <$iCount)
	{
		$type = $arrayofdescription['type'.$iFlag];
		
		$inputDocType = explode('/',$type);
		
		if($inputDocType[0] =='image'){
			$isTypeValid = checktype($type);
		}else{			
			$isTypeValid = checkDocumentTypeForMedia($type);

			if($type == "application/octet-stream")
			{
				$originalNameArr = explode('.', $arrayofdescription['name'.$iFlag]);
				$ext  = $originalNameArr[count($originalNameArr)-1];
				if(!in_array($ext,array('webp','bmp','zip','zipx','7z','sxw','odf','odt','tar',"x-tar","rar"))){
					$isTypeValid = 0;
				}
			}
		}		
		
		if(!$isTypeValid)
			$TypeFlag = 0;
		$iFlag ++;
	}
	
	//error_log($TypeFlag);die;
	
	if($TypeFlag == 0) // && $typeofmedia =='uploadFromCRDashboard') now all cases allow same file types, therefore this condition is not required
		return ("Only pdf, ppt, pptx, doc, docx, xls, xlsx, txt, image,zip files are allowed");
	

	$iFlag = 1;
	while($iFlag < $iCount)
	{
		$name_id = time().basename($_FILES['file'.$iFlag]['tmp_name']);
		$target_location = $PdfPath.$name_id;
		$size = $_FILES['file'.$iFlag]['size'];
		$type = $_FILES['file'.$iFlag]['type'];
		$type = $arrayofdescription['type'.$iFlag];
		$originalName = $arrayofdescription['name'.$iFlag];

		$pdfurl = "/mediadata/".$folderPath."/".$name_id;
		$inputDocType = explode('/',$type);		
		if($inputDocType[0] =='image'){
			$target_location .= '.'.$inputDocType[1];
			$pdfurl .= '.'.$inputDocType[1];
		}else{			
			if($type == "application/msword")
			{
				$target_location .= '.doc';
				$pdfurl .= '.doc';
			}
			if($type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
			{
				$target_location .= '.docx';
				$pdfurl .= '.docx';
			}
			if($type == "text/plain")
			{
				$target_location .= '.txt';
				$pdfurl .= '.txt';
			}

			if($type == "text/csv")
			{
				$target_location .= '.csv';
				$pdfurl .= '.csv';
			}

			if($type == "application/x-rar")
			{
				$target_location .= '.rar';
				$pdfurl .= '.rar';
			}

			if($type == "text/html")
			{
				$target_location .= '.csv';
				$pdfurl .= '.csv';
			}
			
			if($type == "application/vnd.ms-powerpoint")
			{
				$target_location .= '.ppt';
				$pdfurl .= '.ppt';
			}

			if($type == "application/pdf" || $type == '"application/pdf"' || $type=="application/octectstream")
			{
				$target_location .= '.pdf';
				$pdfurl .= '.pdf';
			}
			if($type == "application/vnd.ms-excel" || $type == "application/xls")
			{
				$target_location .= '.xls';
				$pdfurl .= '.xls';
			}
			if($type == "application/postscript")
			{
				$target_location .= '.ps';
				$pdfurl .= '.ps';
			}
			if($type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
				$target_location .= '.xlsx';
				$pdfurl .= '.xlsx';
			}
			if($type == "application/vnd.openxmlformats-officedocument.presentationml.presentation"){
				$target_location .= '.pptx';
				$pdfurl .= '.pptx';
			}
			if($type == "application/vnd.oasis.opendocument.text"){
				$target_location .= '.odt';
				$pdfurl .= '.odt';
			}
			if($type == "application/vnd.sun.xml.writer"){
				$target_location .= '.sxw';
				$pdfurl .='.sxw';
			}
			
			if($type == "application/zip" || $type=="application/x-zip-compressed"){
				$target_location .= '.zip';
				$pdfurl .= '.zip';
			}
			if($type == "application/x-gzip"){
				$target_location .= '.tar.gz';
				$pdfurl .= '.tar.gz';
			}
			if($type == "application/x-tar"){
				$target_location .= '.tar';
				$pdfurl .= '.tar';
			}
			if($type == "application/tar"){
				$target_location .= '.tar';
				$pdfurl .= '.tar';
			}
			if($type == "application/gzip"){
				$target_location .= '.gz';
				$pdfurl .= '.gz';
			}
			if($type == "application/x-7z-compressed" || $type=="application/7z-compressed"){
				$target_location .= '.7z';
				$pdfurl .= '.7z';
			}
				
			if($type == "application/octet-stream"){
				$originalNameArr = explode('.', $originalName);
				$ext  = $originalNameArr[count($originalNameArr)-1];
				$target_location .= '.'.$ext;
				$pdfurl .= '.'.$ext;
			}
		}

		if(!(move_uploaded_file($_FILES['file'.$iFlag]['tmp_name'],$target_location)))
		{
			$iSuccess = 0;
			break;
		}
		else
		{
			$name =  $_FILES['file'.$iFlag]['name'];
			$description = $arrayofdescription['description'.$iFlag];			
			
			$conn = new mysqli($hostName, $userName, $password, "shiksha");
			/*$con = mysqli_connect($hostName, $userName, $password, "shiksha");

			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
				die;
			}*/
			// mysql_select_db ("shiksha") OR DIE ("Unable to select db".mysql_error());			

			$sql = "INSERT INTO tPdfData (mediaid,type,size,name,url,description,uploadeddate)
				VALUES
				(?,?,?,?,?,?,?)";

			$stmt = $conn->prepare($sql);
			$mediaid = "";
			$stmt->bind_param('issssss',$mediaid,$type,$size,$name,$pdfurl,$description,$date);
			$stmt->execute();

			if($stmt->error)
			{
				//Delete the saved file
				 $stmt->close();
				 $conn->close();
				
				unlink($target_location);
				return("file uploading failed pdfdata");
			}

			$mediaid = $stmt->insert_id;

			$sql = "INSERT INTO tMediaMapping(typeid,mediaid,type,mediatype)values(?,?,?,?)";

			$stmt = $conn->prepare($sql);
			$stmt->bind_param('iiss',$id,$mediaid,$typeofmedia,$mediatype);
			$mediatype = 'pdf';
			$stmt->execute();


			if($stmt->error)
			{
				//Delete the saved file
				unlink($target_location);
				$delsql = "DELETE from tPdfData where mediaid = ?";
				$delStmt = $conn->prepare($sql);
				$delStmt->bind_param('i',$mediaid);
				$delStmt->execute();
				$delStmt->close();
				$stmt->close();
				$conn->close();
				return("file uploading failed mediamapping");
			}
			$stmt->close();
			$conn->close();
				
			$returnarray[$iFlag-1]['mediaid'] = $mediaid;
			$returnarray[$iFlag-1]['pdfurl']= $pdfurl;
		}

		$iFlag = $iFlag + 1;
	}
	if(!$iSuccess)
		return("file uploading failed.Please try again");
	else
		$returnarray["status"] = 1;
	echo serialize($returnarray);

}


//API to insert pdf files
function insertdocument($arrayofdescription,$iCount,$id,$typeofmedia)
{
    global $hostName;
    global $userName;
    global $password;

    if($_FILES['file1']['tmp_name'] == '')
	return ("File uploading failed");
	$sizevar = '';
	
	if($typeofmedia == "university" || $typeofmedia == "abroadCourse" || $typeofmedia == "content" || $typeofmedia == "consultantStudentProfile" || $typeofmedia =='dockets'){
		define("MAX_PDF_SIZE","52428800");
		$sizevar = "50 Mb";
	}
	else if($typeofmedia == "institute" || $typeofmedia == "course" || $typeofmedia == 'exam' || $typeofmedia == "scholarship") {
		define("MAX_PDF_SIZE","26214400");
		$sizevar = "25 Mb";
	}
	else if($typeofmedia == "studentDocs")
	{
		define("MAX_PDF_SIZE","20971520");
		$sizevar = "20 MB";
	}
	else if($typeofmedia == "featured" || $typeofmedia == "notification" || $typeofmedia == "of_documents" || $typeofmedia == "career_documents" || $typeofmedia == 'cat_page_articles_widget'  || $typeofmedia == 'uploadFromCRDashboard')
	{
		define("MAX_PDF_SIZE","5242880");
		$sizevar = "5 Mb";
	}
	else
	{
		define("MAX_PDF_SIZE","2097152");
		$sizevar = "2 Mb";
	}
		
	if(!checksize($iCount,MAX_PDF_SIZE))
		return("Size limit of ".$sizevar." exceeded");

	if($typeofmedia =='of_documents') {
		$PdfPath = MEDIA_BASE_PATH."/onlineforms/";
	}else {
		$PdfPath = MEDIA_BASE_PATH."/pdf/";
	}
	if($typeofmedia =='career_documents'){
		$PdfPath = MEDIA_BASE_PATH."/career/";
	}
	if($typeofmedia =='uploadFromCRDashboard'){
		$PdfPath = MEDIA_BASE_PATH."/CampusAmbassador/";
	}
	if($typeofmedia =='shikshaApplyPDF' || $typeofmedia =='dockets'){
		$PdfPath = MEDIA_BASE_PATH."/shikshaApply/pdf/";
	}
	
	//$PdfPath = "/var/www/mdb_image/server/pdf/";
	//if(!is_dir($PdfPath))
	//mkdir($PdfPath,0777);
	//chmod($PdfPath,0777);

	$iSuccess = 1;
	$iFlag = 1;
	$mediaid = 0;
	$date = date("y.m.d");
	$TypeFlag = 0;
	while($iFlag <$iCount)
	{
		$type = $arrayofdescription['type'.$iFlag];
		if(checkDocumenttype($type)){
            $TypeFlag = 1;
            if($type == "application/octet-stream")
			{
				$originalNameArr = explode('.', $arrayofdescription['name'.$iFlag]);
				$ext  = $originalNameArr[count($originalNameArr)-1];
				if(!in_array($ext,array("msg"))){
					$TypeFlag = 0;
				}
			}
        }
		else
			$TypeFlag = 0;

		$iFlag ++;
	}
	
	if($TypeFlag == 0) // && $typeofmedia =='uploadFromCRDashboard') now all cases allow same file types, therefore this condition is not required
		return ("Only pdf, ppt, pptx, doc, docx, xls, xlsx, txt, msg files are allowed");
	

	$iFlag = 1;
	while($iFlag < $iCount)
	{

		$name_id = time().basename($_FILES['file'.$iFlag]['tmp_name']);
		$target_location = $PdfPath.$name_id;
		$size = $_FILES['file'.$iFlag]['size'];
		$type = $_FILES['file'.$iFlag]['type'];
		$type = $arrayofdescription['type'.$iFlag];

		if($typeofmedia =='of_documents') {
			$pdfurl = "/mediadata/onlineforms/".$name_id;
		}else {
			$pdfurl = "/mediadata/pdf/".$name_id;
		} 

		if($typeofmedia =='career_documents') {
			$pdfurl = "/mediadata/career/".$name_id;
		}
		
		if($typeofmedia =='uploadFromCRDashboard') {
			$pdfurl = "/mediadata/CampusAmbassador/".$name_id;
		}
		
		if($typeofmedia =='shikshaApplyPDF'|| $typeofmedia =='dockets') {
			$pdfurl = "/mediadata/shikshaApply/pdf/".$name_id;
		}

		if($type == "application/msword")
		{
			$target_location .= '.doc';
			$pdfurl .= '.doc';
		}
		if($type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
		{
			$target_location .= '.docx';
			$pdfurl .= '.docx';
		}
		if($type == "text/plain")
		{
			$target_location .= '.txt';
			$pdfurl .= '.txt';
		}

		if($type == "text/csv")
		{
			$target_location .= '.csv';
			$pdfurl .= '.csv';
		}
		
		if($type == "application/vnd.ms-powerpoint")
		{
			$target_location .= '.ppt';
			$pdfurl .= '.ppt';
		}

		if($type == "application/pdf" || $type == '"application/pdf"')
		{
			$target_location .= '.pdf';
			$pdfurl .= '.pdf';
		}
		if($type == "application/vnd.ms-excel")
		{
			$target_location .= '.xls';
			$pdfurl .= '.xls';
		}
		if($type == "application/postscript")
		{
			$target_location .= '.ps';
			$pdfurl .= '.ps';
		}
		if($type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
			$target_location .= '.xlsx';
			$pdfurl .= '.xlsx';
		}
		if($type == "application/vnd.openxmlformats-officedocument.presentationml.presentation"){
			$target_location .= '.pptx';
			$pdfurl .= '.pptx';			
		}
		if($type=='application/vnd.ms-outlook'){
			$target_location .= '.msg';
			$pdfurl .= '.msg';			
		}
		if($type == "application/octet-stream"){
		    $originalNameArr = explode('.', $arrayofdescription['name'.$iFlag]);
		    $ext  = $originalNameArr[count($originalNameArr)-1];
		    $target_location .= '.'.$ext;
		    $pdfurl .= '.'.$ext;
		}
		if(!(move_uploaded_file($_FILES['file'.$iFlag]['tmp_name'],$target_location)))
		{
			$iSuccess = 0;
			break;
		}
		else
		{
			$name =  $_FILES['file'.$iFlag]['name'];
			$description = $arrayofdescription['description'.$iFlag];

			//$pdfurl = URL."/mdb_image/Pdf/".$name_id;
            //$hostName = '172.16.3.108';
			$conn = new mysqli($hostName, $userName, $password, "shiksha");
			
			/*
			$con = mysqli_connect($hostName, $userName, $password, "shiksha");
			
			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
				die;
			}*/
			//mysql_connect("localhost", "shiksha", "shiKm7Iv80l") OR DIE (mysql_error());
			//mysql_select_db ("shiksha") OR DIE ("Unable to select db".mysql_error());

			$sql = "INSERT INTO tPdfData ( mediaid , type ,size,name,url,description,uploadeddate)
				VALUES
				(?,?,?,?,?,?,?)";

			$mediaid = "";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("issssss",$mediaid,$type,$size,$name,$pdfurl,$description,$date);
			$stmt->execute();


			if($stmt->error)
			{
				//Delete the saved file
				$stmt->close();
				$conn->close();
				unlink($target_location);
				return("file uploading failed pdfdata");
			}

			$mediaid = $stmt->insert_id;


			$sql = "INSERT INTO tMediaMapping(typeid,mediaid,type,mediatype)values(?,?,?,?)";

			$stmt = $conn->prepare($sql);
			$stmt->bind_param('iiss',$id,$mediaid,$typeofmedia,$mediatype);
			$mediatype = 'pdf';
			$stmt->execute();


			if($stmt->error)
			{
				//Delete the saved file
				unlink($target_location);
				$delsql = "DELETE from tPdfData where mediaid = ?";
				$delStmt = $conn->prepare($sql);
				$delStmt->bind_param('i',$mediaid);
				$delStmt->execute();
				$delStmt->close();
				$stmt->close();
				$conn->close();
				return("file uploading failed mediamapping");
			}

			$stmt->close();
			$conn->close();
				
			$returnarray[$iFlag-1]['mediaid'] = $mediaid;
			$returnarray[$iFlag-1]['pdfurl']= $pdfurl;
            $returnarray[$iFlag-1]['name'] = $arrayofdescription['name'.$iFlag];
		}

		$iFlag = $iFlag + 1;
	}
	if(!$iSuccess)
		return("file uploading failed.Please try again");
	else
		$returnarray["status"] = 1;
	echo serialize($returnarray);
}
?>
