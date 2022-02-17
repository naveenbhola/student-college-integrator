import { Component } from '@angular/core';
import { Router } from '@angular/router';
import {ROUTER_DIRECTIVES} from '@angular/router';
import { PopulargroupService } from '../services/populargroup.service';
import { ListingBaseService } from '../services/listingbase.service';

@Component({
	template: '<router-outlet></router-outlet>',
	directives: [ROUTER_DIRECTIVES],
	providers: [PopulargroupService, ListingBaseService],
})
export class PopulargroupHomeComponent{

}