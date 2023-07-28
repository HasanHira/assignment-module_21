<?php

namespace App\Http\Controllers;

use App\Models\TodoItem;
use Exception;
use Illuminate\Http\Request;

class TodoItemController extends Controller
{
    function ListTodoItems(Request $request){

        $user_id = $request->header('id');
        return TodoItem::where('user_id', $user_id)->get();

    }

    function CreateTodoItem(Request $request){

        try{
            $user_id = $request->header('id');
            TodoItem::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'user_id' => $user_id
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Item added in your todo list.'
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong'
            ]);
        }

    }

    function DeleteTodoItem(Request $request){

        try{
            $todo_item_id = $request->input('id');
            $user_id = $request->header('id');

            TodoItem::where('id', $todo_item_id)
            ->where('user_id', $user_id)
            ->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Item Deleted.'
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong'
            ]);
        }

    }

    function UpdateTodoItem(Request $request){

        try{
            $todo_item_id = $request->input('id');
            $user_id = $request->header('id');

            TodoItem::where('id', $todo_item_id)
            ->where('user_id', $user_id)
            ->update([
                'title'=>$request->input('title'),
                'description' =>$request->input('description'),
                'status' =>$request->input('status')
            ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Item updated.'
                ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong'
            ]);
        }

    }

}
