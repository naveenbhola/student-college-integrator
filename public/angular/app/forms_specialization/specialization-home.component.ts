import { Component } from '@angular/core';
import { Router } from '@angular/router';
import {ROUTER_DIRECTIVES} from '@angular/router';
import { SpecializationService }       from '../services/specialization.service';
import { ListingBaseService } from '../services/listingbase.service';

@Component({
	template: '<router-outlet></router-outlet>',
	directives: [ROUTER_DIRECTIVES],
	providers: [SpecializationService,ListingBaseService],
})
export class SpecializationHomeComponent {

}