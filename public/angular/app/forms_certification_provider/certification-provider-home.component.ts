import { Component } from '@angular/core';
import { Router } from '@angular/router';
import {ROUTER_DIRECTIVES} from '@angular/router';
//import { SubstreamService } from '../services/substream.service';
import { ListingBaseService } from '../services/listingbase.service';

@Component({
	template: '<router-outlet></router-outlet>',
	directives: [ROUTER_DIRECTIVES],
	providers: [ListingBaseService],
})
export class CertificationProviderHomeComponent{

}