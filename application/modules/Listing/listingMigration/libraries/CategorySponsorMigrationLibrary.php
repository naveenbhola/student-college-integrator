<?php

class CategorySponsorMigrationLibrary
{
    function __construct()
    {
        $this->CI =& get_instance();

        $this->model = $this->CI->load->model('listingMigration/categorysponsormigrationmodel');
    }

    /**
     *
     * @param string $logFileName
     * @param        $messageToLog
     * @param int    $mode
     */
    private static function logMessage($logFileName = '', $messageToLog, $mode = FILE_APPEND)
    {
        if ($logFileName == '') {
            $logFileName = "./log" . date('Y-m-d H:i:s');
        }

        if (gettype($messageToLog) == 'string' && $messageToLog != '') { // in case of string
            file_put_contents($logFileName, "\n" . $messageToLog, $mode); // add a new line for the sake of clarity
        } else if (gettype($messageToLog) == 'array' && count($messageToLog) > 0) { // in case of array
            file_put_contents($logFileName, "\n" . print_r($messageToLog, true), $mode); // add a new line for the sake of clarity
        } else if (gettype($messageToLog) == 'object' && $messageToLog !== null) {
            file_put_contents($logFileName, "\n" . print_r($messageToLog, true), $mode); // add a new line for the sake of clarity
        }
    }

    /**
     * Action to migrate the category sponsor from the old tables :
     * <code>tlistingsubscription</code>, <code>tbannerlinks</code> to the new system containing tables
     * <code>category_subscription_criteria</code>, <code>category_banners</code>, <code>category_products</code>
     */
    public function migrateCategorySponsor()
    {
        $this->migrateStickyListings();
        $this->migrateBanners();
    }

