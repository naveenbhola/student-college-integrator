import {RouterConfig} from '@angular/router';

import { HierarchyListComponent } from './hierarchy-list.component';
import { HierarchyAddComponent } from './hierarchy-add.component';
import { HierarchyHomeComponent } from './hierarchy-home.component';
import { UserGuard } from '../Common/services/userGuard';
import { ListingSpecialUsersGuard } from '../Listing/CoursePosting/services/coursePostingGuards';

export const HIERARCHYROUTES: RouterConfig = [
	{
		path: 'cmsPosting/hierarchyPosting',
		resolve: {userData:UserGuard},
		component: HierarchyHomeComponent,
		children:[
			{ path: 'viewList', component: HierarchyListComponent, },
			{ path: 'create', component: HierarchyAddComponent,resolve:{ListingSpecialUsersGuard} },
			{ path: '', redirectTo:'/cmsPosting/hierarchyPosting/viewList', }
		]
	},
];