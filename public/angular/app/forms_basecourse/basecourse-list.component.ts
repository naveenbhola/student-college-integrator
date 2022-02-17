import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ArraySearchPipe, LimitArrayPipe } from '../pipes/arraypipes.pipe';
import { ListingBaseClass, ListingBaseService } from '../services/listingbase.service';

@Component({
  selector: 'basecourse',
  templateUrl: '/public/angular/app/forms_basecourse/basecourse-list.component.html',
  pipes: [ArraySearchPipe, LimitArrayPipe]
})

export class BasecourseListComponent extends ListingBaseClass implements OnInit {
	constructor(private router: Router, public listingBaseService: ListingBaseService) { super(listingBaseService); }
	ngOnInit() {
		this.getBasecourseList();
	}

	newBasecourse() {
		this.router.navigate(['/cmsPosting/basecoursePosting/create']);		
	}

	navigateToEdit(id){
		this.router.navigate(['/cmsPosting/basecoursePosting/create/' + id]);
	}
}