    /**
     * Migrate the sticky listings and save the criteria in the process
     *
     * @return array
     */
    private function migrateStickyListings()
    {

        $stickyListings = $this->model->getActiveData('stickyListing'); // tlistingsubscription
        $stickyListings = $stickyListings['data'];

        $logFileName    = "/tmp/sticky-listing-migration-log" . date('Y-m-d');
        $initialMessage = "\n\n\nStarting Migrating Sticky Listing.\nCreating Criteria...";
        CategorySponsorMigrationLibrary::logMessage($logFileName, $initialMessage);

        $TIER = 1;

        foreach ($stickyListings as $oneActiveSubscription) {
            CategorySponsorMigrationLibrary::logMessage($logFileName, "Active sticky listing in question: ");
            CategorySponsorMigrationLibrary::logMessage($logFileName, $oneActiveSubscription);

            $subcategoryId = $oneActiveSubscription->subcategory;
            $categoryId    = $oneActiveSubscription->categoryid;

            if ($categoryId != 0) {
                $newCategoryInformation = $this->model->findCategorySubcategoryMapping($categoryId, $subcategoryId); // base_entity_mapping
            } else {
                $newCategoryInformation = $this->model->findSubcategoryMapping($subcategoryId); // base_entity_mapping
            }

            if (count($newCategoryInformation) == 0 || !$newCategoryInformation) {
                CategorySponsorMigrationLibrary::logMessage($logFileName, "No mapping found for this shoshkele");
                continue;
            }

            $newCategoryInformation = $newCategoryInformation[0];

            $subscriptionCriteria = array(
                'stream_id'       => $newCategoryInformation->stream_id,
                'substream_id'    => $newCategoryInformation->substream_id,
                'base_course_id'  => $newCategoryInformation->base_course_id,
                'credential'      => $newCategoryInformation->credential,
                'delivery_method' => $newCategoryInformation->delivery_method,
                'education_type'  => $newCategoryInformation->education_type,
//				'tier'            => $TIER,
            );

            $criterionId = $this->model->createCriterion($subscriptionCriteria); // category_subscription_criteria
            if ($criterionId > 0) {
                CategorySponsorMigrationLibrary::logMessage($logFileName, "Created new criteria with id = $criterionId");
            } else {

                CategorySponsorMigrationLibrary::logMessage($logFileName, "Finding criterion...");
                CategorySponsorMigrationLibrary::logMessage($logFileName, $subscriptionCriteria);
                $criterionId = $this->model->findCriterionId($subscriptionCriteria);
                $criterionId = $criterionId[0]->criterion_id;
                if ($criterionId) {
                    CategorySponsorMigrationLibrary::logMessage($logFileName, "Found criterion with the criterion id $criterionId");
                } else {
                    CategorySponsorMigrationLibrary::logMessage($logFileName, "Null value encountered for this subscription criteria");
                }
            }

            $categoryPageExists = $this->model->categoryPageExistsCheck($newCategoryInformation);
            if ($categoryPageExists[0]->categoryPages == 0) {
                CategorySponsorMigrationLibrary::logMessage($logFileName, "Category Page does not exist for this combination");
                CategorySponsorMigrationLibrary::logMessage($logFileName."_no_cat_page", "listing_subs_id = ".$oneActiveSubscription->listingsubsid);
                CategorySponsorMigrationLibrary::logMessage($logFileName."_no_cat_page", "criterion_id = ".$criterionId);
            }

            CategorySponsorMigrationLibrary::logMessage($logFileName, "Inserting data for this Sticky Listing...");


            $productData = array(
                'listing_subs_id' => $oneActiveSubscription->listingsubsid,
                'product_type'    => 'category_sponsor',
                'institute_id'    => $oneActiveSubscription->listing_type_id,
                'criterion_id'    => $criterionId,
                'subscription_id' => $oneActiveSubscription->subscriptionid,
                'start_date'      => $oneActiveSubscription->startdate,
                'end_date'        => $oneActiveSubscription->enddate,
                'status'          => $oneActiveSubscription->status,
                'city_id'         => $oneActiveSubscription->cityid,
                'state_id'        => $oneActiveSubscription->stateid,
                'added_by'        => -1,
                'added_on'        => $oneActiveSubscription->lastModificationDate,
                'updated_by'      => -1,
                'updated_on'        => $oneActiveSubscription->lastModificationDate,
            );


            $productId = $this->model->saveDetails('product', $productData);

            CategorySponsorMigrationLibrary::logMessage($logFileName, $productData);
            CategorySponsorMigrationLibrary::logMessage($logFileName, $productId);
            CategorySponsorMigrationLibrary::logMessage($logFileName, "---------------------------\n");

        }
    }

