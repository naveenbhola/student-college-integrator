type instituteTypes = "academy"|"center"|"college"|"department"|"faculty"|"school";
type universityTypes = "" | "deemed" | "central" | "state" | "private";
type entityTypes = "institute"|"course"|"university"|"group";
type primaryTypes = "college"|"university"|"school";
import { Entity } from '../../../Common/Classes/Entity';
import { ContactDetails,InstituteLocation,AcademicStaff,InstituteFacility,InstituteEvent,InstituteMedia,InstituteCompany,InstituteScholarship } from './InstituteEntities';

export class Institute extends Entity{
	institute_id:number = 0;
	postingListingType : string = 'Institute';
	institute_type:instituteTypes = 'college';
	university_type:universityTypes = '';
	is_dummy:any = 'false';
	parent_entity_id:number;
	parent_entity_type:entityTypes;
	parent_entity_name:string = '';
	primary_listing_type : primaryTypes;
	primary_listing_id : number;
	is_satellite_entity:boolean = false;	
	name:string = '';
	main_location:InstituteLocation = new InstituteLocation({is_main_location:true});
	short_name:string = '';
	about_college:string = '';
	abbreviation:string = '';
	synonyms:any = [];
	establishment_year:number;
	establish_university_year : number;
	ownership:string = '';
	students_type:string = 'co-ed';
	is_autonomous:boolean;
	is_national_importance:boolean;
	is_placement_page_exists:boolean;
	is_flagship_course_placement_data_exists:boolean;
	is_naukri_placement_data_exists:boolean;
	is_cutoff_page_exists:boolean;
	is_review_page_exists:boolean;
	is_all_course_page_exists:boolean;
	is_admission_page_exists:boolean;
	cutoff_page_exam_name:string;
	accreditation:string = '';
	academic_staff:AcademicStaff[] = [];
	staff_faculty_highlights : string = '';
	facilities:any = {};
	research_projects:any = [];
	events:InstituteEvent[] = [];
	scholarships:InstituteScholarship[] = [];
	usp:any = [];
	brochure_url:string = '';
	brochure_size:string = '';
	brochure_year:number = new Date().getFullYear();
	locations:any = {};
	logo_url:string = '';
	photos:InstituteMedia[] = [];
	videos:InstituteMedia[] = [];
	companies:InstituteCompany[] = [];
	seo_title:string = '';
	seo_description:string = '';
	posting_comments:string = '';
	statusFlag:string = 'draft';
	parentHierarichyDetails : Array<Object> = [];
	savingMode:string='';
	is_open_university:boolean = false;
	is_ugc_approved: boolean = true;
	is_aiu_membership:boolean = false;
	disabled_url : any = '';
	created_on :any;
	created_by: any;
	client_id:any;
	submit_date:any;
	seo_url : any;
	media_server_domain : any;

	addSynonym(str:string = ''){
		this.synonyms.push({'value':''});
	}

	removeSynonym(index){
		this.synonyms.splice(index,1);
	}

	addResearchProjects(str:string = ''){
		this.research_projects.push({'value':str});
	}

	removeResearchProjects(index){
		this.research_projects.splice(index,1);
	}

	addUsp(str:string = ''){
		this.usp.push({'value':str});
	}

	removeUsp(index){
		this.usp.splice(index,1);
	}

	addAcademicStaff(obj={}){
		if(!Object.keys(obj).length){
			let length = this.academic_staff.length;
			this.academic_staff.push(new AcademicStaff({'position':length+1}));
		}
		else{
			this.academic_staff.push(new AcademicStaff(obj));
		}
	}

	removeAcademicStaff(index){
		for(let staff of this.academic_staff){
		    if(staff.position > index){
		        --staff.position;
		    }
		}
		this.academic_staff.splice(index,1);
	}

	addEvent(obj = {}){
		if(!Object.keys(obj).length){
			let length = this.events.length;
			this.events.push(new InstituteEvent({'position':length+1}));
		}
		else{
			this.events.push(new InstituteEvent(obj));
		}
	}

	removeEvent(index){
		for(let event of this.events){
		    if(event.position > index){
		        --event.position;
		    }
		}
		this.events.splice(index,1);
	}

	addScholarship(obj = {}){
		this.scholarships.push(new InstituteScholarship(obj));
	}

	removeScholarship(index){
		this.scholarships.splice(index,1);
	}

	addPhoto(obj = {}){
		this.photos.push(new InstituteMedia(obj));	
	}

	removePhoto(index){
		this.photos.forEach((photo) => {
			if(photo.position > index)
				--photo.position;
		});
		this.photos.splice(index,1);
	}

	addVideo(obj = {}){
		this.videos.push(new InstituteMedia(obj));	
	}

	removeVideo(index){
		this.videos.forEach((video) => {
			if(video.position > index)
				--video.position;
		});
		this.videos.splice(index,1);
	}

