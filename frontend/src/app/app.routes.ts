import { Routes } from "@angular/router"
import { ListStudentComponent } from "./components/students/list-student/list-student.component";
import { ListCourseComponent } from "./components/courses/list-course/list-course.component";

export const ROUTES: Routes = [
	{ path: "home", component: ListStudentComponent },
	{ path: "courses", component: ListCourseComponent },
	{ path: "students", component: ListStudentComponent },
	{ path: "", pathMatch: 'full', redirectTo: 'home' },
	{ path: "**", pathMatch: 'full', redirectTo: 'home' }
];