<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Course;
use App\Student;

class CourseController extends Controller
{
    /**
     * Retrieve the course for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $course = Course::find($id);
        return response($course, ($course!=null) ? 200:404)
                  ->header('Content-Type', 'application/json');
    }

    /**
     * Display a listing of courses.
     *
     * @return Response
     */
    public function index()
    {
        return Course::all();
    }


    /**
     * Display a listing of courses paginated.
     *
     * @return Response
     */
    public function indexPaginated(Request $request){
        $size = $request->input('size', 10);
        $page = $request->input('page',1);
        return Course::paginate($size,['*'],'page',$page);   
    }

    /**
     * Store a newly created course in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data = Course::create($request->all());
        return response(["msg"=>"created"],201);
    }

    
    /**
     * Update the specified course in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        if($course==null)
            return response(["msg"=>"not found"],404);
        else
            $course->update($request->all());
        return response(["msg"=>"successfully updated"],200);
    }


    /**
     * Remove the specified course from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $course = Course::find($id);
        if($course==null)
            return response(["msg"=>"not found"],404);
        $students = Student::where(["course"=>$id])->get();
        if(count($students)>0)
            return response(["msg"=>"this course has students. Can't delete it"],400);
        $course->delete();
        return response(["msg"=>"successfully deleted"],200);
    }
}