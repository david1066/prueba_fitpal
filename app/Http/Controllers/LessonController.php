<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        /*
        1. Al momento de consultar las clases disponibles el sistema solo deberá retornar
          clases que inicien máximo 8 días después.
        3.   El llamado de clases debe estar paginado.
          */
        //ruta api/lesson?page=1
        //cargamos los horarios en la misma consulta con el load haciendo referencia a la relacion del modelo
        $lessons = Lesson::whereraw('DATEDIFF(date_start,NOW())<=8')->paginate(20)->load('schedule');

        return response()->json(['code' => 200, 'status' => 'success', 'lessons' => $lessons], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          //recoger datos por lesson
          //ejemplo json={"lesson_name": "Gimnasio","date_start": "2022-08-01","date_end": "2022-09-11"}
          $json = $request->input('json', null);
          $params = json_decode($json);
          $params_array = json_decode($json, true);
  
          if (!empty($params_array)) {
              
              //validar los datos
              $validate = \Validator::make($params_array, [
                  'lesson_name' => 'required',
                  'date_start' => 'required|date|date_format:Y-m-d',
                  'date_end' => 'required|date|date_format:Y-m-d'
              ]);
              if ($validate->fails()) {
                  $data = [
                      'code' => 400,
                      'status' => 'error',
                      'message' => 'No se ha guardado el lesson, faltan datos',
                      'errors'=>$validate->errors()
                  ];
              } else {
                
                  $lesson = new Lesson();
                  $lesson->lesson_name = $params->lesson_name;
                  $lesson->date_start = $params->date_start;
                  $lesson->date_end = $params->date_end;
                  $lesson->save();
  
                  // guardar el articulo o Lesson
  
                  $data = [
                      'code' => 200,
                      'status' => 'success',
                      'Lesson' => $lesson
                  ];
                  // devolver respuesta
              }
          } else {
              $data = [
                  'code' => 400,
                  'status' => 'error',
                  'message' => 'envia los datos correctamente'
              ];
          }
          return response()->json($data, $data['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lessons  $lessons
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lessons  $lessons
     * @return \Illuminate\Http\Response
     */
    public function edit(Lesson $lesson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lessons  $lessons
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
          //recoger los datos por post
          $json = $request->input('json', null);
          $params_array = json_decode($json, true);
  
  
          $data = array('code' => 400, 'status' => 'error', 'message' => 'datos enviados incorrectamente');
  
        
          if (!empty($params_array) && is_numeric($id)) {
              //validar datos
              $validate = \Validator::make($params_array, [
                'lesson_name' => 'required',
                'date_start' => 'required|date|date_format:Y-m-d',
                'date_end' => 'required|date|date_format:Y-m-d'
             ]);
  
              if ($validate->fails()) {
                  $data['errors'] = $validate->errors();
                  return response()->json($data, $data['code']);
              }
              //elminar lo que no queremos actualizar
              unset($params_array['id']);
              unset($params_array['updated_at']);
              unset($params_array['created_at']);
              unset($params_array['deleted_at']);
  
              //conseguir usuario identificado
             //evitamos el sql injeccion col el id = ?
              $lesson = Lesson::whereraw('id = ?', $id)->first();

  
              if (!empty($lesson) && is_object($lesson)) {
                  $where = [
                      'id' => $id
                  ];
  
  
                  //actualizar el registro en concreto y devolver que cambios se hicieron
                  Lesson::updateOrCreate($where, $params_array);
                  $data = array('code' => 200, 'status' => 'success', 'lesson' => $lesson, 'changes' => $params_array);
              }
  
              //devolver algo
  
          }
          return response()->json($data, $data['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lessons  $lessons
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //eliminacion por softdeletes
        $data = [
            'code' => 404,
            'status' => 'error', 'message' => 'el lesson no existe'
        ];
        //obligamos a que sea numerico
        if(is_numeric($id)){
            //evitamos el sql injeccion col el id = ?
            $lesson = Lesson::whereraw('id = ?', $id)->first();
            if (!empty($lesson)) {
    
                //borrarlo
                $lesson->delete();
                //devolver algo
                $data = [
                    'code' => 200,
                    'status' => 'success', 'lesson' => $lesson
                ];
            } 
        }
        
        return response()->json($data, $data['code']);
    
    }
}
