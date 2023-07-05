<?php

namespace App\Http\Controllers;

use App\Models\ToDoList;
use App\Http\Controllers\Controller;
use App\Http\Resources\ToDoListResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ToDoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = ToDoList::get()->where('user_id', auth()->user()->id);
        if (is_null($lists)) {
            return response()->json(['message' => 'you dont have any to do lists'], 404);
        }
        return response()->json(['to_do_lists' => ToDoListResource::collection($lists)]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'category' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category = Category::get()->where('name', $request->category)->first();

        if (is_null($category)) {
            return response()->json(['message' => 'category you entered does not exist'], 404);
        }

        $list = ToDoList::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'category_id' => $category->id,
        ]);

        return response()->json(['message' => 'to do list created successfully', 'to_do_list' => new ToDoListResource($list)], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ToDoList  $toDoList
     * @return \Illuminate\Http\Response
     */
    public function show($list_id)
    {
        $list = ToDoList::get()->where('id', $list_id)->first();
        // return $list;
        if (is_null($list)) {
            return response()->json(['message' => 'to do list not found'], 404);
        }

        if ($list->user_id != auth()->user()->id) {
            return response()->json(['message' => 'only author can access the to do list'], 403);
        }

        return response()->json(['to_do_list' => new ToDoListResource($list)], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ToDoList  $toDoList
     * @return \Illuminate\Http\Response
     */
    public function destroy($list_id)
    {
        $list = ToDoList::get()->where('id', $list_id)->first();
        if (is_null($list)) {
            return response()->json(['message' => 'to do list not found'], 404);
        }

        if ($list->user_id != auth()->user()->id) {
            return response()->json(['message' => 'only author can delete the to do list'], 403);
        }

        $list->delete();
        return response()->json(['message' => 'to do list is successfully deleted'], 200);
    }
}
