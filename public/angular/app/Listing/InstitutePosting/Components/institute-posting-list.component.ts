import { Component, OnInit,Input } from '@angular/core';
import { SidebarService } from '../../../services/sidebar-service';
import { InstituteListingSearchFilters } from './institute-list-search-filters.component';
import { instituteSRTable } from './institute-search-result-table.component';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
    selector: 'institutePostingList',
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('instituteListingView.component.html'),
	directives:[InstituteListingSearchFilters , instituteSRTable]
})
export class InstitutePostingListComponent implements OnInit{
	@Input() postingListingType : string = 'Institute';
	constructor(public sidebarService:SidebarService){
	}

	ngOnInit(){
		if(this.postingListingType == 'University')
			this.sidebarService.updateLinks({'activeLink':'universityPosting','subLink':'list'});
		else
			this.sidebarService.updateLinks({'activeLink':'institutePosting','subLink':'list'});
	}	
}