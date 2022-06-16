<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          //recoger datos por schedule
          //ejemplo json={"lesson_id": 2,"weekday":1,"time_start": "07:00","time_end": "12:00"}
          $json = $request->input('json', null);
          $params = json_decode($json);
          $params_array = json_decode($json, true);
  
          if (!empty($params_array)) {
              
              //validar los datos
              $validate = \Validator::make($params_array, [
                  'lesson_id' => 'required|integer',
                  'weekday' =>  'required|integer|min:1|max:7',
                  'time_start' => 'required|date_format:H:i',
                  'time_end' => 'required|date_format:H:i'
              ]);
              if ($validate->fails()) {
                  $data = [
                      'code' => 400,
                      'status' => 'error',
                      'message' => 'No se ha guardado el schedule, faltan datos',
                      'errors'=>$validate->errors()
                  ];
              } else {
                
                  $schedule = new Schedule();
                  $schedule->lesson_id = $params->lesson_id;
                  // lunes=1, martes=2, miercoles=3, jueves=4, viernes=5, sabado=6
                  $schedule->weekday = $params->weekday;
                  $schedule->time_start = $params->time_start;
                  $schedule->time_end = $params->time_end;
                  $schedule->save();
  
                  // guardar el articulo o schedule
  
                  $data = [
                      'code' => 200,
                      'status' => 'success',
                      'schedule' => $schedule
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
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
                'lesson_id' => 'required|integer',
                'weekday' => 'required|integer|min:1|max:7',
                'time_start' => 'required|date_format:H:i',
                'time_end' => 'required|date_format:H:i'
            ]);

            if ($validate->fails()) {
                $data['errors'] = $validate->errors();
                return response()->json($data, $data['code']);
            }
            //elminar lo que no queremos actualizar
            $lesson_id=$params_array['lesson_id'];
            unset($params_array['id']);
            unset($params_array['updated_at']);
            unset($params_array['created_at']);
            unset($params_array['deleted_at']);
            unset($params_array['lesson_id']);
            

            //conseguir usuario identificado
            //evitamos el sql injeccion col el id = ?
            $schedule = Schedule::whereraw('id = ?', $id)->first();


            if (!empty($schedule) && is_object($schedule)) {
                $where = [
                    'id' => $id,
                    'lesson_id'=>$lesson_id
                ];


                //actualizar el registro en concreto y devolver que cambios se hicieron
                Schedule::updateOrCreate($where, $params_array);
                $data = array('code' => 200, 'status' => 'success', 'schedule' => $schedule, 'changes' => $params_array);
            }

            //devolver algo

        }
        return response()->json($data, $data['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */


    public function destroy($id,$lesson_id)
    {
          //eliminacion por softdeletes
          $data = [
            'code' => 404,
            'status' => 'error', 'message' => 'el schedule no existe'
        ];
        //obligamos a que sea numerico
        if(is_numeric($id) && is_numeric($lesson_id)){
            //evitamos el sql injeccion col el id = ? and lesson_id = ?
            $schedule = Schedule::whereraw('id = ? and lesson_id = ?', [$id,$lesson_id])->first();
            if (!empty($schedule)) {
    
                //borrarlo
                $schedule->delete();
                //devolver algo
                $data = [
                    'code' => 200,
                    'status' => 'success', 'schedule' => $schedule
                ];
            } 
        }
        
        return response()->json($data, $data['code']);
    }
}
