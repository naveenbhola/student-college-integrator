<InstituteListing recordID="<?php echo $recordID;?>">
    <Description><?php echo $Description;?></Description>
    <ContactNumber><?php echo $ContactNumber;?></ContactNumber>
    <Email><?php echo $Email;?></Email>
    <Websiteaddress><?php echo $Websiteaddress;?></Websiteaddress>
    <?php
    echo "\n";
    if (count($location) > 0){

        foreach ($location as $location_details) {
            $m = 1;
            foreach ($location_details as $locationdetails) {
    ?>
            <Location recordID="<?php echo $m;?>">
                <CompleteAddress><?php echo $locationdetails['CompleteAddress'];?></CompleteAddress>
                <City><?php echo $locationdetails['City'];?></City>
                <Country><?php echo $locationdetails['Country'];?></Country>
            </Location>
    <?php
            $m++;
            }

        echo "\n";
        }
    }
    ?>
    <?php
    echo "\n";
    if (count($courses) > 0){
        foreach ($courses as $courses_details) {
            foreach ($courses_details as $coursesdetails) {
    ?>
    <CourseOffered recordID="<?php echo $coursesdetails['recordID'];?>" >
        <CourseName><?php echo $coursesdetails['CourseName'];?></CourseName>
        <Level><?php echo $coursesdetails['Level'];?></Level>
        <Type><?php echo $coursesdetails['Type'];?></Type>
        <CourseSubCategory><?php echo $coursesdetails['CourseSubCategory'];?></CourseSubCategory>
        <CourseCategory><?php echo $coursesdetails['CourseCategory'];?></CourseCategory>
        <Duration><?php echo $coursesdetails['Duration'];?></Duration>
        <CourseFee><?php echo $coursesdetails['CourseFee'];?></CourseFee>
        <Affiliatedto><?php echo $coursesdetails['Affiliation'] ;?></Affiliatedto>
        <ApprovedBy><?php echo $coursesdetails['ApprovedBy'] ;?></ApprovedBy>
        <Eligibility><?php echo $coursesdetails['Eligibility']; ?></Eligibility>
        <DATES>
                <STARTDATE><?php echo $coursesdetails['STARTDATE'];?></STARTDATE>
                <ENDDATE><?php echo $coursesdetails['ENDDATE'];?></ENDDATE>
        </DATES>
        <FormSubmissionDate><?php echo $coursesdetails['formSubmissionDate']; ?></FormSubmissionDate>
        <resultDeclarationDate><?php echo $coursesdetails['resultDeclarationDate']; ?></resultDeclarationDate>
        <ContactInfo><?php echo $coursesdetails['ContactInfo'];?></ContactInfo>
    </CourseOffered>
    <?php
            }
        echo "\n";
        }
    }
    echo "\n";
    ?>
</InstituteListing>
<?php echo "\n"; ?>
