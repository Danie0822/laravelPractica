<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use App\Responses\ResponseMessages;

class StudentController extends Controller
{
    public function index()
    {   // Obtener todos los estudiantes
        $students = Student::all();
        // Si no se encontraron estudiantes
        if ($students->isEmpty()) {
            return ResponseMessages::error('No se encontraron estudiantes', 404);
        }
        return ResponseMessages::success($students, 'Operación exitosa', 200);
    }

    public function store(Request $request)
    { 
        try {
            $validator = $this->validateStudent($request);
            // Si la validación falla, devolver un error 400
            if ($validator->fails()) {
               return ResponseMessages::error('Error al validar los datos', 400);
            }
            // Crear un nuevo estudiante con los datos de la solicitud
            $student = Student::create($request->all());
            // Devolver una respuesta 200 con los datos del estudiante creado
            return ResponseMessages::success($student, 'Estudiante creado', 200);
        } catch (\Exception $e) {
            return ResponseMessages::error('Hubo un error', 500, $e);
        }
    }

    public function show($id)
    {
        try {
            // Encontrar un estudiante por su ID
            $student = Student::find($id);
            if ($student == null) {
                return ResponseMessages::error('No se encontró el estudiante', 404);
            }
            // Devolver una respuesta 200 con los datos del estudiante
            return ResponseMessages::success($student, 'Operación exitosa', 200);
        } catch (\Exception $e) {
            return ResponseMessages::error('Hubo un error', 500, $e);
        }
    }

    public function destroy($id)
    {
        try {
            // Encontrar un estudiante por su ID
            $student = Student::findOrFail($id);
            // Eliminar el estudiante
            $student->delete();
            return ResponseMessages::success(null, 'Estudiante eliminado', 200);
        } catch (\Exception $e) {
            return ResponseMessages::error('Hubo un error', 500, $e);
        }
    }
    public function update(Request $request)
    {
        try {
            $validator = $this->validateStudent($request, $request->id);
            // Si la validación falla, devolver un error 400
            if ($validator->fails()) {
                return ResponseMessages::error('Error al validar los datos', 400);
            }
            // Encontrar el estudiante usando el ID proporcionado en el cuerpo
            $student = Student::find($request->id);
            if ($student == null) {
                return ResponseMessages::error('No se encontró el estudiante', 404);
            }
            // Actualizar el estudiante con los datos del cuerpo
            $student->update($request->only(['name', 'email', 'phone', 'language']));
            return ResponseMessages::success($student, 'Estudiante actualizado', 200);
        } catch (\Exception $e) {
            return ResponseMessages::error('Hubo un error', 500, $e);
        }
    }
    // Validar los datos del estudiante
    private function validateStudent($request, $id = null)
    { 
        // Reglas de validación
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:student,email,' . $id . ',id',  // Correcta validación de email con exclusión del ID
            'phone' => 'required|string|max:20',
            'language' => 'required|in:es,en'
        ];
        // Si se proporciona un ID, asegurarse de que el ID exista en la base de datos
        if ($id != null) {
            $rules['id'] = 'required|exists:student,id';  // Validar que el ID exista
        }
        // Crear el validador
        $validator = Validator::make($request->all(), $rules);
        return $validator;
    }
    
}
