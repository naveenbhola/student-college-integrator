import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ArraySearchPipe, LimitArrayPipe, SortArrayPipe } from '../pipes/arraypipes.pipe';
import { ListingBaseClass, ListingBaseService } from '../services/listingbase.service';
import { PopulargroupService } from '../services/populargroup.service';

@Component({
  selector: 'populargroup',
  templateUrl: '/public/angular/app/forms_populargroup/populargroup-list.component.html',
  pipes: [ArraySearchPipe, LimitArrayPipe, SortArrayPipe]
})

export class PopulargroupListComponent extends ListingBaseClass implements OnInit {
	errorMessage: string;
	populargroupList = [];
	constructor(private router: Router,public popularGroupService: PopulargroupService, public listingBaseService: ListingBaseService) { super(listingBaseService); }
	ngOnInit() {
		this.popularGroupService.getPopulargroupList().subscribe(populargroupList => { this.populargroupList = Object.values(populargroupList); }, error => alert('Failed to get popular groups'));
	}

	newPopulargroup() {
		this.router.navigate(['/cmsPosting/populargroupPosting/create']);		
	}
	
	navigateToEdit(id){
		this.router.navigate(['/cmsPosting/populargroupPosting/create/' + id]);
	}
}

