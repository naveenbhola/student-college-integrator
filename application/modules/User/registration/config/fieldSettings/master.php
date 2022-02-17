<?php
if(PREF_YEAR_MANDATORY) {
    $prefYear = array(
        'type' => 'select',
        'label' => 'Preferred Year of Admission',
        'caption' => 'Preferred Year of Admission',
        'validations' => 'Mandatory'
    );
} else {
    $prefYear = array(
        'type' => 'select',
        'label' => 'Preferred Year of Admission',
        'caption' => 'Preferred Year of Admission'
    );
}

$masterFieldSettings = array(
    'fieldOfInterest' => array(
        'type' => 'select',
        'label' => 'Education Interest',
        'caption' => 'desired education of interest'
    ),
    'desiredCourse' => array(
        'type' => 'select',
        'label' => 'Desired Course',
        'caption' => 'the desired course'
    ),
    'mode' => array(
        'type' => 'checkbox',
        'label' => 'Mode',
        'caption' => 'preferred mode of learning'
    ),
    'preferredStudyLocation' => array(
        'type' => 'select',
        'label' => 'Preferred Study Location(s)',
        'caption' => 'preferred study location(s)'
    ),
    'preferredStudyLocality' => array(
        'type' => 'select',
        'label' => 'Preferred Localities for Studies',
        'caption' => 'preferred locality'
    ),
    'destinationCountry' => array(
        'type' => 'layer',
        'label' => 'Destination Country(s)',
        'caption' => 'destination country(s)'
    ),
    'degreePreference' => array(
        'type' => 'checkbox',
        'label' => 'Degree Preference',
        'caption' => 'your degree preferences'
    ),
    'whenPlanToStart' => array(
        'type' => 'select',
        'label' => 'When do you plan to start?',
        'caption' => 'when you plan to start the course'
    ),
    'whenPlanToGo' => array(
        'type' => 'select',
        'label' => 'When do you plan to go?',
        'caption' => 'when you plan to start the course'
    ),
    'desiredGraduationLevel' => array(
        'type' => 'radio',
        'label' => 'Desired Graduation Level',
        'caption' => 'the desired course level'
    ),
    'graduationStatus' => array(
        'type' => 'radio',
        'label' => 'Graduation Status',
        'caption' => 'your graduation status',
        'validations' => 'GraduationCompletionDate'
    ),
    'graduationDetails' => array(
        'type' => 'select',
        'label' => 'Graduation Details',
        'caption' => 'your graduation course'
    ),
    'graduationMarks' => array(
        'type' => 'select',
        'label' => 'Marks',
        'caption' => 'your aggregate marks in graduation'
    ),
    'graduationCompletionYear' => array(
        'type' => 'select',
        'label' => 'Graduation Completion Year',
        'caption' => 'Graduation completion year',
        'validations' => 'GraduationCompletionDate'
    ),
    'xiiStream' => array(
        'type' => 'radio',
        'label' => 'XII Stream',
        'caption' => 'XII std stream'
    ),
    'xiiYear' => array(
        'type' => 'select',
        'label' => 'XII Year',
        'caption' => 'XII completion year'
    ),
    'xiiMarks' => array(
        'type' => 'select',
        'label' => 'XII Marks',
        'caption' => 'marks'
    ),
    'workExperience' => array(
        'type' => 'select',
        'label' => 'Work Experience',
        'caption' => 'your years of experience'
    ),
    'residenceCity' => array(
        'type' => 'select',
        'label' => 'Residence Location',
        'caption' => 'City of Residence'
    ),
    'exams' => array(
        'label' => 'Exams',
        'type' => 'checkbox',
        'caption' => 'exams taken',
        'validations' => 'AllExamScores'
    ),
    'examsAbroad' => array(
        'label' => 'Exams',
        'type' => 'checkbox',
        'caption' => 'at least one exam',
        'validations' => 'AllAbroadExamScores'
    ),
    'bookedExamDate' => array(
        'type' => 'radio',
        'label' => 'Booked exam date',
        'caption' => 'Booked exam date'
    ),
    'callPreference' => array(
        'type' => 'select',
        'label' => 'Call Preference',
        'caption' => 'call preference'
    ),
    'fund' => array(
        'type' => 'checkbox',
        'label' => 'Funding Details',
        'caption' => 'your source(s) of funding',
    ),
    'firstName' => array(
        'type' => 'textbox',
        'label' => 'first name',
        'caption' => 'your First name',
        'validations' => 'DisplayName'
    ),
    'lastName' => array(
        'type' => 'textbox',
        'label' => 'last name',
        'caption' => 'your Last name',
        'validations' => 'DisplayName'
    ),
    'email' => array(
        'type' => 'textbox',
        'label' => 'email',
        'caption' => 'your Email address',
        'validations' => 'Email'
    ),
    'mobile' => array(
        'type' => 'textbox',
        'label' => 'Mobile',
        'caption' => 'your Mobile number',
        'validations' => 'Mobile'
    ),
    'securityCode' => array(
        'type' => 'textbox',
        'label' => 'Security Code',
        'caption' => 'the Security Code as shown in the image',
    ),
    'agreeToTS' => array(
        'type' => 'checkbox',
        'label' => 'Agree to terms of service',
        'caption' => 'agree to terms of service and privacy policy'
    ),
    'specialization' => array(
        'type' => 'select',
        'label' => 'Specialization',
        'caption' => 'Specialization'
    ),
    'password' => array(
        'type' => 'textbox',
        'label' => 'Password',
        'caption' => 'Password',
        'validations' => 'Password'
    ),
    'confirmPassword' => array(
        'type' => 'textbox',
        'label' => 'Confirm Password',
        'caption' => 'Confirm Password',
        'validations' => 'ConfirmPassword'
    ),
    'age' => array(
        'type' => 'textbox',
        'label' => 'Age',
        'caption' => 'age',
        'validations' => 'Age'
    ),
    'gender' => array(
        'type' => 'radio',
        'label' => 'Gender',
        'caption' => 'gender'
    ),
    'otherExams' => array(
        'type' => 'checkbox',
        'label' => 'Other Exams',
        'caption' => 'other exams'
    ),
    'otherDetails' => array(
        'type' => 'textarea',
        'label' => 'Other Details',
        'caption' => 'other details'
    ),
    'privacySettings' => array(
        'type' => 'checkbox',
        'label' => 'Privacy Settings',
        'caption' => 'privacy settings'
    ),
    'examTaken' => array(
        'type' => 'radio',
        'label' => 'Exam Taken',
        'caption' => 'whether you have given any exam'
    ),
    'passport' => array(
        'type' => 'radio',
        'label' => 'Passport',
        'caption' => 'whether you have a vaild passport'
    ),
    'abroadDesiredCourse' => array(
        'type' => 'radio',
        'label' => 'Desired Course',
        'caption' => 'desired course'
    ),
    'abroadSpecialization' => array(
        'type' => 'select',
        'label' => 'Specialization',
        'caption' => 'specialization'
    ),
    'budget' => array(
       'type' => 'select',
       'label' => 'Program Budget',
       'caption' => 'program budget'
    ),
    'contactByConsultant' => array(
        'type' => 'radio',
        'label' => 'Do you want to be contacted by a consultant?',
        'caption' => 'whether you want to be contacted by a consultant'
    ),
    'residenceLocality' => array(
        'type' => 'select',
        'label' => 'Residence Locality',
        'caption' => 'Nearest locality you live in'
    ),
    'residenceCityLocality' => array(
        'type' => 'select',
        'label' => 'Residence Location',
        'caption' => 'City you live in'
    ),
    'tenthBoard' => array(
        'type' => 'select',
        'label' => '10th Board',
        'caption' => '10th Board'
    ),
    'tenthmarks' => array(
        'type' => 'select',
        'label' => 'Class 10th marks, CGPA or max grade',
        'caption' => '10th marks, CGPA or max grade'
    ),
    'CurrentSubjects' => array(
        'type' => 'layer',
        'label' => 'What are your current class subjects?',
        'caption' => 'What are your current class subjects?'
    ),
    'currentClass' => array(
        'type' => 'select',
        'label' => 'Which class are you currently studying?',
        'caption' => 'Which class are you currently studying?'
    ),
    'currentSchool' => array(
        'type' => 'textbox',
        'label' => 'What is your current school name?',
        'caption' => 'your school name'
    ),
    'graduationStream' => array(
        'type' => 'select',
        'label' => 'Graduation stream',
        'caption' => 'Graduation stream'
    ),
    'workExperience' => array(
        'type' => 'select',
        'label' => 'Work experience',
        'caption' => 'Work experience'
    ),
    'country' => array(
        'type' => 'layer',
        'label' => 'Resident Country',
        'caption' => 'Resident country'
    ),
    'isdCode' => array(
        'type' => 'select',
        'label' => 'ISD Code',
        'caption' => 'ISD Code'
    ),
    'prefYear' => $prefYear,
    'stream' => array(
        'type' => 'select',
        'label' => 'Stream',
        'caption' => 'Stream',
        'validations' => 'Mandatory'  
    ),
    'educationType'=>array(
        'type' => 'layer',
        'label' => 'Mode',
        'caption' => 'Mode',
        'validations' => 'EducationType'        
    ),
    'subStreamSpecializations'=>array(
        'type' => 'layer',
        'label' => 'Sub-Stream & Specialization',
        'caption' => 'Sub-Stream & Specialization',
        'validations' => 'SubStreamSpecializations'  
    ),
    'baseCourses'=>array(
        'type' => 'layer',
        'label' => 'Shiksha Course',
        'caption' => 'Shiksha Course',
        'validations' => 'BaseCourses'        
    ),
    'flowChoice'=>array(
        'type' => 'layer',
        'label' => 'Flow Choice',
        'caption' => 'Flow Choice',
        'validations' => 'FlowChoice'       
    )
);
