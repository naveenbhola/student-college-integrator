// Observable Version
import { Injectable }     from '@angular/core';
import { Http, Response } from '@angular/http';
import { Headers, RequestOptions } from '@angular/http';

import { Observable }     from 'rxjs/Observable';
import { ListingBaseService } from './listingbase.service';

@Injectable()
export class BasecourseService extends ListingBaseService {
	constructor(http: Http) { super(http); }
	private basecourseUrl = 'listingBase/BaseCourseAdmin/saveBaseCourse';  // URL to web API


	addBasecourse(result: any, mode: string, basecourseId: number) {
		result['mode'] = mode;
		result['basecourseId'] = basecourseId;
		let body = JSON.stringify({ result });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.basecourseUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}
}