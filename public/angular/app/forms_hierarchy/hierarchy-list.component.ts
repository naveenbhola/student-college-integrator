import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ArraySearchPipe, LimitArrayPipe, SortArrayPipe } from '../pipes/arraypipes.pipe';
import { ListingBaseClass, ListingBaseService } from '../services/listingbase.service';
import { Observable }     from 'rxjs/Rx';

@Component({
  selector: 'hierarchy',
  templateUrl: '/public/angular/app/forms_hierarchy/hierarchy-list.component.html',
  pipes: [ArraySearchPipe, LimitArrayPipe, SortArrayPipe]
})

export class HierarchyListComponent extends ListingBaseClass implements OnInit {
	errorMessage: string;
	
	constructor(private router: Router, public listingBaseService: ListingBaseService) { super(listingBaseService); }
	ngOnInit() {
		this.getHierarchyList();
	}

	newHierarchy() {
		this.router.navigate(['/cmsPosting/hierarchyPosting/create']);		
	}
	
}

