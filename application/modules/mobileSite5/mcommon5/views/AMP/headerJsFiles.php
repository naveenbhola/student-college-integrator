<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>   
  <script async src="https://cdn.ampproject.org/v0.js"></script>
   
   <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
  <script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>   
  <script async custom-element="amp-access" src="https://cdn.ampproject.org/v0/amp-access-0.1.js"></script> 
  <script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.2.js"></script>
  <script async custom-element="amp-install-serviceworker" src="https://cdn.ampproject.org/v0/amp-install-serviceworker-0.1.js"></script>
  <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>

  <?php 
   if($pageType == 'coursePage')
   {
      $queryParams['courseId'] = $courseId;
      $queryParams['viewedResKey'] = $ampCourseViewedTrackingPageKeyId;
      $queryParams = http_build_query($queryParams);
    }
    elseif (in_array($pageType, array('institute','university'))) {
      $queryParams['listingId'] = $listingId;
      $queryParams = http_build_query($queryParams);
    }
    elseif($pageType == 'examPage'){
      $queryParams['courseId'] = $groupId;
      $queryParams['viewedResKey'] = $ampExamViewedTrackingPageKeyId;
      $queryParams['pageType'] = $pageType;
      $queryParams = http_build_query($queryParams);
    }
  ?>
   <script id="amp-access" type="application/json">
   {
	    "authorization": "<?php echo SHIKSHA_HOME;?>/mcommon5/MobileSiteHamburgerV2/ampAuthorization?<?=$queryParams;?>&rid=READER_ID&url=CANONICAL_URL&ref=DOCUMENT_REFERRER&_=RANDOM",
      "pingback": "<?php echo SHIKSHA_HOME;?>/mcommon5/MobileSiteHamburgerV2/ampAuthorization?<?=$queryParams;?>&rid=READER_ID&url=CANONICAL_URL&ref=DOCUMENT_REFERRER&_=RANDOM&callType=pingback",
      "noPingback":true,
      "login": {
        "sign-in": "<?php echo SHIKSHA_HOME;?>/muser5/UserActivityAMP/getLoginAmpPage?rid=READER_ID&url=CANONICAL_URL",
        "sign-out": "<?php echo SHIKSHA_HOME;?>/muser5/MobileUser/logout"
      },
      "authorizationFallbackResponse": {
          "error": true,
          "access": false,
          "subscriber": false,
          "validuser":false,
          "bMailed":false,
          "shortlisted":false,
          "compared":false,
          "contact":false,
          "GuideMailed":false,
          "examSubscribe":false
      }
   }
</script>

<?php 
foreach ($js as $key) {
    switch ($key) {
        case 'youtube':
          echo '<script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>';
            break;
        case 'carousel':
          echo '<script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>';
            break;
        case 'form':
          echo '<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>';
            break;
        case 'lightbox':
          echo '<script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>';
            break;
        case 'sticky':
          echo '<script async custom-element="amp-sticky-ad" src="https://cdn.ampproject.org/v0/amp-sticky-ad-1.0.js"></script>';
          break;
        case 'image-lightbox':
          echo '<script async custom-element="amp-image-lightbox" src="https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js"></script>';
            break;
        case 'iframe':
          echo '<script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>';
            break;
        case 'soundcloud':
          echo '<script async custom-element="amp-soundcloud" src="https://cdn.ampproject.org/v0/amp-soundcloud-0.1.js"></script>';
            break;
        case 'socialShare' :
          echo '<script async custom-element="amp-addthis" src="https://cdn.ampproject.org/v0/amp-addthis-0.1.js"></script>';
          break;
        case 'animation':
          echo '<script async custom-element="amp-animation" src="https://cdn.ampproject.org/v0/amp-animation-0.1.js"></script>';
          break;
        case 'scroll':
          echo '<script async custom-element="amp-position-observer" src="https://cdn.ampproject.org/v0/amp-position-observer-0.1.js"></script>';
          break;
        default:
          # code...
          break;
      }  
}

?>
<script async custom-element="amp-sticky-ad" src="https://cdn.ampproject.org/v0/amp-sticky-ad-1.0.js"></script>

<!-- <script async custom-element="amp-fit-text" src="https://cdn.ampproject.org/v0/amp-fit-text-0.1.js"></script>
<script async custom-element="amp-selector" src="https://cdn.ampproject.org/v0/amp-selector-0.1.js"></script> -->
<!-- <script async custom-element="amp-fit-text" src="https://cdn.ampproject.org/v0/amp-fit-text-0.1.js"></script> -->