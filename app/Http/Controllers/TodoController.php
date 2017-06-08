<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()){
            $todo = Todo::all();
        }
        return response()->json(['status' => 'success','result' => $todo]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'todo' => 'required',
            'description' => 'required',
            'category' => 'required'
        ]);

        $todo = $request->input('todo');
        $description = $request->input('description');
        $category = $request->input('category');
        $user_id = $request->input('user_id');

        if(Auth::user()){


            $data = Todo::create([
                 'todo' => $todo,
                 'description' => $description,
                 'category' => $category,
                 'user_id' => $user_id
             ]);

            if($data){
                return response()->json(['status' => 'success', 'data' => $data]);
            }
            else{
                return response()->json(['status' => 'fail']);
            }

        }else{
            return response()->json(['status' => 'not authenticated']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todo = Todo::where('id', $id)->get();
        return response()->json($todo);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $todo = Todo::where('id', $id)->get();

        return view('todo.edittodo',['todos' => $todo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'todo' => 'filled',
            'description' => 'filled',
            'category' => 'filled'
        ]);
        $todo = Todo::find($id);
        if($todo->fill($request->all())->save()){
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failed']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Todo::destroy($id)){
            return response()->json(['status' => 'success']);
        }
    }
}