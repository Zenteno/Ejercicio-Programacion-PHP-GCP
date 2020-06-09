import {
	Component,
	OnInit,
	Inject
} from '@angular/core';
import {
	MatDialog,
	MatDialogRef,
	MAT_DIALOG_DATA
} from '@angular/material/dialog';
import {
	ApiService
} from "../../../service/api.service";

class DialogData {
	id: number;
	name: string;
	code: string;
}

@Component({
	selector: 'app-list-course',
	templateUrl: './list-course.component.html',
	styleUrls: ['./list-course.component.css']
})
export class ListCourseComponent implements OnInit {

	courses: any = [];
	columnsToDisplay = ['name', "code", "id"];
	constructor(private api: ApiService, private dialog: MatDialog) {}

	openDialog(data: any = null): void {
		const dialogRef = this.dialog.open(AddCourseComponent, {
			width: '250px',
			data: data == null ? new DialogData() : data
		});

		dialogRef.afterClosed().subscribe(result => {
			if (result != undefined) {
				if (!("id" in result)) {
					this.api.addCourse(result).subscribe(x => {
						this.ngOnInit();
					});
				} else {
					this.api.editCourse(result["id"], result).subscribe(x => {
						this.ngOnInit();
					});
				}
			}

		});

	}

	ngOnInit(): void {
		this.api.listCourses().subscribe(c => {
			this.courses = c;
		})
	}
	edit(id): void {
		const course = this.courses.find((x) => x.id == id);
		this.openDialog({ ...course});
	}

}


@Component({
	selector: 'add-course-dialog',
	templateUrl: 'add-course.html',
})
export class AddCourseComponent {

	constructor(
		public dialogRef: MatDialogRef < AddCourseComponent > ,
		@Inject(MAT_DIALOG_DATA) public data: DialogData) {}

	onNoClick(): void {
		this.dialogRef.close();
	}

}