// Observable Version
import { Injectable }     from '@angular/core';
import { Http, Response } from '@angular/http';
import { Headers, RequestOptions } from '@angular/http';

import { Observable }     from 'rxjs/Observable';
import { ListingBaseService } from '../../../services/listingbase.service';

@Injectable()
export class CoursePostingService extends ListingBaseService {
	constructor(http: Http) { super(http); }
	courseStaticApiUrl = 'nationalCourse/CoursePosting/getStaticAttribute';
	courseSaveUrl = 'nationalCourse/CoursePosting/saveCourse';
	courseDataUrl = 'nationalCourse/CoursePosting/getCourseData';
	courseLocationUrl = 'nationalCourse/CoursePosting/getCourseLocations';
	courseListUrl = 'nationalCourse/CoursePosting/getListOfCoursesBasedOnFilters';
	courseValidateUrl = 'nationalCourse/CoursePosting/validateCourseForDeletion';
	courseDeleteUrl = 'nationalCourse/CoursePosting/deleteCourse';
	courseDisableUrl = 'nationalCourse/CoursePosting/disableCourse';
	courseEnableUrl = 'nationalCourse/CoursePosting/enableCourse';
	courseMakeLiveEnableUrl = 'nationalCourse/CoursePosting/makeLiveAndEnableCourseListing';
	courseMakeLiveUrl = 'nationalCourse/CoursePosting/makeCourseLive';
	courseRecrutingCompaniesUrl = 'nationalCourse/CoursePosting/getRecruitingCompaniesMappedToInstitute';
	courseCloneUrl = 'nationalCourse/CoursePosting/cloneCourse';
	courseMediaUrl = 'nationalCourse/CoursePosting/getInstituteMedia';
	coursePostingCommentUrl = 'nationalCourse/CoursePosting/getCoursePostingComments';
	releaseLockUrl = 'nationalCourse/CoursePosting/releaseLockOnCourse';

	getCourseStaticData() {
		return this.http.get(this.courseStaticApiUrl).map(this.extractData).catch(this.handleError);
	}

	saveCourseData(courseObj) {
		let body = JSON.stringify({ courseObj });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.courseSaveUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}

	getCourseData(courseId) {
		let body = JSON.stringify({ courseId });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.courseDataUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}

	getCourseLocation(instituteId) {
		let body = 'instituteId=' + instituteId;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.courseLocationUrl, body, options).map(res => res.json()).catch(this.handleError);
	}

	populateCoursesBasedOnFilters(courseFiltersObj) {
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });
		return this.http.post(this.courseListUrl, courseFiltersObj, options).map(this.extractData).catch(this.handleError);
	}

	validateCourseId(courseId) {
		let body = JSON.stringify({ "courseId": courseId });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.courseValidateUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}

	deleteCourseListing(courseId, migrationId, responses, reviews, crs, questions) {
		let body = JSON.stringify({ "courseId": courseId, "migrationId": migrationId, "responses":responses, "reviews":reviews, "crs": crs, "questions": questions });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.courseDeleteUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}

	disableCourseListing(courseId, url) {
		let body = JSON.stringify({ "courseId": courseId, "url": url });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.courseDisableUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}

	enableCourseListing(courseId,status) {
		let body = JSON.stringify({ "courseId": courseId,'status': status });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.courseEnableUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}

	makeLiveCourseListing(courseId, status) {
		let body = JSON.stringify({ "courseId": courseId, "status": status });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.courseMakeLiveUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}

	makeLiveAndEnableCourseListing(courseId, status) {
		let body = JSON.stringify({ "courseId": courseId, "status": status });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.courseMakeLiveEnableUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);

	}

	getRecruitingCompaniesMappedToInstitute(instituteId) {
		let body = 'instituteId=' + instituteId;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.courseRecrutingCompaniesUrl, body, options).map(res => res.json()).catch(this.handleError);
	}

	cloneCourse(data) {
		let body = JSON.stringify(data);
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });
		return this.http.post(this.courseCloneUrl, body, options).map(super.extractData).catch(super.handleError);
	}

	getMediaMappedToInstitute(instituteId) {
		let body = 'instituteId=' + instituteId;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.courseMediaUrl, body, options).map(res => res.json()).catch(this.handleError);
	}

	getCoursePostingComments(courseId) {
		let body = 'courseId=' + courseId;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.coursePostingCommentUrl, body, options).map(res => res.json()).catch(this.handleError);
	}

	releaseLockOnCourse(courseId){
		if(courseId){
			let body = 'courseId=' + courseId;

			let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
			let options = new RequestOptions({ headers: headers });

			return this.http.post(this.releaseLockUrl, body, options).map(super.extractData).catch(this.handleError);
		}
		else{
			return Observable.of(true);
		}
	}
}