    private function migrateBanners()
    {
        $activeBanners = $this->model->getActiveData('banner');
        $activeBanners = $activeBanners['data'];

        $logFileName    = "/tmp/shoshkele-migration-log" . date('Y-m-d');
        $initialMessage = "\n\n\nStarting Migrating Shoshkeles.\nCreating Criteria...";
        CategorySponsorMigrationLibrary::logMessage($logFileName, $initialMessage);
        $TIER = 1;

        foreach ($activeBanners as $oneActiveBanner) {

            CategorySponsorMigrationLibrary::logMessage($logFileName, "Active shoshkele in question: ");
            CategorySponsorMigrationLibrary::logMessage($logFileName, $oneActiveBanner);

            $subcategoryId = $oneActiveBanner->subcategoryid;
            $categoryId    = $oneActiveBanner->categoryId;

            if ($categoryId != 0) {
                $newCategoryInformation = $this->model->findCategorySubcategoryMapping($categoryId, $subcategoryId); // base_entity_mapping
            } else {
                $newCategoryInformation = $this->model->findSubcategoryMapping($subcategoryId); // base_entity_mapping
            }

            if (count($newCategoryInformation) == 0 || !$newCategoryInformation) {
                CategorySponsorMigrationLibrary::logMessage($logFileName, "No mapping found for this shoshkele");
                continue;
            }
            $newCategoryInformation = $newCategoryInformation[0];

            $bannerCriteria = array(
                'stream_id'       => $newCategoryInformation->stream_id,
                'substream_id'    => $newCategoryInformation->substream_id,
                'base_course_id'  => $newCategoryInformation->base_course_id,
                'credential'      => $newCategoryInformation->credential,
                'delivery_method' => $newCategoryInformation->delivery_method,
                'education_type'  => $newCategoryInformation->education_type,
//				'tier'            => $TIER,
            );

            $criterionId = $this->model->createCriterion($bannerCriteria);
            if ($criterionId > 0) {
                CategorySponsorMigrationLibrary::logMessage($logFileName, "Created new criteria with id = $criterionId");
            } else {

                CategorySponsorMigrationLibrary::logMessage($logFileName, "Finding criterion...");
                CategorySponsorMigrationLibrary::logMessage($logFileName, $bannerCriteria);
                $criterionId = $this->model->findCriterionId($bannerCriteria);
                $criterionId = $criterionId[0]->criterion_id;
                if ($criterionId) {
                    CategorySponsorMigrationLibrary::logMessage($logFileName, "Found criterion with the criterion id $criterionId");
                } else {
                    CategorySponsorMigrationLibrary::logMessage($logFileName, "Null value encountered for this subscription criteria");
                }
            }

            $categoryPageExists = $this->model->categoryPageExistsCheck($newCategoryInformation);
            if ($categoryPageExists[0]->categoryPages == 0) {
                CategorySponsorMigrationLibrary::logMessage($logFileName, "Category Page does not exist for this combination");
                CategorySponsorMigrationLibrary::logMessage($logFileName."_no_cat_page", "banner_link_id = ".$oneActiveBanner->bannerlinkid);
                CategorySponsorMigrationLibrary::logMessage($logFileName."_no_cat_page", "criterion_id = ".$criterionId);
            }

            CategorySponsorMigrationLibrary::logMessage($logFileName, "Inserting data for this Shoshkele...");

            $bannerData = array(
                'banner_link_id'  => $oneActiveBanner->bannerlinkid,
                'banner_id'       => $oneActiveBanner->bannerid,
                'criterion_id'    => $criterionId,
                'subscription_id' => $oneActiveBanner->subscriptionid,
                'start_date'      => $oneActiveBanner->startdate,
                'end_date'        => $oneActiveBanner->enddate,
                'status'          => $oneActiveBanner->status,
                'city_id'         => $oneActiveBanner->cityid,
                'state_id'        => $oneActiveBanner->stateId,
                'added_by'        => -1,
                'added_on'        => $oneActiveBanner->lastModificationDate,
                'updated_by'      => -1,
                'updated_on'      => $oneActiveBanner->lastModificationDate,
            );


            $bannerId = $this->model->saveDetails('banner', $bannerData);

            CategorySponsorMigrationLibrary::logMessage($logFileName, $bannerData);
            CategorySponsorMigrationLibrary::logMessage($logFileName, $bannerId);
            CategorySponsorMigrationLibrary::logMessage($logFileName, "---------------------------\n");
        }

    }

