<?php

namespace App\Http\Controllers;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Assignment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpParser\Builder\Class_;
use PhpParser\Node\Expr\Assign;

class AssignmentController extends Controller
{
    public function index()
    {
        // Lấy tất cả bài tập từ cơ sở dữ liệu
        $assignmentsGroupBy = Assignment::with('class')->get()->groupBy('class_id');
        // Trả về view với dữ liệu bài tập
        return view('assignments.index', compact('assignmentsGroupBy',));
    }

    public function getAssignments($id) {
        $assignmentsGroupBy = Assignment::where('class_id', $id)->with('class')->get()->groupBy('class_id');
        // Trả về view với dữ liệu bài tập
        return view('assignments.index', compact('assignmentsGroupBy'));
    }


public function create() {
    $assignments = Assignment::all();
    $classes = Classes::all();
    $users = User::all();
    return view('assignments.create', compact('assignments', 'classes','users'));
}

public function store(Request $request)
{
    // dd('1');
    // Validate dữ liệu
    Log::info('Validation', $request->all());
    $valid = Validator::make($request->all(), [
            'class_id' => 'required',
            'creator_id' => 'required',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:closed,published,private,draft',
            'level' => 'required|string',
            'duration' => 'required',
            'totalScore' => 'required',
            'specialized' => 'required|string',
            'subject' => 'required|string',
            'topic' => 'required|string',
            'start_date' => 'required',
            'due_date' => 'required',
            'auto_grade' => 'required',
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        }

    // Lưu vào database

    try {
        Assignment::create([
            'class_id' => $request->class_id,
            'creator_id' => $request->creator_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'level' => $request->level,
            'duration' => $request->duration,
            'totalScore' => $request->totalScore,
            'specialized' => $request->specialized,
            'subject' => $request->subject,
            'topic' => $request->topic,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'auto_grade' => $request->auto_grade,
        ]);
        return redirect()->back()->with('success', 'Assignment created successfully.');
    } catch (\Throwable $th) {
        return redirect()->back()->with('error', 'Failed to create assignment.');
    }


}


public function edit($id)
{
    $assignment = Assignment::findOrFail($id);
    $classes = Classes::all();
    $users = User::all();
    return view('assignments.edit', compact('assignment', 'classes','users'));
}

// Update method
public function update(Request $request, $id)
{
    // Validation
    $request->validate([
         'class_id' => 'required|exists:classes,id',
        'creator_id' => 'required|exists:users,id',
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'status' => 'required|string',
        'level' => 'required|string',
        'duration' => 'required|integer|min:1',
        'totalScore' => 'required|integer|min:0',
        'specialized' => 'required|string',
        'subject' => 'required|string',
        'topic' => 'required|string',
        'start_date' => 'required|date',
        'due_date' => 'required|date',
        'auto_grade' => 'required|boolean',

    ]);

    $assignment = Assignment::findOrFail($id);
    $assignment->update([
        'class_id' => $request->class_id,
        'creator_id' => $request->creator_id,
        'name' => $request->name,
        'description' => $request->description,
        'status' => $request->status,
        'level' => $request->level,
        'duration' => $request->duration,
        'totalScore' => $request->totalScore,
        'specialized' => $request->specialized,
        'subject' => $request->subject,
        'topic' => $request->topic,
        'start_date' => $request->start_date,
        'due_date' => $request->due_date,
        'auto_grade' => $request->auto_grade,
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
public function getAssignmentsByClass($class_id)
{
    // Lấy bài tập theo class_id
    $assignments = Assignment::where('class_id', $class_id)->get();

    // Kiểm tra nếu không có bài tập
    if ($assignments->isEmpty()) {
        return redirect()->back()->with('error', 'Không có bài tập nào cho lớp học này.');
    }

    // Trả về view hiển thị bài tập
    return view('assignments.index', compact('assignments'));
}

}
