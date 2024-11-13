<?php

namespace App\Http\Controllers;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Models\Assignment;
use PhpParser\Builder\Class_;
use PhpParser\Node\Expr\Assign;

class AssignmentController extends Controller
{
public function index()
{
    $assignments = Assignment::all();

    return view('Assignments.index', compact('assignments'));
}


public function create() {
    $assignments = Assignment::all();
    $classes = Classes::all();
    return view('assignments.create', compact('assignments', 'classes'));
}

public function store(Request $request)
{
    // Validation
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'due_date' => 'required|date',
        'auto_grade' => 'required|boolean',
        'class_id' => 'required|exists:classes,id',  
    ]);

    // Lưu dữ liệu vào database
    $classes = Assignment::create([
        'title' => $request->title,
        'description' => $request->description,
        'due_date' => $request->due_date,
        'auto_grade' => $request->auto_grade,
        'class_id' => $request->class_id,  
    ]);
    return redirect()->route('assignments.index')->with('success', 'Assignment created successfully.');
}

public function edit($id)
{
    $assignment = Assignment::findOrFail($id);
    $classes = Classes::all();
    return view('assignments.edit', compact('assignment', 'classes'));
}

// Update method
public function update(Request $request, $id)
{
    // Validation
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'due_date' => 'required|date',                         
        'auto_grade' => 'required|boolean',
        'class_id' => 'required|exists:classes,id',
    ]);

    $assignment = Assignment::findOrFail($id);
    $assignment->update([
        'title' => $request->title,
        'description' => $request->description,
        'due_date' => $request->due_date,
        'auto_grade' => $request->auto_grade,
        'class_id' => $request->class_id,
    ]);

    return redirect()->route('assignments.index')->with('success', 'Assignment updated successfully.');
}

// Delete method
public function destroy($id)
{
    $assignment = Assignment::findOrFail($id);
    $assignment->delete();

    return redirect()->route('assignments.index')->with('success', 'Assignment deleted successfully.');
}


}
