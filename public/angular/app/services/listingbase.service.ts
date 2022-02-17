// Observable Version
import { Injectable }     from '@angular/core';
import { Http, Response } from '@angular/http';

import { Headers, RequestOptions } from '@angular/http';

import { Observable }     from 'rxjs/Rx';

@Injectable()
export class ListingBaseService {
	constructor(protected http: Http) { this.http = http; }
	substreamGetUrl                    = 'listingBase/SubstreamAdmin/getAllSubstreams';
	substreamSingleGet                 = 'listingBase/SubstreamAdmin/getSubstream/';
	specializationGetUrl               = 'listingBase/SpecializationAdmin/getAllSpecializations';
	hierarchyGetUrl                    = 'listingBase/HierarchyAdmin/getAllHierarchies';
	basecourseGetUrl                   = 'listingBase/BaseCourseAdmin/getAllBasecourses';
	popularCourseGetUrl                = 'listingBase/BaseCourseAdmin/getPopularCourses';
	nonPopularCourseGetUrl             = 'listingBase/BaseCourseAdmin/getNonPopularCourses';
	basecourseSingleGetUrl             = 'listingBase/BaseCourseAdmin/getBaseCourse/';
	basecourseAttributes               = 'listingBase/BaseCourseAdmin/getCourseAttributes';
	specializationSingleGet            = 'listingBase/SpecializationAdmin/getSpecialization/';
	streamGetUrl                       = 'listingBase/SubstreamAdmin/getAllStreams';
	certificationProviderGetUrl        = 'listingBase/CertificationProviderAdmin/getAllCertificationProviders';
	certificationProviderSingleGet     = 'listingBase/CertificationProviderAdmin/getCertificationProvider/';
	hierarchyTreeGetUrl                = 'listingBase/HierarchyAdmin/getHierarchyTree';
	instituteAutosuggestorUrl          = 'nationalInstitute/InstitutePosting/getParentInstituteSuggestions/';
	baseCoursesbyHierarchiesUrl        = 'nationalCourse/CoursePosting/getBasecoursesByMultipleBaseEntities';
	baseCoursesObjbyHierarchiesUrl     = 'listingBase/BaseCourseAdmin/getBasecoursesByMultipleBaseEntities';
	basecoursesByHirarchyWithFilterUrl = 'nationalCourse/CoursePosting/getBasecoursesByHirarchyWithFilter';
	instituteNameUrl                   = 'nationalInstitute/InstitutePosting/getShikshaInstituteName/';
	saInstituteAutosuggestorUrl        = 'nationalInstitute/InstitutePosting/getSAUniversities/';
	specByBaseCoursesUrl               = 'nationalCourse/CoursePosting/getSpecializationsByBaseCourses';

	getSubstreams() {
		return this.http.get(this.substreamGetUrl).map(this.extractData).catch(this.handleError);
	}

	getSubstream(id) {
		return this.http.get(this.substreamSingleGet + id).map(this.extractData).catch(this.handleError);
	}

	getSpecialization(id) {
		return this.http.get(this.specializationSingleGet + id).map(this.extractData).catch(this.handleError);
	}

	getCertificationProvider(id) {
		return this.http.get(this.certificationProviderSingleGet + id).map(this.extractData).catch(this.handleError);
	}

	getSpecializations() {
		return this.http.get(this.specializationGetUrl).map(this.extractData).catch(this.handleError);
	}

	getHierarchies() {
		return this.http.get(this.hierarchyGetUrl).map(this.extractData).catch(this.handleError);
	}

	getBasecourses() {
		return this.http.get(this.basecourseGetUrl).map(this.extractData).catch(this.handleError);
	}

	getBasecourse(id) {
		return this.http.get(this.basecourseSingleGetUrl + id).map(this.extractData).catch(this.handleError);
	}


	getPopularCourses() {
		return this.http.get(this.popularCourseGetUrl).map(this.extractData).catch(this.handleError);
	}

	getNonPopularCourses(){
		return this.http.get(this.nonPopularCourseGetUrl).map(this.extractData).catch(this.handleError);
	}

	getStreams() {
		return this.http.get(this.streamGetUrl).map(this.extractData).catch(this.handleError);
	}

	getHierarchyTree() {
		return this.http.get(this.hierarchyTreeGetUrl + "/1").map(this.extractData).catch(this.handleError);
	}

	getCertificationProviders() {
		return this.http.get(this.certificationProviderGetUrl).map(this.extractData).catch(this.handleError);
	}

