import {RouterConfig} from '@angular/router';

import { BasecourseListComponent } from './basecourse-list.component';
import { BasecourseAddComponent } from './basecourse-add.component';
import { BasecourseHomeComponent } from './basecourse-home.component';
import { UserGuard } from '../Common/services/userGuard';
import { ListingSpecialUsersGuard } from '../Listing/CoursePosting/services/coursePostingGuards';

export const BASECOURSEROUTES: RouterConfig = [
	{
		path: 'cmsPosting/basecoursePosting',
		resolve: {userData:UserGuard},
		component: BasecourseHomeComponent,
		children:[
			{ path: 'viewList', component: BasecourseListComponent, },
			{ path: 'create', component: BasecourseAddComponent,resolve:{ListingSpecialUsersGuard} },
			{ path: 'create/:id', component: BasecourseAddComponent,resolve:{ListingSpecialUsersGuard} },
			{ path: '', component: BasecourseListComponent,}
		]
	},
];