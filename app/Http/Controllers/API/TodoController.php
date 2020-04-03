<?php

namespace App\Http\Controllers\API;

use App\Todo;
use App\Todo_step;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $options = json_decode(\Request::get('options'), true);
        $limit = $options['limit'];
        $showCompleted = $options['showCompleted'];


        $data =  Todo::with('steps');
        if(!$showCompleted){
            $data->where('active', true);
        };
        return $data->orderByRaw('ISNULL(due_date), due_date ASC')
                    ->paginate($limit);
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
            'due_date' => 'date|nullable',
            'filesData.filesToSave.*' => 'file|mimes:pdf,jpeg,doc,docx,xls,xlsx,png|nullable'
        ]);

        $files = []; 
        if($request['filesData.filesToSave']){
            $path = time(now());
            $files['path'] = $path;

            foreach ($request['filesData.filesToSave'] as $index => $file) {
                $filename = $file->getClientOriginalName();

                $files['filenames'][$index] = ['name' => $filename];

                Storage::disk('public')->put($path.'/'.$filename, \File::get($file));
            }
        }

        Todo::create([
            'title' => $request['title'],
            'task' => $request['task'],
            'active' => true,
            'due_date' => $request['due_date'],
            'files' => json_encode($files),
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
            'due_date' => 'date|nullable',
            'filesData.filesToSave.*' => 'file|mimes:pdf,jpeg,doc,docx,xls,xlsx,png|nullable'
        ]);

        $files = []; 
        if($request['filesData.filesToSave']){
            $files = json_decode($todo['files'], true); 

            foreach ($request['filesData.filesToSave'] as $index => $file) {
                $filename = $file->getClientOriginalName();

                //Добавляем у уже созданному, либо создаем
                if(!empty($files)){
                    //Существует ли файл с таким именем
                    $filename = $this->checkName($filename, $files['filenames']);

                    array_push($files['filenames'], ['name' => $filename]);
                }else{
                    $files['filenames'][$index] = ['name' => $filename];
            };

            //Либо существующий, либо новый путь
            $path = '';
            if(array_key_exists('path', $files)){
                $path = $files['path'];
            }else{
                $path = time(now());
                $files['path'] = $path;
            };

                Storage::disk('public')->put($path.'/'.$filename, \File::get($file));
            };
        };

        $todo->update([
            'title' => $request['title'],
            'task' => $request['task'],
            'due_date' => $request['due_date'],
            'files' => json_encode($files),
        ]);
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

        //Удаляем все файлы, связанные с этой задачей
        $files = json_decode($todo['files'], true);
        if(!empty($files)){
            Storage::disk('public')->deleteDirectory($files['path']); 
        }

        $todo->delete();
    }

    // finish Todo task
    public function finishTodo($id){
        $todo = Todo::findOrFail($id);

        $todo->update(['active' => false]);
    }

    public function addStep(Request $request){

        $this->validate($request, [
            'step' => 'required|string',
            'todo_id' => 'required|numeric',
        ]);

        Todo_step::create([
            'step' => $request['step'],
            'todo_id' => $request['todo_id'],
            'active' => true
        ]);
    }

    public function deleteStep($id)
    {
        $step = Todo_step::findOrFail($id);
        $step->delete();
    }

    public function loadSteps(){
        $todoId = \Request::get('todoId');
        
        $steps = Todo_step::where('todo_id', $todoId)
                                ->orderBy('id')
                                ->get();

        return $steps;
    }

    public function updateStep(Request $request, $id)
    {
        $step = Todo_step::findOrFail($id);

        $this->validate($request, [
            'step' => 'required|string',
            'todo_id' => 'required|numeric',
        ]);

        $step->update($request->all());
    }

    public function changeStepStatus($id){
        $step = Todo_step::findOrFail($id);
        $currentStatus = \Request::get('currentStatus');

        $step->update(['active' => $currentStatus]);
    }

    public function downloadFile($folder = null, $file = null){
        $exists = Storage::disk('public')->exists($folder . '/' . $file);
        if($exists){
            return Storage::disk('public')->download($folder . '/' . $file);
        }
        
    }

    //Проверка уникальности имени файла
    public function checkName(&$filename, $files){
        //Есть ли в нашем массиве такое же имя
        if(array_search($filename, array_column($files, 'name')) !== false){
            //Получение индекса строки, к которой будет прибавлен номер
            $fileNameArr = explode('.', $filename);
            $fileLastPiece = count($fileNameArr)-2;
            //Прибавляем к имени файла нужную порядковую цифру
            if(strpos($fileNameArr[$fileLastPiece], '_') !== false){
                //Получение последней цифры файла
                $lastPieceArr = explode('_', $fileNameArr[$fileLastPiece]);
                $number = end($lastPieceArr);
                //Увеличиваем значение и склеиваем строку
                $lastPieceArr[count($lastPieceArr) - 1] = intval($number + 1);
                $lastPieceArr = implode('_', $lastPieceArr);
                $fileNameArr[$fileLastPiece] = $lastPieceArr;
            //Если это первый повтор
            }else{
                $fileNameArr[$fileLastPiece] = $fileNameArr[$fileLastPiece] . '_' . 1;
            }
            $filename = implode('.', $fileNameArr);
            //Проверяем новое имя
            $this->checkName($filename, $files);
        }
        return $filename;
    }
}
