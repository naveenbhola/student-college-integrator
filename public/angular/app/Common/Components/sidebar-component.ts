import { Component } from '@angular/core';
import {ROUTER_DIRECTIVES,Router} from '@angular/router';
import { SidebarService } from '../../services/sidebar-service';
import {Posting} from '../Classes/Posting';

@Component({
	selector: 'side-bar',
	templateUrl : '/public/angular/app/Common/Views/sidebar-template.html',
	directives: [ROUTER_DIRECTIVES],
	styles:[`
		.nav.child_menu{
			display:block !important;
		}
	`]
})

export class SideBarComponent extends Posting{
	activeLink:any='shikshaBase';
	subLink:any;sub:any;
	subLinksShow:any;

	universitySubLinks:any = [{'id':'university_parent_mapping_form', 'value': 'Parent Mapping'},
						 {'id': 'basic_info_form','value': 'Basic Info'},
						 {'id': 'academic_staff_form','value': 'Academic Staff'},
						 {'id': 'facilities_form','value': 'Institute Facilities'},
						 {'id': 'research_projects_form','value': 'Research'},
						 {'id': 'event_form','value': 'Events'},
						 {'id': 'usp_form','value': 'USP'},
						 {'id': 'institute_brochure_form','value': 'Brochure'},
						 {'id': 'media_photo_form','value': 'Logo and Media'},
						 {'id': 'scholarship_form','value': 'Scholarships'},
						 {'id': 'seo_specification_form','value': 'SEO Specification'}
						 ];

	instituteSubLinks:any = [{'id':'parent_mapping_form', 'value': 'Parent Mapping'},
						 {'id': 'basic_info_form','value': 'Basic Info'},
						 {'id': 'academic_staff_form','value': 'Academic Staff'},
						 {'id': 'facilities_form','value': 'Institute Facilities'},
						 {'id': 'research_projects_form','value': 'Research'},
						 {'id': 'event_form','value': 'Events'},
						 {'id': 'usp_form','value': 'USP'},
						 {'id': 'institute_brochure_form','value': 'Brochure'},
						 {'id': 'media_photo_form','value': 'Logo and Media'},
						 {'id': 'scholarship_form','value': 'Scholarships'},
						 {'id': 'seo_specification_form','value': 'SEO Specification'}
						 ];

	courseSubLinks:any = [
						 {'id':'course_parent_mapping_form', 'value': 'Parent Mapping'},
						 {'id': 'course_type_form','value': 'Type Information'},
						 {'id': 'course_schedule_info_form','value': 'Schedule Information'},						 
						 {'id': 'course_basic_info_form','value': 'Basic Information'},
						 {'id': 'course_usp_form','value':'USP'},
						 {'id': 'course_admission_process_form','value':'Admission Process'},
						 {'id': 'course_structure_form','value':'Course Structure'},
						 {'id': 'course_brochure_form','value':'Brochure'},
						 {'id': 'course_fees_details_form','value': 'Fees Details'},
						 {'id': 'course_eligibility_form','value': 'Eligibility'},
						 {'id': 'course_exam_cutoff','value': 'Cut-off Details'},
						 {'id': 'course_seats_form','value':'Seats Break-Up'},
						 {'id': 'course_placements_form','value':'Placements'},
						 {'id': 'course_internsip_form','value':'Internships'},
						 {'id': 'course_partner_institute','value': 'Partner Institutes'},
						 {'id': 'course_importantdates_form','value':'Important Dates'},
						 {'id': 'course_locations_form','value': 'Locations'},
						 {'id': 'seo_specification_form','value':'SEO Specification'},
						 {'id': 'course_media_form','value':'Course Media'}
						 ];

	constructor(public sidebarService:SidebarService,public router: Router){
		super();
		this.sub = sidebarService.updateLinksObservable$.subscribe(section => {this.setActiveLink(section['activeLink']);this.subLink=section['subLink'];});
	}

	get sideBarSubLinks(){
		return this.sidebarService.subLinks;
    }

    get validUniversitySubLinks(){
    	if(this.sidebarService.subLinks.length == 0) {
    		return this.universitySubLinks;
    	}
    	else  
    	{
    		return this.sidebarService.subLinks;
    	}
    }

    get getValidInstituteSubLinks() {
    	if(this.sidebarService.subLinks.length == 0) {
    		return this.instituteSubLinks;
    	}
    	else {
    		return this.sidebarService.subLinks;
    	}
    }

    get validCourseSubLinks(){
    	return this.courseSubLinks;
    }



	setActiveLink(activeLink){			
		this.activeLink = activeLink;					
	}

	toggleSideBar(){
		let a = document.body.classList;
		if(a.contains('nav-sm')){
			a.remove('nav-sm');
			a.add('nav-md');
		}
		else{
			a.remove('nav-md');
			a.add('nav-sm');
		}
		this.activeLink = '';
	}

	scrollToView(id){
		document.getElementById(id).scrollIntoView();
	}

	openViewList(pathLink){
		// this.router.navigate(['/nationalInstitute/'+pathLink+'/viewList']);
		window.location.href = '/nationalInstitute/'+pathLink+'/viewList';
	}
	
}