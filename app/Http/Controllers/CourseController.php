<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Course;

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
     * Store a newly created course in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data = Course::create($request->all());
        return response([],201);
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
            return response([],404);
        else
            $course->fill($request->all());
        return response([],200);
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
            return response([],404);
        $course->delete();
        return response([],200);
    }
}