	addCompany(obj = {}){
		obj['position'] = obj['position'] || this.companies.length+1;
		this.companies.push(new InstituteCompany(obj));
	}

	removeCompany(index){
		this.companies.forEach((company) => {
			if(company.position > index)
				--company.position;
		});
		this.companies.splice(index,1);
	}

	addLocation(obj = {}){
		if(typeof this.locations[obj['city_id']+"_0"] !== 'undefined'){
			this.removeLocation(obj['city_id'], 0);
		}
		let locIndex = obj['city_id']+"_"+obj['locality_id'];
		this.locations[locIndex] = (new InstituteLocation(obj));
		return this.locations[locIndex];
	}

	checkIfLocationExists(cityId, localityId){
		let locIndex = cityId+"_"+localityId;
		if (typeof this.locations[locIndex] !== 'undefined' || (this.main_location.city_id == cityId && this.main_location.locality_id == parseInt(localityId)))
		{
			return true;
		}
		else{
			return false;
		}
	}

	addMainLocation(obj = {}){
		this.main_location = new InstituteLocation({is_main_location:true});
	}

	removeLocation(cityId, localityId){
		let locIndex = cityId+"_"+localityId;
		if(typeof this.locations[locIndex] !== 'undefined'){
			delete this.locations[locIndex];	
		}
	}

	moveLocationToMainLocation(cityId, localityId){
		let locIndex = cityId+"_"+localityId;
		if(typeof this.locations[locIndex] !== 'undefined'){
			this.main_location = this.locations[locIndex];
			delete this.locations[locIndex];
			return true;
		}
		else if(typeof this.locations[cityId+"_0"] !== 'undefined'){
			this.main_location = this.locations[cityId+"_0"];
			delete this.locations[cityId+"_0"];
			return true;	
		}
		return false;
	}

	moveMainLocationToLocation(obj){
		let locIndex = obj.city_id+"_"+obj.locality_id;
		if(typeof this.locations[locIndex] === 'undefined'){
			// copy the object rather reference
			if(obj.city_id !== 0){
				this.locations[locIndex] = JSON.parse(JSON.stringify(obj));
				this.main_location.contact_details = new ContactDetails;
			}
			if(obj.locality_id != 0){
				if(typeof this.locations[obj.city_id+"_0"] !== 'undefined'){
					if(this.locations[obj.city_id+"_0"].institute_location_id != '' && typeof(this.locations[obj.city_id+"_0"].institute_location_id) !== 'undefined')
						this.removeLocation(obj.city_id, 0);
				}
			}
		}
	}

	removeLocationByCity(cityId){

		for (var key in this.locations) {
	      if (this.locations.hasOwnProperty(key) && this.locations[key].city_id == cityId) {
	        delete this.locations[key];
	      }
	    }
	}

	fillData(options){
		for(let i in options){
			switch(i){
				case 'academic_staff':
					for(let obj of options['academic_staff']){
						this.addAcademicStaff(obj);
					}
					if(!this.academic_staff.length){
						this.addAcademicStaff();
					}
					break;
				case 'events':
					for(let obj of options['events']){
						this.addEvent(obj);
					}
					if(!this.events.length){
						this.addEvent();
					}
					break;
				case 'scholarships':
					for(let obj of options['scholarships']){
						this.addScholarship(obj);
					}
					if(!this.scholarships.length){
						this.addScholarship();
					}
					break;
				case 'research_projects':
					for(let obj of options['research_projects']){
						this.addResearchProjects(obj);
					}
					if(!this.research_projects.length){
						this.addResearchProjects();
					}
					break;
				case 'usp':
					for(let obj of options['usp']){
						this.addUsp(obj);
					}
					if(!this.usp.length){
						this.addUsp();
					}
					break;
				case 'photos':
					for(let obj of options['photos']){
						this.addPhoto(obj);
					}
					break;
				case 'videos':
					for(let obj of options['videos']){
						this.addVideo(obj);
					}
					break;
				case 'companies':
					for(let obj of options['companies']){
						this.addCompany(obj);
					}
					break;
				case 'locations':
					for(let obj of options['locations']){
						if(obj['is_main_location']){
							this.main_location = new InstituteLocation(obj);
							if(obj['institute_location_id'] && options['contact_details'][obj['institute_location_id']]){
								this.main_location.addContact(options['contact_details'][obj['institute_location_id']]);
							}
						}
						else{
							var locObj = this.addLocation(obj);
							if(obj['institute_location_id'] && options['contact_details'][obj['institute_location_id']]){
								locObj.addContact(options['contact_details'][obj['institute_location_id']]);
							}
						}
					}
					break;
				case 'facilities':
					break;
				default:
					this[i] = options[i];
			}
		}
	}
}
