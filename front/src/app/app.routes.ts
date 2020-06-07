import { Routes } from "@angular/router"

import {HomeComponent} from "./components/home/home.component";
import {ListStudentComponent} from "./components/students/list-student/list-student.component";
import {ListCourseComponent} from "./components/courses/list-course/list-course.component";
import {AddCourseComponent} from "./components/courses/add-course/add-course.component";
import {AddStudentComponent} from "./components/students/add-student/add-student.component";
import {EditCourseComponent} from "./components/courses/edit-course/edit-course.component";
import {EditStudentComponent} from "./components/students/edit-student/edit-student.component";

export const ROUTES: Routes = [
	{ path: "home", component: HomeComponent },
	{ path: "student/add", component: AddStudentComponent },
	{ path: "student/:id/edit", component: EditStudentComponent },
	{ path: "student", component: ListStudentComponent },
	{ path: "course/add", component: AddCourseComponent },
	{ path: "course/:id/edit", component: EditCourseComponent },
	{ path: "course", component: ListCourseComponent },
	{ path: "", pathMatch: 'full', redirectTo: 'home' },
	{ path: "**", pathMatch: 'full', redirectTo: 'home' }
];