// Observable Version
import { Injectable }     from '@angular/core';
import { Http, Response } from '@angular/http';
import { Headers, RequestOptions } from '@angular/http';

import { Observable }     from 'rxjs/Observable';
import { ListingBaseService } from './listingbase.service';

@Injectable()
export class InstituteDeleteService extends ListingBaseService {
	constructor(http: Http) { super(http); }
	instituteValidateUrl = 'nationalInstitute/InstitutePosting/validateListingForDeletion';
	instituteDeleteUrl = 'nationalInstitute/InstitutePosting/deleteListing';
	instituteDisableUrl = 'nationalInstitute/InstitutePosting/makeListingPageDisable';
	
	validateInstituteData(newInstituteId,listingType){
		let body = JSON.stringify({"newInstituteId":newInstituteId,"listingType":listingType});
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.instituteValidateUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}

	deleteInstituteListing(listingTypeId,newInstituteId,listingType,newlisting_name){
		let body = JSON.stringify({"listingTypeId":listingTypeId,"newInstituteId":newInstituteId,"listingType":listingType,"newlisting_name":newlisting_name});
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.instituteDeleteUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}

	setListingDisableUrl(listingTypeId,listingType,disabled_url){
		let body = JSON.stringify({"listingTypeId":listingTypeId,"listingType":listingType,"disabled_url":disabled_url});
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.instituteDisableUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);

	}

}