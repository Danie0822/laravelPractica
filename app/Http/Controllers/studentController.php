<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {   // Obtener todos los estudiantes
        $students = Student::all();
        // Si no se encontraron estudiantes
        if ($students->isEmpty()) {
            return response()->json(['message' => 'No se encontraron estudiantes'], 404);
        }
        return response()->json($students, 200);
    }

    public function store(Request $request)
    { 
        try {
            // Validar los datos de la solicitud
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:student',
                'phone' => 'required|string|max:20',
                'language' => 'required|in:es,en'
            ]);
            // Si la validación falla, devolver un error 400
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 400);
            }
            // Crear un nuevo estudiante con los datos de la solicitud
            $student = Student::create($request->all());
            $data = [
                'message' => 'Estudiante creado',
                'student' => $student
            ];
            // Devolver una respuesta 201 con los datos del estudiante creado
            return response()->json($data, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear el estudiante'], 500);
        }
    }

    public function show($id)
    {
        try {
            // Encontrar un estudiante por su ID
            $student = Student::find($id);
            if ($student == null) {
                return response()->json(['message' => 'No se encontró el estudiante'], 404);
            }
            // Devolver una respuesta 200 con los datos del estudiante
            return response()->json($student, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Hubo un error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Encontrar un estudiante por su ID
            $student = Student::find($id);
            if ($student == null) {
                return response()->json(['message' => 'No se encontró el estudiante'], 404);
            }
            // Eliminar el estudiante
            $student->delete();
            return response()->json(['message' => 'Estudiante eliminado'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Hubo un error: ' . $e->getMessage()], 500);
        }
    }
    public function update(Request $request)
    {
        try {
            // Validar que el ID esté presente en el cuerpo de la solicitud
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:student,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:student,email,' . $request->id,
                'phone' => 'required|string|max:20',
                'language' => 'required|in:es,en'
            ]);
            // Si la validación falla, devolver un error 400
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 400);
            }
    
            // Encontrar el estudiante usando el ID proporcionado en el cuerpo
            $student = Student::find($request->id);
            if ($student == null) {
                return response()->json(['message' => 'No se encontró el estudiante'], 404);
            }
    
            // Actualizar el estudiante con los datos del cuerpo
            $student->update($request->all());
    
            $data = [
                'message' => 'Estudiante actualizado',
                'student' => $student
            ];
            return response()->json($data, 200);
    
        } catch (\Exception $e) {
            return response()->json(['message' => 'Hubo un error: ' . $e->getMessage()], 500);
        }
    }
    
}
