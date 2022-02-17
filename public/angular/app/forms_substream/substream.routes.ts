import {RouterConfig} from '@angular/router';

import { SubstreamListComponent } from './substream-list.component';
import { SubstreamAddComponent } from './substream-add.component';
import { SubstreamHomeComponent } from './substream-home.component';
import { UserGuard } from '../Common/services/userGuard';
import { ListingSpecialUsersGuard } from '../Listing/CoursePosting/services/coursePostingGuards';

export const SUBSTREAMROUTES: RouterConfig = [
	{
		path: 'cmsPosting/substreamPosting',
		component: SubstreamHomeComponent,
		resolve: {userData:UserGuard},
		children:[
			{ path: 'viewList', component: SubstreamListComponent, },
			{ path: 'create', component: SubstreamAddComponent,resolve:{ListingSpecialUsersGuard} },
			{ path: 'create/:id', component: SubstreamAddComponent,resolve:{ListingSpecialUsersGuard} },
			{ path: '', component: SubstreamListComponent}
		]
	},
];