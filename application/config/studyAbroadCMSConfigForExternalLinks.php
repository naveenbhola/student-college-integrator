<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Define the Constants here with "ENT_SA_"(Entity of study abroad) prefix.
**/
/*
 * This file is no more in use. 
 * This was created for one time activity to find external 404 URLs
 * 
 */
$config['EXTERNAL_URLS']    = array(
                                'university'    => array(
                                                    'contactWebsiteLink'        => array(   'table'             => 'listing_contact_details',
                                                                                            'column'            => 'website',
                                                                                            'entityFieldName'   => 'listing_type_id',
                                                                                            'listing_type'      => 'university',
                                                                                            'status'            => 'live'
                                                                                        ),
                                                    'admissionWebsiteLink'      => array(   'table'             => 'listing_admission_contact_details',
                                                                                            'column'            => 'admission_website_url',
                                                                                            'entityFieldName'   => 'listing_type_id',
                                                                                            'listing_type'      => 'university',
                                                                                            'status'            => 'live'
                                                                                        ),
                                                    'departmentWebsiteLink'     => array(   'table'             => 'university_departments',
                                                                                            'column'            => 'department_website_url',
                                                                                            'entityFieldName'   => 'university_id',
                                                                                            'status'            => 'live'
                                                                                        ),
                                                    'accomodationWebsiteLink'   => array(   'table'             => 'university_campus_accommodation',
                                                                                            'column'            => 'accommodation_website_url',
                                                                                            'entityFieldName'   => 'university_id',
                                                                                            'status'            => 'live'
                                                                                        ),
                                                    'livingExpenseWebsiteLink'  => array(   'table'             => 'university_campus_accommodation',
                                                                                            'column'            => 'living_expense_website_url',
                                                                                            'entityFieldName'   => 'university_id',
                                                                                            'status'            => 'live'
                                                                                        ),
                                                    'campuseWebsteLink'         => array(   'table'             => 'university_campuses',
                                                                                            'column'            => 'campus_website_url',
                                                                                            'entityFieldName'   => 'university_id',
                                                                                            'status'            => 'live'
                                                                                        ),
                                                    'consultantWebsiteLink'     => array(   'table'             => 'listing_external_links',
                                                                                            'column'            => 'link',
                                                                                            'entityFieldName'   => 'listing_type_id',
                                                                                            'listing_type'      => 'university',
                                                                                            'link_type'         => 'india_consultants_page_link',
                                                                                            'status'            => 'live'
                                                                                        ),
                                                    'studentPageWebsiteLink'    => array(   'table'             => 'listing_external_links',
                                                                                            'column'            => 'link',
                                                                                            'entityFieldName'   => 'listing_type_id',
                                                                                            'listing_type'      => 'university',
                                                                                            'link_type'         => 'international_students_page_link',
                                                                                            'status'            => 'live'
                                                                                        )
                                                    ),
                                'institute'    => array(
                                                    'departmentWebsiteLink' => array(   'table'                 => 'listing_contact_details',
                                                                                        'column'                => 'website',
                                                                                        'entityFieldName'       => 'listing_type_id',
                                                                                        'listing_type'          => 'institute',
                                                                                        'status'                => 'live'
                                                                                    ),
                                                    'facultyWebsiteLink'    => array(   'table'                 => 'listing_external_links',
                                                                                        'column'                => 'link',
                                                                                        'entityFieldName'       => 'listing_type_id',
                                                                                        'listing_type'          => 'institute',
                                                                                        'status'                => 'live',
                                                                                        'link_type'             => 'FACULTY_PAGE'
                                                                                    ),
                                                    'alumniWebsiteLink'     => array(   'table'                 => 'listing_external_links',
                                                                                        'column'                => 'link',
                                                                                        'entityFieldName'       => 'listing_type_id',
                                                                                        'listing_type'          => 'institute',
                                                                                        'status'                => 'live',
                                                                                        'link_type'             => 'ALUMNI_PAGE'
                                                                                    )
                                                    ),
                                'course'        => array(
                                                    'courseWebsiteLink'                 => array(   'table'             => 'listing_contact_details',
                                                                                                    'column'            => 'website',
                                                                                                    'entityFieldName'   => 'listing_type_id',
                                                                                                    'listing_type'      => 'course',
                                                                                                    'status'            => 'live'
                                                                                                ),
                                                    'courseDurationWebsiteLink'         => array(   'table'             => 'listing_external_links',
                                                                                                    'column'            => 'link',
                                                                                                    'entityFieldName'   => 'listing_type_id',
                                                                                                    'listing_type'      => 'course',
                                                                                                    'status'            => 'live',
                                                                                                    'link_type'         => 'courseDurationLink'
                                                                                                ),
                                                    'applicationDeadlineWebsiteLink'    => array(   'table'             => 'listing_external_links',
                                                                                                    'column'            => 'link',
                                                                                                    'entityFieldName'   => 'listing_type_id',
                                                                                                    'listing_type'      => 'course',
                                                                                                    'status'            => 'live',
                                                                                                    'link_type'         => 'applicationDeadlineLink'
                                                                                                ),
                                                    'admissionWebsiteLink'              => array(   'table'             => 'listing_external_links',
                                                                                                    'column'            => 'link',
                                                                                                    'entityFieldName'   => 'listing_type_id',
                                                                                                    'listing_type'      => 'course',
                                                                                                    'status'            => 'live',
                                                                                                    'link_type'         => 'admissionWebsiteLink'
                                                                                                ),
                                                    'feesPageWebsiteLink'               => array(   'table'             => 'listing_external_links',
                                                                                                    'column'            => 'link',
                                                                                                    'entityFieldName'   => 'listing_type_id',
                                                                                                    'listing_type'      => 'course',
                                                                                                    'status'            => 'live',
                                                                                                    'link_type'         => 'feesPageLink'
                                                                                                ),
                                                    'scholarshipWebsiteLink'            => array(   'table'             => 'abroad_course_scholarship_mapping',
                                                                                                    'column'            => 'link',
                                                                                                    'entityFieldName'   => 'course_id',
                                                                                                    'status'            => 'live'
                                                                                                ),
                                                    'scholarshipCourseLevelWebsiteLink' => array(   'table'             => 'listing_external_links',
                                                                                                    'column'            => 'link',
                                                                                                    'entityFieldName'   => 'listing_type_id',
                                                                                                    'listing_type'      => 'course',
                                                                                                    'status'            => 'live',
                                                                                                    'link_type'         => 'scholarshipLinkCourseLevel'
                                                                                                ),
                                                    'scholarshipDepartmentLevelWebsiteLink' => array(   'table'             => 'listing_external_links',
                                                                                                        'column'            => 'link',
                                                                                                        'entityFieldName'   => 'listing_type_id',
                                                                                                        'listing_type'      => 'course',
                                                                                                        'status'            => 'live',
                                                                                                        'link_type'         => 'scholarshipLinkDeptLevel'
                                                                                                ),
                                                    'scholarshipUniversityLevelWebsiteLink' => array(   'table'             => 'listing_external_links',
                                                                                                        'column'            => 'link',
                                                                                                        'entityFieldName'   => 'listing_type_id',
                                                                                                        'listing_type'      => 'course',
                                                                                                        'status'            => 'live',
                                                                                                        'link_type'         => 'scholarshipLinkUniversityLevel'
                                                                                                ),
                                                    'careerServiceWebsiteLink'              => array(   'table'             => 'course_job_profile',
                                                                                                        'column'            => 'career_services_link',
                                                                                                        'entityFieldName'   => 'course_id',
                                                                                                        'status'            => 'live'
                                                                                                ),
                                                    'internshipWebsiteLink'                 => array(   'table'             => 'course_job_profile',
                                                                                                        'column'            => 'internships_link',
                                                                                                        'entityFieldName'   => 'course_id',
                                                                                                        'status'            => 'live'
                                                                                                ),
                                                    'facultyInformationWebsiteLink'         => array(   'table'             => 'listing_external_links',
                                                                                                        'column'            => 'link',
                                                                                                        'entityFieldName'   => 'listing_type_id',
                                                                                                        'listing_type'      => 'course',
                                                                                                        'status'            => 'live',
                                                                                                        'link_type'         => 'facultyInfoLink'
                                                                                                ),
                                                    'alumniWebsiteLink'                     => array(   'table'             => 'listing_external_links',
                                                                                                        'column'            => 'link',
                                                                                                        'entityFieldName'   => 'listing_type_id',
                                                                                                        'listing_type'      => 'course',
                                                                                                        'status'            => 'live',
                                                                                                        'link_type'         => 'alumniInfoLink'
                                                                                                ),
                                                    'faqWebsiteLink'                        => array(   'table'             => 'listing_external_links',
                                                                                                        'column'            => 'link',
                                                                                                        'entityFieldName'   => 'listing_type_id',
                                                                                                        'listing_type'      => 'course',
                                                                                                        'status'            => 'live',
                                                                                                        'link_type'         => 'faqLink'
                                                                                                )
                                                    ),
                                'snapshotcourse'=> array(
                                                    'courseWebsiteLink' => array(   'table'             => 'snapshot_courses',
                                                                                    'column'            => 'website_link',
                                                                                    'entityFieldName'   => 'course_id',
                                                                                    'status'            => 'live'
                                                                                )
                                                    )
                                );