	getInstituteAutosuggestor(keyword,suggestionType = 'all') {
		//let body = JSON.stringify({'text':keyword });
		let body = 'text=' + keyword+'&suggestionType='+suggestionType;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.instituteAutosuggestorUrl, body, options).map(res => res.json()).catch(this.handleError);
	}

	getSAInstituteAutosuggestor(keyword){
		//let body = JSON.stringify({'text':keyword });
		let body = 'text='+keyword+'&source=autosuggestor';

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.saInstituteAutosuggestorUrl,body, options).map(res => res.json()).catch(this.handleError);			
	}

	extractData(res: Response) {
		let body = res.json();
		return body.data || {};
	}

	handleError(error: any) {
		// In a real world app, we might use a remote logging infrastructure
		// We'd also dig deeper into the error to get a better message
		let errMsg = (error.message) ? error.message :
			error.status ? `${error.status} - ${error.statusText}` : 'Server error';
		console.error(errMsg); // log to console instead
		return Observable.throw(errMsg);
	}

	getSubstreamsByStreams(streamIds) {
		return this.http.get('listingBase/SubstreamAdmin/getSubstreamsByStream/' + streamIds).map(this.extractData).catch(this.handleError);
	}

	getCourseAttributes() {
		return this.http.get(this.basecourseAttributes).map(this.extractData).catch(this.handleError);
	}

	getInstituteName(type,val){
		//let body = JSON.stringify({'text':keyword });
		let body = 'id='+val+'&type='+type;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.instituteNameUrl,body, options).map(res => res.json()).catch(this.handleError);			
	}

	getSAInstituteName(type,val){
		let body = 'text='+val+'&source=input';

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.saInstituteAutosuggestorUrl,body, options).map(res => res.json()).catch(this.handleError);			
	}

	getBasecoursesByMultipleBaseEntities(hierarchyArr){
		let body = JSON.stringify({ hierarchyArr });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.baseCoursesbyHierarchiesUrl, body, options).map(this.extractData).catch(this.handleError);
	}

	getBasecoursesObjByMultipleBaseEntities(hierarchyArr){
		let body = JSON.stringify({ hierarchyArr });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.baseCoursesObjbyHierarchiesUrl, body, options).map(this.extractData).catch(this.handleError);
	}

	getBasecoursesByHirarchyWithFilter(hierarchyArr, courseLevel, credential){
		let body = JSON.stringify({'hierarchyArr': hierarchyArr, 'course_level' : courseLevel, 'credential' : credential });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.basecoursesByHirarchyWithFilterUrl, body, options).map(this.extractData).catch(this.handleError);
	}

	getSpecializationByBaseCourses(baseCourseArr){
		let body = JSON.stringify({ baseCourseArr });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.specByBaseCoursesUrl, body, options).map(this.extractData).catch(this.handleError);
	}
}

export class ListingBaseClass {
	streamList = [];
	substreamList = [];
	specializationList = [];
	hierarchyList = [];
	basecourseList = [];
	certificationProviderList = [];

	constructor(public listingBaseService: ListingBaseService) { }

	getStreamList() {
		this.listingBaseService.getStreams()
			.subscribe(
			streamList => { this.streamList = Object.values(streamList); },
			error => alert('Failed to get Streams'));
	}

	getSubstreamList() {
		this.listingBaseService.getSubstreams()
			.subscribe(
			substreamList => { this.substreamList = Object.values(substreamList); },
			error => alert('Failed to get Substreams'));
	}

	getSpecializationList() {
		this.listingBaseService.getSpecializations()
			.subscribe(
			specializationList => { this.specializationList = Object.values(specializationList); },
			error => alert('Failed to get Specializations'));
	}

	getHierarchyList(type = 'array') {
		let o1 = this.listingBaseService.getHierarchies();
		let o2 = this.listingBaseService.getStreams();
		let o3 = this.listingBaseService.getSubstreams();
		let o4 = this.listingBaseService.getSpecializations();
		Observable.forkJoin([o1, o2, o3, o4]).subscribe(
			data => {
				let hierarchyData = data[0]; this.streamList = data[1]; this.substreamList = data[2]; this.specializationList = data[3];

				for (let hierarchy of hierarchyData) {
					if (hierarchy['stream_id']) {
						let streamId = hierarchy.stream_id;
						hierarchy['stream_name'] = this.streamList[streamId].name;
					}
					if (hierarchy['substream_id']) {
						hierarchy['substream_name'] = this.substreamList[+hierarchy['substream_id']].name;
					}
					if (hierarchy['specialization_id']) {
						hierarchy['specialization_name'] = this.specializationList[+hierarchy['specialization_id']].name;
					}
					if (type == 'array') {
						this.hierarchyList.push(hierarchy);
					}
					else {
						this.hierarchyList[hierarchy['hierarchy_id']] = hierarchy;
					}
				}
				this.streamList = Object.values(this.streamList);
				this.substreamList = Object.values(this.substreamList);
				this.specializationList = Object.values(this.specializationList);
			},
			error => alert('Failed To get Data')
		);
	}

	getBasecourseList() {
		this.listingBaseService.getBasecourses()
			.subscribe(
			basecourseList => { this.basecourseList = Object.values(basecourseList); },
			error => alert('Failed to get Base courses'));
	}

	getCertificationProviderList() {
		this.listingBaseService.getCertificationProviders()
			.subscribe(
			certificationProviderList => { this.certificationProviderList = Object.values(certificationProviderList); },
			error => alert('Failed to get Base courses'));
	}


}