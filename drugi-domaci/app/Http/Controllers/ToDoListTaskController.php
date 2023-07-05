<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\ToDoList;
use Database\Factories\TaskFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ToDoListTaskController extends Controller
{

    public function store(Request $request, $to_do_list_id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            'due_date' => ['date', 'after:yesterday'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $list = ToDoList::find($to_do_list_id);
        if (is_null($list)) {
            return response()->json(['message' => 'to do list not found'], 404);
        }

        if ($list->user_id != auth()->user()->id) {
            return response()->json(['message' => 'only author can add task to to do list'], 403);
        }

        $task = Task::create([
            'to_do_list_id' => $to_do_list_id,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'done' => 'false'
        ]);

        return response()->json(['message' => 'tast successfully created', 'task' => new TaskResource($task)], 200);
    }

    public function update(Request $request, $to_do_list_id, $task_id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'string',
            'due_date' => ['date', 'after:yesterday'],
            'done' => ['string', 'in:true,false'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $list = ToDoList::find($to_do_list_id);
        if (is_null($list)) {
            return response()->json(['message' => 'to do list not found'], 404);
        }

        $task = Task::get()->where('to_do_list_id', $to_do_list_id)->where('id', $task_id)->first();
        if (is_null($task)) {
            return response()->json(['message' => 'task not found'], 404);
        }

        if ($list->user_id != auth()->user()->id) {
            return response()->json(['message' => 'only author can update task from to do list'], 403);
        }

        if (!(is_null($request->description))) {
            $task->description = $request->description;
        }
        if (!(is_null($request->due_date))) {
            $task->due_date = $request->due_date;
        }
        if (!(is_null($request->done))) {
            $task->done = $request->done;
        }

        $task->save();
        return response()->json(['message' => 'task successfully changed', 'task' => new TaskResource($task)], 200);
    }

    public function destroy($to_do_list_id, $task_id)
    {
        $list = ToDoList::find($to_do_list_id);
        if (is_null($list)) {
            return response()->json(['message' => 'to do list not found'], 404);
        }

        $task = Task::get()->where('to_do_list_id', $to_do_list_id)->where('id', $task_id)->first();
        if (is_null($task)) {
            return response()->json(['message' => 'task not found'], 404);
        }

        if ($list->user_id != auth()->user()->id) {
            return response()->json(['message' => 'only author can delete task from to do list'], 403);
        }

        $task->delete();
        return response()->json(['message' => 'task successfully deleted'], 200);
    }
}
