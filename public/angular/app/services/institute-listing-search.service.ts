import { Injectable } from '@angular/core';
import { Http, Response } from '@angular/http';
import { Headers, RequestOptions } from '@angular/http';
import { Observable }     from 'rxjs/Observable';


@Injectable()
export class InstituteListingService {
	constructor(protected http: Http) { this.http = http;}
	
	instituteTypeGetUrl = 'nationalInstitute/InstitutePosting/getInstituteTypesInShiksha';
	instituteAutosuggestorUrl = 'nationalInstitute/InstitutePosting/getParentInstituteSuggestions/';
	instituteListUrl 	= 'nationalInstitute/InstitutePosting/getListOfInstitutesBasedOnFilters';
	makeListingLiveUrl 		=  'nationalInstitute/InstitutePosting/makeListingLive';
	courseListUrl = "nationalInstitute/InstitutePosting/getCourseListForPrimary";
	updateCourseOrderUrl = "nationalInstitute/InstitutePosting/setOrderingForCourses";

	checkDraftExistUrl ="nationalInstitute/InstitutePosting/checkDraftStatusExist";
	isListingPaidUrl   = "nationalInstitute/InstitutePosting/isListingPaid";

	getInstituteTypesInShiksha()
	{
		return this.http.get(this.instituteTypeGetUrl).map(this.extractData).catch(this.handleError);
	}
	populateInstitutesBasedOnFilters(instituteFiltersObj)
	{
		//console.log(instituteFiltersObj);
		let headers = new Headers({ 'Content-Type': 'application/json' });
    	let options = new RequestOptions({ headers: headers });
		return this.http.post(this.instituteListUrl, instituteFiltersObj, options).map(this.extractData).catch(this.handleError);
	}

	getInstituteAutosuggestor(keyword,suggestionType= 'all'){
		//let body = JSON.stringify({'text':keyword });
		let body = 'text='+keyword+'&statusCheck=true'+'&suggestionType='+suggestionType;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.instituteAutosuggestorUrl,body, options).map(res => res.json()).catch(this.handleError);			
	}

	extractData(res: Response) {

		let body = res.json();
		console.log('response'+body.data);
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
	makeListingLive(listingObj)
	{
		let headers = new Headers({'Content-Type' : 'application/json'});
		let options = new RequestOptions({headers : headers}) ;
		return this.http.post(this.makeListingLiveUrl,listingObj,options).map(this.extractData).catch(this.handleError);
	}

	getCoursesForOrdering(listingTypeId){
		let body = JSON.stringify({"listingTypeId":listingTypeId});
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.courseListUrl, body, options)
			.map(this.extractData)
			.catch(this.handleError);
	}

	updateCourseOrdering(listingTypeId,listingType,courseList,prevCourseList){
		let body = JSON.stringify({'listingTypeId':listingTypeId,'listingType':listingType,'courseList' : courseList,'prevCourseList' : prevCourseList});
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.updateCourseOrderUrl, body, options)
			.map(this.extractData)
			.catch(this.handleError);
	}

	checkDraftEntryExist(id)
	{
		let body = 'listing_id='+id;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.checkDraftExistUrl, body, options)
			.map(this.extractData)
			.catch(this.handleError);	
	}

	checkIsListingPaid(listingId,listingType){
		let body = 'listingId='+listingId+'&listingType='+listingType;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.isListingPaidUrl,body,options).map(this.extractData).catch(this.handleError);		
	}

}