    public function migrateMainInstitutes()
    {

        $activeInstitutes = $this->model->getActiveData('mainListing'); // PageCollegeDb + tPageKeyCriteriaMapping
        $activeInstitutes = $activeInstitutes['data'];
        $TIER             = 1;
        $logFileName      = "/tmp/main-listing-migration-log" . date('Y-m-d');
        $initialMessage   = "\n\n\nStarting Migrating main institutes\nCreating Criteria...";
        CategorySponsorMigrationLibrary::logMessage($logFileName, $initialMessage);

        foreach ($activeInstitutes as $oneActiveInstitute) {

            CategorySponsorMigrationLibrary::logMessage($logFileName, "Active main listing in question: ");
            CategorySponsorMigrationLibrary::logMessage($logFileName, $oneActiveInstitute);
            $subcategoryId = $oneActiveInstitute->subCategoryId;
            $categoryId    = $oneActiveInstitute->categoryId;

            if ($categoryId != 0) {
                $newCategoryInformation = $this->model->findCategorySubcategoryMapping($categoryId, $subcategoryId); // base_entity_mapping
            } else {
                $newCategoryInformation = $this->model->findSubcategoryMapping($subcategoryId); // base_entity_mapping
            }
            if (count($newCategoryInformation) == 0 || !$newCategoryInformation) {
                CategorySponsorMigrationLibrary::logMessage($logFileName, "No mapping found for this main listing");
                continue;
            }

            $newCategoryInformation = $newCategoryInformation[0];

            $subscriptionCriteria = array(
                'stream_id'       => $newCategoryInformation->stream_id,
                'substream_id'    => $newCategoryInformation->substream_id,
                'base_course_id'  => $newCategoryInformation->base_course_id,
                'credential'      => $newCategoryInformation->credential,
                'delivery_method' => $newCategoryInformation->delivery_method,
                'education_type'  => $newCategoryInformation->education_type,
//				'tier'            => $TIER,
            );

            $criterionId = $this->model->createCriterion($subscriptionCriteria); // category_subcription_criteria
            if ($criterionId > 0) {
                CategorySponsorMigrationLibrary::logMessage($logFileName, "Created new criteria with id = $criterionId");
            } else {
                CategorySponsorMigrationLibrary::logMessage($logFileName, "Finding criterion...");
                CategorySponsorMigrationLibrary::logMessage($logFileName, $subscriptionCriteria);
                $criterionId = $this->model->findCriterionId($subscriptionCriteria);
                $criterionId = $criterionId[0]->criterion_id;
                if ($criterionId) {
                    CategorySponsorMigrationLibrary::logMessage($logFileName, "Found criterion with the criterion id $criterionId");
                } else {
                    CategorySponsorMigrationLibrary::logMessage($logFileName, "Null value encountered for this subscription criteria");
                }
            }

            $categoryPageExists = $this->model->categoryPageExistsCheck($newCategoryInformation);

            if ($categoryPageExists[0]->categoryPages == 0) {
                CategorySponsorMigrationLibrary::logMessage($logFileName, "Category Page does not exist for this combination");
                CategorySponsorMigrationLibrary::logMessage($logFileName."_no_cat_page", "listing_subs_id = ".$oneActiveInstitute->id);
                CategorySponsorMigrationLibrary::logMessage($logFileName."_no_cat_page", "criterion_id = ".$criterionId);
            }

            CategorySponsorMigrationLibrary::logMessage($logFileName, "Inserting data for this main listing...");


            $productData = array(
                'listing_subs_id' => $oneActiveInstitute->id,
                'product_type'    => 'main',
                'institute_id'    => $oneActiveInstitute->listing_type_id,
                'criterion_id'    => $criterionId,
                'subscription_id' => $oneActiveInstitute->subscriptionId,
                'start_date'      => $oneActiveInstitute->StartDate,
                'end_date'        => $oneActiveInstitute->EndDate,
                'status'          => $oneActiveInstitute->status,
                'city_id'         => $oneActiveInstitute->cityId,
                'state_id'        => $oneActiveInstitute->stateId,
                'added_by'        => -1,
                'added_on'        => $oneActiveInstitute->lastModificationDate,
                'updated_by'      => -1,
                'updated_on'      => $oneActiveInstitute->lastModificationDate,
            );


            $productId = $this->model->saveDetails('product', $productData);

            CategorySponsorMigrationLibrary::logMessage($logFileName, $productData);
            CategorySponsorMigrationLibrary::logMessage($logFileName, $productId);
            CategorySponsorMigrationLibrary::logMessage($logFileName, "---------------------------\n");
        }

        return array(
            'data'       => $activeInstitutes,
        );

    }
}