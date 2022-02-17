// Observable Version
import { Injectable }     from '@angular/core';
import { Http, Response } from '@angular/http';
import { Headers, RequestOptions } from '@angular/http';

import { Observable }     from 'rxjs/Observable';
import { ListingBaseService } from './listingbase.service';

@Injectable()
export class CertificationProviderService extends ListingBaseService {
	constructor(http: Http) { super(http); }
	private certificationProviderUrl = 'listingBase/CertificationProviderAdmin/submit';  // URL to web API

	addCertificationProvider(result: any,mode:string,certificationProviderId:number) {
		result['mode'] = mode;
		result['certificationProviderId'] = certificationProviderId;
		let body = JSON.stringify({ result });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.certificationProviderUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}

}