import { Component} from '@angular/core';
import { listingContentComponent } from './listingContentPosting.component';

@Component({
	selector: 'featuredListingContent',
	template:'<listingContent [pageTitle]="pageTitle"></listingContent>',
	directives:[listingContentComponent]

})
export class featuredListingContent{

	pageTitle = 'Featured';
}
