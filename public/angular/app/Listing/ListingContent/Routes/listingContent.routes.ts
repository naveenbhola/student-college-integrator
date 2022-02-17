import {RouterConfig} from '@angular/router';

import { listingContentComponent } from '../Components/listingContentPosting.component';
import { featuredListingContent } from '../Components/featuredListingContent.component';
import { featuredArticleListingContent } from '../Components/featuredArticleListingContent.component';

export const LISTINGCONTENTROUTES: RouterConfig = [
	{
		path: 'nationalInstitute/InstitutePosting',
		children: [
			{ path: 'cmsPopularCourses', component: listingContentComponent, },
			{ path: 'cmsFeaturedCourses', component: featuredListingContent, },
			{ path: 'cmsFeaturedArticle', component: featuredArticleListingContent, }
		]
	},
];
