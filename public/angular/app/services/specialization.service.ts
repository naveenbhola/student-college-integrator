// Observable Version
import { Injectable }     from '@angular/core';
import { Http, Response } from '@angular/http';
import { Headers, RequestOptions } from '@angular/http';

import { Observable }     from 'rxjs/Observable';
import { ListingBaseService } from './listingbase.service';

@Injectable()
export class SpecializationService extends ListingBaseService {
	constructor(http: Http) { super(http); }
	private specializationUrl = 'listingBase/SpecializationAdmin/submit';  // URL to web API


	addSpecialization(result: any,mode:string,specalizationId:number) {
		result['mode'] = mode;
		result['specializationId'] = specalizationId;
		let body = JSON.stringify({ result });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.specializationUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}
}