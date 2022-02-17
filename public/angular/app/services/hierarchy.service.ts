// Observable Version
import { Injectable }     from '@angular/core';
import { Http, Response } from '@angular/http';
import { Headers, RequestOptions } from '@angular/http';

import { Observable }     from 'rxjs/Observable';
import { ListingBaseService } from './listingbase.service';

@Injectable()
export class HierarchyService extends ListingBaseService {
	constructor(http: Http) { super(http); }
	private hierarchyUrl = 'listingBase/HierarchyAdmin/submit';  // URL to web API


	addHierarchy(result: any) {
		let body = JSON.stringify({ result });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.hierarchyUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}
}