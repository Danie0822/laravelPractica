<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profesor;
use Illuminate\Support\Facades\Validator;
use App\Responses\ResponseMessages;

class profesorController extends Controller
{
    public function index(){
        $profesores = Profesor::all();
        if ($profesores->isEmpty()) {
            return ResponseMessages::error('No se encontraron profesores', 404);
        }
        return ResponseMessages::success($profesores, 'Operación exitosa', 200);
    }

    public function store(Request $request){
        try {
            $validator = $this->validateProfesor($request);
            if ($validator->fails()) {
                return ResponseMessages::error('Error al validar los datos', 400);
            }
            $profesor = Profesor::create($request->only(['nombre', 'apellido', 'email', 'telefono', 'especialidad', 'clave']));
            return ResponseMessages::success($profesor, 'Profesor creado', 200);
        } catch (\Exception $e) {
            return ResponseMessages::error('Hubo un error', 500, $e);
        }
    }

    public function show($id){
        try {
            $profesor = Profesor::findOrFail($id);
            return ResponseMessages::success($profesor, 'Operación exitosa', 200);
        } catch (\Exception $e) {
            return ResponseMessages::error('Hubo un error', 500, $e);
        }
    }

    public function destroy($id){
        try{
            $profesor = Profesor::findOrFail($id);
            $profesor->delete();
            return ResponseMessages::success(null, 'Profesor eliminado', 200);
        }
        catch(\Exception $e){
            return ResponseMessages::error('Hubo un error', 500, $e);
        }
    }
    
    public function update(Request $request){
        try{
            $validate = $this->validateProfesor($request, $request->id);
            if($validate->fails()){
                return ResponseMessages::error('Error al validar los datos', 400);
            }
            $profesor = Profesor::findOrFail($request->id);
            if($profesor == null){
                return ResponseMessages::error('Profesor no encontrado', 404);
            }
            $profesor->update($request->only(['nombre', 'apellido', 'email', 'telefono', 'especialidad', 'clave']));
            return ResponseMessages::success($profesor, 'Profesor actualizado', 200);
        }
        catch(\Exception $e){
            return ResponseMessages::error('Hubo un error', 500, $e);
        }
    }

    private function validateProfesor(Request $request, $id = null)
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:profesor,email,' . $id . ',id',  // Correcta validación de email con exclusión del ID
            'telefono' => 'required|string|max:20',
            'especialidad' => 'required|in:matemáticas,física,química,biología, software',
            'clave' => 'required|string|max:255'
        ];
        if($id == null){
            $rules['id'] = 'required|exists:student,id';  // Validar que el ID exista
        }

        return Validator::make($request->all(), $rules);
    }
}
