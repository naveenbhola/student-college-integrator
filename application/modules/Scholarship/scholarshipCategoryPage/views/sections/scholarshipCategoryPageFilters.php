<div class="dtls-sidebar" id="dtls-sidebar">
        <h2 class="f14-fnt fnt-sbold refine-txt">REFINE YOUR SEARCH <a class="a-link pos-abs resetLnk" href="Javascript:void(0);">Reset All</a></h2>
        <ul class="side__ul">
            <?php
                if($request->getType()=='country'){
                        $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageCourseLevelFilter');
                }else{
                        $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageDestinationCountryFilter');
                }
                $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageAmountFilter');
                $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageApplicableStreamFilter');
                $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageScholarshipTypeFilter');
                $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageSpecialRestrictionsFilter');
                $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageApplicabilityFilter');
                $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageDeadlineFilter');
                $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageCitizenshipFilter');
                $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageStudentAwardsFilter');
                $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageIntakeYearFilter');
            ?>			
        </ul>
</div>