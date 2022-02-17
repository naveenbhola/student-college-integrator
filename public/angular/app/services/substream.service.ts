// Observable Version
import { Injectable }     from '@angular/core';
import { Http, Response } from '@angular/http';
import { Headers, RequestOptions } from '@angular/http';

import { Observable }     from 'rxjs/Observable';
import { ListingBaseService } from './listingbase.service';

@Injectable()
export class SubstreamService extends ListingBaseService {
	constructor(http: Http) { super(http); }
	private substreamUrl = 'listingBase/SubstreamAdmin/submit';  // URL to web API


	addSubstream(result: any,mode:string,substreamId:number) {
		result['mode'] = mode;
		result['substreamId'] = substreamId;
		let body = JSON.stringify({ result });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.substreamUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}
}