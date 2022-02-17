<?php
class trackingpages
{
	//this function used for storing tracking info for desktop
function _pagetracking(&$displayData)
	{
		

		$temp = array();

		 if(isset($displayData['trackingcatID'])){
   			$temp['categoryId'] = $displayData['trackingcatID'];
   		} 
      if(isset($displayData['trackingsubCatID'] )) {
        $temp['subCategoryId'] = $displayData['trackingsubCatID'];
      } 
   		if(isset($displayData['trackingtype']))
   		{
   			$temp['type']=$displayData['trackingtype'];
   		}
   		if(isset($displayData['trackingexamName']))
   		{
   			$temp['examName']=$displayData['trackingexamName'];
   		}
   		if(isset($displayData['trackingkey']))
   		{	
   			if($displayData['trackingkey']=='')
   				$displayData['trackingkey']='All';
   			$temp['key']=$displayData['trackingkey'];
   		}
   		if(isset($displayData['trackingviewedUserId']))
   		{
   			$temp['viewedUserId']=$displayData['trackingviewedUserId'];
   		}
      if(isset($displayData['trackingcountryId']))
      {
        $temp['countryId']=$displayData['trackingcountryId'];
      }
      if(isset($displayData['trackingPaginationKey']))
      {
        $temp['trackingPaginationKey']=$displayData['trackingPaginationKey'];
      }
      
      if(isset($displayData['trackingcityID']))
      {
        $temp['cityId'] = $displayData['trackingcityID'];
      }
      if(isset($displayData['trackingstateID']))
      {
        $temp['stateId'] = $displayData['trackingstateID'];
      }
      if(isset($displayData['trackingLDBCourseId']))
      {
        $temp['LDBCourseId']=$displayData['trackingLDBCourseId'];
      }
      if(isset($displayData['trackingAuthorId']))
      {
        $temp['authorId'] = $displayData['trackingAuthorId'];
      }
     
      $this->compareInstitute($displayData,$temp);
		$displayData['beaconTrackData'] = array(
    'pageIdentifier' => $displayData['trackingpageIdentifier'],
    'pageEntityId' => isset($displayData['trackingpageNo'])?$displayData['trackingpageNo']:0,
    'extraData' => $temp	);
		
	}
  function compareInstitute(&$displayData,&$temp)
  {
    //receiving Institues Names
     if(isset($displayData['compareInstituteName1']))
        $temp['compareInstituteName1']=$displayData['compareInstituteName1'];

      if(isset($displayData['compareInstituteName2']))
        $temp['compareInstituteName2']=$displayData['compareInstituteName2'];

      if(isset($displayData['compareInstituteName3']))
        $temp['compareInstituteName1']=$displayData['compareInstituteName3'];

      if(isset($displayData['compareInstituteName4']))
        $temp['compareInstituteName1']=$displayData['compareInstituteName4'];

      //receiving Institues ID's

      if(isset($displayData['compareInstituteID1']))
        $temp['compareInstituteID1']=$displayData['compareInstituteID1'];
       if(isset($displayData['compareInstituteID2']))
        $temp['compareInstituteID2']=$displayData['compareInstituteID2'];
       if(isset($displayData['compareInstituteID3']))
        $temp['compareInstituteID3']=$displayData['compareInstituteID3'];
       if(isset($displayData['compareInstituteID4']))
        $temp['compareInstituteID4']=$displayData['compareInstituteID4'];

      //receiving Institues Course ID's

       if(isset($displayData['compareCourseID1']))
        $temp['compareCourseID1']=$displayData['compareCourseID1'];
       if(isset($displayData['compareCourseID2']))
        $temp['compareCourseID2']=$displayData['compareCourseID2'];
       if(isset($displayData['compareCourseID3']))
        $temp['compareCourseID3']=$displayData['compareCourseID3'];
       if(isset($displayData['compareCourseID4']))
        $temp['compareCourseID4']=$displayData['compareCourseID4'];

  }
	//this function used for storing tracking info for mobile

function getSourcePageName($tabselected)
{
$tabselected=(string)$tabselected;
switch($tabselected)
{
	case '0':
  case 'MsgBoard':
		return "cafeBuzzPage";
	case '1':
		return "qnaPage";
  case '3':
      return "qnaPage";
	case '4':
		return "myQnaPage";
	case '6':
		return "discussionPage";
	case '7':
			return "announcementPage";
}
}
//below function is used for storing page key values of cafeBuzz/QnA/Discussion/Announcement Pages
function gettingPageKey($tabselected,&$displayData)
{
$tabselected=(string)$tabselected;
switch($tabselected)
{
  case '0':
    $displayData['qtrackingPageKeyId']=70;
    $displayData['dtrackingPageKeyId']=71;
    $displayData['atrackingPageKeyId']=72;
    $displayData['anstrackingPageKeyId']=73;
    $displayData['ansctrackingPageKeyId']=74;
    $displayData['dctrackingPageKeyId']=75;
    $displayData['drtrackingPageKeyId']=76;
    $displayData['actrackingPageKeyId']=77;
    $displayData['artrackingPageKeyId']=78;
    $displayData['raqtrackingPageKeyId']=79;
    $displayData['raanstrackingPageKeyId']=80;
    $displayData['raansctrackingPageKeyId']=81;
    $displayData['radtrackingPageKeyId']=82;
    $displayData['radctrackingPageKeyId']=83;
    $displayData['radrtrackingPageKeyId']=84;
    $displayData['raatrackingPageKeyId']=85;
    $displayData['raactrackingPageKeyId']=86;
    $displayData['raartrackingPageKeyId']=87;
    $displayData['tupanstrackingPageKeyId']=88;
    $displayData['tdownanstrackingPageKeyId']=89;
    return;
  case '1':
    $displayData['qtrackingPageKeyId']=90;
    $displayData['dtrackingPageKeyId']=91;
    $displayData['atrackingPageKeyId']=92;
    $displayData['anstrackingPageKeyId']=93;
    return;
  case '3':
    $displayData['qtrackingPageKeyId']=94;
    $displayData['dtrackingPageKeyId']=95;
    $displayData['atrackingPageKeyId']=96;
    $displayData['anstrackingPageKeyId']=97;
  return;
  case '4':
    return ;
  case '6':
    $displayData['qtrackingPageKeyId']=98;
    $displayData['dtrackingPageKeyId']=99;
    $displayData['atrackingPageKeyId']=100;
    return;
  case '7':
    $displayData['qtrackingPageKeyId']=101;
    $displayData['dtrackingPageKeyId']=102;
    $displayData['atrackingPageKeyId']=103;
      return;
  /*case 'MsgBoard':
      return 'HomePage';*/

}

}
//below function is used for storing page key values of question/discussion/announcement Detail pages
function getDetailPageKeys($fromOthers,&$displayData)
{
  switch($fromOthers)
  {
    case 'user':
                $displayData['qtrackingPageKeyId']=104;
                $displayData['banstrackingPageKeyId']=105;
                $displayData['anstrackingPageKeyId']=106;
                $displayData['ansctrackingPageKeyId']=107;
                $displayData['raqtrackingPageKeyId']=108;
                $displayData['raanstrackingPageKeyId']=109;
                $displayData['raansctrackingPageKeyId']=110;
                $displayData['tupanstrackingPageKeyId']=111;
                $displayData['tdownanstrackingPageKeyId']=112;
                $displayData['bottomregtrackingPageKeyId']=113;
                return;
    case 'discussion':
                $displayData['qtrackingPageKeyId']=0;
                $displayData['dtrackingPageKeyId']=114;
                $displayData['atrackingPageKeyId']=0;
                $displayData['dctrackingPageKeyId']=115;
                $displayData['rtrackingPageKeyId']=116;
                $displayData['radtrackingPageKeyId']=117;
                $displayData['radctrackingPageKeyId']=118;
                $displayData['radrtrackingPageKeyId']=119;
                $displayData['tupdtrackingPageKeyId']=531;
                $displayData['tdowndtrackingPageKeyId']=532;
                $displayData['tupdctrackingPageKeyId'] = 533;
                $displayData['tdowndctrackingPageKeyId'] = 534;
                break;
    case 'announcement':
                $displayData['qtrackingPageKeyId']=120;
                $displayData['dtrackingPageKeyId']=121;
                $displayData['atrackingPageKeyId']=122;
                $displayData['actrackingPageKeyId']=123;
                $displayData['rtrackingPageKeyId']=124;
                $displayData['raatrackingPageKeyId']=125;
                $displayData['raactrackingPageKeyId']=126;
                $displayData['raartrackingPageKeyId']=127;
                break;
  }
}
//below function is used for storing page keys of User Detail Page.
function getUserDetailPageKeys($tabSelected,&$displayData)
{
  switch($tabSelected)
  {
      case 'all':
      case  '':
        $displayData['anstrackingPageKeyId']=128;
        $displayData['ansctrackingPageKeyId']=129;
        $displayData['dctrackingPageKeyId']=130;
        $displayData['drtrackingPageKeyId']=131;
        $displayData['actrackingPageKeyId']=132;
        $displayData['artrackingPageKeyId']=133;
        $displayData['raqtrackingPageKeyId']=134;
        $displayData['raanstrackingPageKeyId']=135;
        $displayData['raansctrackingPageKeyId']=136;
        $displayData['radtrackingPageKeyId']=137;
        $displayData['radctrackingPageKeyId']=138;
        $displayData['radrtrackingPageKeyId']=139;
        $displayData['raatrackingPageKeyId']=140;
        $displayData['raactrackingPageKeyId']=141;
        $displayData['raartrackingPageKeyId']=142;
        $displayData['tupanstrackingPageKeyId']=143;
        $displayData['tdownanstrackingPageKeyId']=144;
        break;
    case 'Question':
        $displayData['anstrackingPageKeyId']=145;
        $displayData['raqtrackingPageKeyId']=146;
        break;
    case 'Answer':
        $displayData['anstrackingPageKeyId']=147;
        $displayData['ansctrackingPageKeyId']=148;
        $displayData['raanstrackingPageKeyId']=149;
        $displayData['raansctrackingPageKeyId']=150;
        $displayData['tupanstrackingPageKeyId']=151;
        $displayData['tdownanstrackingPageKeyId']=152;
        break;
    case 'Comment':
        $displayData['anstrackingPageKeyId']=153;
        $displayData['ansctrackingPageKeyId']=154;
        $displayData['raanstrackingPageKeyId']=155;
        $displayData['raansctrackingPageKeyId']=156;
        break;
    case 'Discussion':
        $displayData['dctrackingPageKeyId']=157;
        $displayData['drtrackingPageKeyId']=158;
        $displayData['radtrackingPageKeyId']=159;
        $displayData['radctrackingPageKeyId']=160;
        $displayData['radrtrackingPageKeyId']=161;
        break;
    case 'Announcement':
        $displayData['actrackingPageKeyId']=162;
        $displayData['artrackingPageKeyId']=163;
        $displayData['raatrackingPageKeyId']=164;
        $displayData['raactrackingPageKeyId']=165;
        $displayData['raartrackingPageKeyId']=166;
        break;
   default:
        $this->gettingPageKey($tabSelected,$displayData);
        break;

  }

}

}
	?>