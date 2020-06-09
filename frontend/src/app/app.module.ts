import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { MatToolbarModule } from "@angular/material/toolbar";
import { MatIconModule } from "@angular/material/icon";
import { MatButtonModule } from "@angular/material/button";
import { RouterModule } from "@angular/router"
import { MatCardModule } from '@angular/material/card'; 
import { MatTableModule } from "@angular/material/table";
import { MatDialogModule } from "@angular/material/dialog";
import { MatFormFieldModule } from "@angular/material/form-field"
import { MatInputModule } from "@angular/material/input";
import { MatSelectModule } from '@angular/material/select'; 
import { HttpClientModule} from "@angular/common/http";
import { FormsModule } from "@angular/forms";

import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NavbarComponent } from './components/shared/navbar/navbar.component';
import { ListStudentComponent } from './components/students/list-student/list-student.component';
import { ListCourseComponent } from './components/courses/list-course/list-course.component';
import { AddCourseComponent } from './components/courses/list-course/list-course.component';
import { AddStudentComponent } from './components/students/list-student/list-student.component';

import { ROUTES } from "./app.routes";

@NgModule({
  declarations: [
    AppComponent,
    NavbarComponent,
    ListStudentComponent,
    ListCourseComponent,
    AddCourseComponent,
    AddStudentComponent
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    MatToolbarModule,
    MatIconModule,
    MatButtonModule,
    RouterModule.forRoot(ROUTES, {useHash: true}),
    MatCardModule,
    MatTableModule,
    MatFormFieldModule,
    MatDialogModule,
    MatInputModule,
    MatSelectModule,
    HttpClientModule,
    FormsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
