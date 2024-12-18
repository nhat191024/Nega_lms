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
        $assignments = Assignment::all();
        // dd($assignmentsGroupBy);
        // Trả về view với dữ liệu bài tập
        return view('assignments.index', compact('assignments'));
    }

    public function getAssignments($id)
    {
        $assignmentsGroupBy = Assignment::where('class_id', $id)->with('class')->get()->groupBy('class_id');
        // Trả về view với dữ liệu bài tập
        return view('assignments.index', compact('assignmentsGroupBy'));
    }


    public function create()
    {
        $assignments = Assignment::all();
        $classes = Classes::all();
        $users = User::all();
        return view('assignments.create', compact('assignments', 'classes', 'users'));
    }

    public function store(Request $request)
    {
        // dd('1');
        // Validate dữ liệu
        Log::info('Validation', $request->all());
        $valid = Validator::make($request->all(), [
            'creator_id' => 'required',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:closed,published,private,draft',
            'level' => 'required|string',
            'totalScore' => 'required',
            'specialized' => 'required|string',
            'subject' => 'required|string',
            'topic' => 'required|string',

        ]);
        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        }

        // Lưu vào database

        try {
            Assignment::create([

                'creator_id' => $request->creator_id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'level' => $request->level,
                'totalScore' => $request->totalScore,
                'specialized' => $request->specialized,
                'subject' => $request->subject,
                'topic' => $request->topic,

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
        return view('assignments.edit', compact('assignment', 'classes', 'users'));
    }

    // Update method
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'creator_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'level' => 'required|string',
            'totalScore' => 'required|integer|min:0',
            'specialized' => 'required|string',
            'subject' => 'required|string',
            'topic' => 'required|string',


        ]);

        $assignment = Assignment::findOrFail($id);
        $assignment->update([
            'creator_id' => $request->creator_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'level' => $request->level,
            'totalScore' => $request->totalScore,
            'specialized' => $request->specialized,
            'subject' => $request->subject,
            'topic' => $request->topic,
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
    // public function getAssignmentsByClass($class_id)
    // {
    //     // Lấy danh sách bài tập theo class_id và nhóm theo class_id
    //     $assignments = Assignment::where('class_id', $class_id)->with(['class', 'creator'])->get();
    //     $assignmentsGroupBy = $assignments->groupBy('class_id');

    //     return view('assignments.index', compact('assignmentsGroupBy'));
    // }
    public function toggleVisibility($id)
    {
        $assignment = Assignment::findOrFail($id);

        $assignment->visibility = !$assignment->visibility;
        $assignment->save();

        return redirect()->route('assignments.index')->with('success', 'Trạng thái hiển thị đã được cập nhật!');
    }


}
