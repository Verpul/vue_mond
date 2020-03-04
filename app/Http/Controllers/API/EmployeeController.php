<?php

namespace App\Http\Controllers\API;

use App\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Employee::all();
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
            'first_name' => 'required|string|min:2|max:100',
            'middle_name' => 'required|string|min:2|max:100',
            'last_name' => ['required','string','max:100', 'min:2',
                            Rule::unique('employees')->where(function($query) use($request){
                                return $query->where('first_name', $request->first_name)
                                ->where('middle_name', $request->middle_name);
                            })],
            'position' => 'required|string|max:100',
            'work_phone' => 'string|nullable',
            'mobile_phone' => 'string|nullable',
            'email' => 'string|email|nullable'
        ]);

        Employee::create([
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'],
            'last_name' => $request['last_name'],
            'position' => $request['position'],
            'work_phone' => $request['work_phone'],
            'mobile_phone' => $request['mobile_phone'],
            'email' => $request['email']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $this->validate($request, [
            'first_name' => 'required|string|min:2|max:100',
            'middle_name' => 'required|string|min:2|max:100',
            'last_name' => ['required','string','max:100', 'min:2',
                            Rule::unique('employees')->where(function($query) use($request){
                                return $query->where('first_name', $request->first_name)
                                ->where('middle_name', $request->middle_name);
                            })->ignore($id)],
            'position' => 'required|string|max:100',
            'work_phone' => 'string|nullable',
            'mobile_phone' => 'string|nullable',
            'email' => 'string|email|nullable'
        ]);

        $employee->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        $employee->delete();
    }
}
