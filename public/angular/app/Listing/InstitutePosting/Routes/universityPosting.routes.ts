import {RouterConfig} from '@angular/router';

import { UniversityPostingListComponent } from '../../UniversityPosting/Components/university-posting-list.component';
import { UniversityPostingCreateComponent } from '../../UniversityPosting/Components/universityPostingCreate.component';

export const UNIVERSITYPOSTINGROUTES: RouterConfig = [
	{
		path: 'nationalInstitute/UniversityPosting',
		children: [
			{ path: 'viewList', component: UniversityPostingListComponent, },
			{ path: 'create', component: UniversityPostingCreateComponent, },
			{ path: 'create/:id', component: UniversityPostingCreateComponent, },
			{ path: '', pathMatch: 'full', redirectTo: 'viewList' }
		]
	},
];
