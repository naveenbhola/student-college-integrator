import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ArraySearchPipe, LimitArrayPipe, SortArrayPipe } from '../pipes/arraypipes.pipe';
import { ListingBaseClass, ListingBaseService } from '../services/listingbase.service';

@Component({
  selector: 'certificationProvider',
  templateUrl: '/public/angular/app/forms_certification_provider/certification-provider-list.component.html',
  pipes: [ArraySearchPipe, LimitArrayPipe, SortArrayPipe]
})

export class CertificationProviderListComponent extends ListingBaseClass implements OnInit {
	errorMessage: string;
	constructor(private router: Router, public listingBaseService: ListingBaseService) { super(listingBaseService); }
	ngOnInit() {
		this.getCertificationProviderList();
	}

	newCertificationProvider() {
		this.router.navigate(['/cmsPosting/certificationProviderPosting/create']);		
	}
	
	navigateToEdit(id){
		this.router.navigate(['/cmsPosting/certificationProviderPosting/create/' + id]);
	}
}