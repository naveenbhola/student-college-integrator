<?php
if(!empty($contactDetails['address'])){
    $address = $contactDetails['address'];
}

if(!empty($contactDetails['admission_contact_number'])){
    $phone = $contactDetails['admission_contact_number'];
}
else if(!empty($contactDetails['generic_contact_number'])){
    $phone = $contactDetails['generic_contact_number'];
}

if(!empty($contactDetails['admission_email'])){
    $email = $contactDetails['admission_email'];
}
else if(!empty($contactDetails['generic_email'])){
    $email = $contactDetails['generic_email'];
}

if(!empty($contactDetails['website_url'])){
    $website = $contactDetails['website_url'];
}

if(!empty($aggregateReviewData['aggregateRating'])){
    $ratingValue = $aggregateReviewData['aggregateRating'];
}

if(!empty($aggregateReviewData['totalReviewCount'])){
    $reviewCount = $aggregateReviewData['totalReviewCount'];
}

  if($listing_type == 'course') { 
    if(!empty($affiliatedUniversityName)) {
       $affiliatedUniversityName = "Affiliated to ".$affiliatedUniversityName;
    }
    else{
       $affiliatedUniversityName = htmlentities($name)." at ".htmlentities($instituteNameWithLocation);
    }

    ?>
  <script type="application/ld+json">
    {
    "@context" : "http://schema.org",
    "@type" : "Course",
    "name"  : "<?=htmlentities($name)?>",
    "description" : "<?=$affiliatedUniversityName?>",
    "provider" : 
    {
      "@type" : "CollegeOrUniversity",
      "name" : "<?=htmlentities($instituteName_contact)?>",
      "url"  : "<?=$website?>",
      "email" : "<?=$email?>",
      "telephone": "<?=$phone?>",
      "address" : "<?=$address?>"
      <?php
      if(!empty($aggregateReviewData) && $aggregateReviewData['totalReviewCount'] != 0 && $aggregateReviewData['aggregateRating'] != null){
      ?>
      ,"aggregateRating":
      {
        "@type" : "AggregateRating",
        "bestRating":"5",
        "ratingValue":"<?=$ratingValue?>",
        "reviewCount":"<?=$reviewCount?>",
        "worstRating":"1"
      }
      <?php } ?>
    }
  }
  </script>
  <?php }
  else { ?>
  <script type="application/ld+json">
  {
    "@context" : "http://schema.org",
    "@type" : "CollegeOrUniversity", 
    "name" : "<?=htmlentities($name)?>",
    "address" : "<?=$address?>",
    "email" : "<?=$email?>",
    "url" : "<?=$website?>",
    "telephone" : "<?=$phone?>"
    <?php
    if(!empty($aggregateReviewData) && $aggregateReviewData['totalReviewCount'] != 0 && $aggregateReviewData['aggregateRating'] != null){
    ?>
    ,"aggregateRating":
      {
        "@type" : "AggregateRating",
        "bestRating":"5",
        "ratingValue":"<?=$ratingValue?>",
        "reviewCount":"<?=$reviewCount?>",
        "worstRating":"1"
      }

    <?php } ?>
  }
  </script>

<?php }
?>