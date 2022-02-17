import {Component} from '@angular/core';
import { listingContentComponent } from './listingContentPosting.component';
@Component({
	selector:'featuredArticle',
	template:'<listingContent [pageTitle]="pageTitle"></listingContent>',
	directives:[listingContentComponent]

})

export class featuredArticleListingContent{
	pageTitle = 'Article';
}