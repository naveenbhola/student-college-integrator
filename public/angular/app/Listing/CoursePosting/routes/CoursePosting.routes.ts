import {RouterConfig} from '@angular/router';

import { CoursePostingListComponent } from '../components/coursePostingList.component';
import { CoursePostingCreateComponent } from '../components/coursePostingCreate.component';
import { CoursePostingCreateTempComponent } from '../components/coursePostingCreateTemp.component';
import { CourseEditGuard, CourseCloneGuard, CourseDeactivateGuard, CourseStaticDataGuard, CourseHierarchiesGuard, CourseHierarchyTreeGuard } from '../services/coursePostingGuards';
import { CourseDependencyService } from '../services/course-dependencies.service';
import { CoursePostingService } from '../services/coursePosting.service';
import { ListingBaseService } from '../../../services/listingbase.service';
import { UserGuard } from '../../../Common/services/userGuard';

export const COURSEPOSTINGROUTES: RouterConfig = [
	{
		path: 'nationalCourse',
		resolve: {userData:UserGuard},
		children: [
			{
				path: 'CoursePosting',
				children: [
					{ path: 'viewList', component: CoursePostingListComponent, },
					{ path: 'create', component: CoursePostingCreateComponent, canDeactivate: [CourseDeactivateGuard], resolve: { staticData: CourseStaticDataGuard, hierarchyTree: CourseHierarchyTreeGuard, hierarchies: CourseHierarchiesGuard, cloneData: CourseCloneGuard } },
					{ path: 'edit/:id', component: CoursePostingCreateComponent, canDeactivate: [CourseDeactivateGuard], resolve: { courseData: CourseEditGuard, staticData: CourseStaticDataGuard, hierarchyTree: CourseHierarchyTreeGuard, hierarchies: CourseHierarchiesGuard } },
					{ path: '', pathMatch: 'full', redirectTo: 'viewList' },
					{ path: 'createTemp', component: CoursePostingCreateTempComponent, },
				]
			}
		]
	},
];

export const COURSEPOSTINGPROVIDERS = [CourseDependencyService, CourseDeactivateGuard, CourseEditGuard, CourseCloneGuard, CourseStaticDataGuard, CourseHierarchyTreeGuard, CourseHierarchiesGuard, CoursePostingService, ListingBaseService];
