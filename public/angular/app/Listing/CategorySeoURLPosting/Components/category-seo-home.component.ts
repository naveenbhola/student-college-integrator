import { Component } from '@angular/core';
import { Router } from '@angular/router';
import {ROUTER_DIRECTIVES} from '@angular/router';
import { BasecourseService } from '../../../services/basecourse.service';
import { ListingBaseService } from '../../../services/listingbase.service';

@Component({
	template: '<router-outlet></router-outlet>',
	directives: [ROUTER_DIRECTIVES],
	providers: [BasecourseService, ListingBaseService],
})
export class CategorySeoHomeComponent{

}