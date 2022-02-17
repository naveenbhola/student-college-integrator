// Observable Version
import { Injectable }     from '@angular/core';
import { Http, Response } from '@angular/http';
import { Headers, RequestOptions } from '@angular/http';

import { Observable }     from 'rxjs/Observable';
import { ListingBaseService } from './listingbase.service';

@Injectable()
export class InstitutePostingService extends ListingBaseService {
	constructor(http: Http) { super(http); }
	instituteStaticApiUrl = 'nationalInstitute/InstitutePosting/getStaticAttribute';
	locationApiUrl = 'nationalInstitute/InstitutePosting/getLocationTree';
	instituteParentHierarchyApiUrl = 'nationalInstitute/InstitutePosting/getInstituteParentHierarchy';
	instituteSaveUrl = 'nationalInstitute/InstitutePosting/saveInstitute';
	instituteAutosuggestorUrl = 'nationalInstitute/InstitutePosting/getCompaniesSuggestion/';
	instituteDataUrl = 'nationalInstitute/InstitutePosting/getInstituteData/';
	institutePostingCommentUrl = 'nationalInstitute/InstitutePosting/getInstitutePostingComments';
	instituteTypeHierarchyCheck = 'nationalInstitute/InstitutePosting/validateInstituteTypeInHierarchy';
	locationMappedToCourse = 'nationalInstitute/InstitutePosting/checkIfLocationMappedToCourse';
	isUserAllowedEdit 		= 'nationalInstitute/InstitutePosting/isUserAllowedInEditMode';
	cancelEditFormUrl 		= 'nationalInstitute/InstitutePosting/cancelEditForm';
	isAllowUserActionUrl		= 'nationalInstitute/InstitutePosting/isAllowUserAction';
	
	getInstituteStaticData(){
		return this.http.get(this.instituteStaticApiUrl).map(this.extractData).catch(this.handleError);
	}

	getInstituteData(instituteId,is_dummy,postingListingType){

		return this.http.get(this.instituteDataUrl+instituteId+'/'+is_dummy+'/'+postingListingType).map(this.extractData).catch(this.handleError);
	}

	getLocationData(){
		return this.http.get(this.locationApiUrl).map(this.extractData).catch(this.handleError);
	}

	getInstituteParentHierarchyData(instituteId,type,institute_posting_type,postingListingType,typeOfListingPosting,excludedInstituteId){

		let body = 'id='+instituteId+"&type="+type+"&institute_posting_type="+institute_posting_type+'&postingListingType='+postingListingType+'&typeOfListingPosting='+typeOfListingPosting+'&excludedInstituteId='+excludedInstituteId;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.instituteParentHierarchyApiUrl,body, options).map(res => res.json()).catch(this.handleError);			
	}

	saveInstituteData(instituteObj, oldInstituteState){

		let body = JSON.stringify({ 'instituteObj' : instituteObj, 'oldInstituteState' : oldInstituteState });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.instituteSaveUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}

	getCompaniesAutosuggestor(keyword){
		let body = 'text='+keyword;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.instituteAutosuggestorUrl,body, options).map(res => res.json()).catch(this.handleError);			
	}

	getPostingComments(instituteId,listingType){
		let body = 'instituteId='+instituteId+'&listingType='+listingType;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.institutePostingCommentUrl,body, options).map(res => res.json()).catch(this.handleError);			
	}

	validateInstituteTypeInHierarchy(parentId, parentType, instituteType,instituteId){
		let body = 'parentId='+parentId+"&parentType="+parentType+"&instituteType="+instituteType+'&instituteId='+instituteId;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.instituteTypeHierarchyCheck,body, options).map(res => res.json()).catch(this.handleError);			
	}

	checkLocationMappedToCourse(instituteLocationIds){
		let body = JSON.stringify({ 'instituteLocationIds' : instituteLocationIds});

		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.locationMappedToCourse,body, options).map(res => res.json()).catch(this.handleError);			
	}	
	checkUserAllowed(listingId,listingType)
	{
		let body = 'listingId='+listingId+'&listingType='+listingType;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.isUserAllowedEdit,body, options).map(this.extractData).catch(this.handleError);
	}
	isAllowUserAction(listingId,listingType)
	{
		let body = 'listingId='+listingId+'&listingType='+listingType;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.isAllowUserActionUrl,body, options).map(this.extractData).catch(this.handleError);
	}
	cancelEditForm(listingId,listingType)
	{

		let body = 'listingId='+listingId+'&listingType='+listingType;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.cancelEditFormUrl,body, options).map(this.extractData).catch(this.handleError);
	}
}