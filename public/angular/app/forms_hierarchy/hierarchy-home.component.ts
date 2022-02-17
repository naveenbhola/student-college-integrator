import { Component } from '@angular/core';
import { Router } from '@angular/router';
import {ROUTER_DIRECTIVES} from '@angular/router';
import { HierarchyService }       from '../services/hierarchy.service';
import { ListingBaseService } from '../services/listingbase.service';

@Component({
	template: '<router-outlet></router-outlet>',
	directives: [ROUTER_DIRECTIVES],
	providers: [HierarchyService,ListingBaseService],
})

export class HierarchyHomeComponent{

}