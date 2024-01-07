<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Dotenv\Exception\ValidationException;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::orderBy('id', 'desc')->get();

        if ($students->isEmpty()) {
            return response()->json([
                'success'  => false,
                'message'  => 'No records found',
                'students' => []
            ], 200);
        } else {
            return response()->json([
                'success'  => true,
                'message'  => 'Student List',
                'students' => $students
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|max:255|regex:/^[a-zA-Z\s]+$/',
            'course' => 'required|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|digits:11|unique:students,phone'
        ]);

        $student = Student::create($validated);

        if ($student) {
            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Student creation failed',
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::find($id);

        if ($student) {
            return response()->json([
                'success' => true,
                'message' => 'Student details',
                'student' => $student
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No student records found",
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::find($id);

        $validated = $request->validate([
            'name'  => 'sometimes|max:255|regex:/^[a-zA-Z\s]+$/',
            'course' => 'sometimes|max:255',
            'email' => 'sometimes|email|unique:students,email',
            'phone' => 'sometimes|digits:11|unique:students,phone'
        ]);

        if ($validated) {
            $student->update($validated);
            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully',
                'student' => $student
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Student update failed'
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);

        if ($student) {
            $student->delete();
            return response()->json([
                'success' => true,
                'message' => 'Student with id of ' . $id . ' deleted successfully',
            ], 204);
        }
    }
}
