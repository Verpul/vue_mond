<?php

namespace App\Http\Controllers\API;

use App\Todo;
use App\Todo_step;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Todo::where('active', true)
                ->with('steps')
                ->orderByRaw('ISNULL(due_date), due_date ASC')
                ->paginate(10);
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
            'title' => 'required|string|max:150',
            'task' => 'required|string',
            'due_date' => 'date|nullable'
        ]);

        Todo::create([
            'title' => $request['title'],
            'task' => $request['task'],
            'active' => true,
            'due_date' => $request['due_date'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|string|max:150',
            'task' => 'required|string',
            'due_date' => 'date|nullable'
        ]);

        $todo->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();
    }

    // finish Todo task
    public function finishTodo($id){
        $todo = Todo::findOrFail($id);

        $todo->update(['active' => false]);
    }

    public function addStep(Request $request, $id){

        $this->validate($request, [
            'step' => 'required|string',
            'todo_id' => 'required|numeric',
        ]);

        Todo_step::create([
            'step' => $request['step'],
            'todo_id' => $request['todo_id'],
        ]);
    }

    public function deleteStep($id)
    {
        $todo = Todo_step::findOrFail($id);
        $todo->delete();
    }

    public function loadSteps(){
        $todoId = \Request::get('todoId');
        
        $steps = Todo_step::where('todo_id', $todoId)
                                ->orderBy('id')
                                ->get();

        return $steps;
    }
}
