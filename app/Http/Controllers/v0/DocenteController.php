<?php

namespace App\Http\Controllers\v0;

use App\Exports\JornadasExport;
use App\Http\Controllers\Controller;
use App\Models\Catalogo;
use App\Models\DocenteActividad;
use App\Models\DocenteAsistencia;
use App\Models\JornadaActividad;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DocenteController extends Controller
{
    public function obtenerJornadaLaboralDiaria(Request $request)
    {
        $fecha = Carbon::now()->format('Y/m/d/');

        $docenteAsistencia = DocenteAsistencia::with('jornadaActividades')
            ->with('tipo')
            ->where('user_id', $request->user_id)
            ->where('fecha', $fecha)
            ->where('estado_id', '<>', 3)
            ->first();
        $jornadaActividades = [];
        if ($docenteAsistencia) {
            $jornadaActividades = $docenteAsistencia->jornadaActividades()
                ->with('tipo')
                ->where('estado_id', '<>', 3)
                ->get();
        }
        return response()->json([
            'jornada_actividades' => $jornadaActividades,
            'fecha_actual' => $fecha], 200);
    }

    public function obtenerJornadaLaboralTodos(Request $request)
    {
        $docenteAsistencia = DocenteAsistencia::
        with(['jornadaActividades' => function ($query) {
            $query->where('estado_id', '<>', 3);
        }])
            ->where('user_id', $request->user_id)
            ->where('estado_id', '<>', 3)
            ->get();

        return response()->json(['docente_asistencias' => $docenteAsistencia], 200);

    }

    public function inicarActividad(Request $request)
    {
        $fecha = Carbon::now();
        $docenteAsistencia = DocenteAsistencia::where('user_id', $request->user_id)
            ->where('fecha', $fecha->format('Y-m-d'))
            ->first();

        if (!$docenteAsistencia) {
            $docenteAsistencia = $this->crearDocenteAsistencia($fecha, $request->user_id, $request->descriocion);
        }

        $horaInicio = $request->hora_inicio_jornada['hora'] . ':' . $request->hora_inicio_jornada['minuto']
            . ':' . $request->hora_inicio_jornada['segundo'];

        $jornadaActividad = new JornadaActividad([
            'hora_inicio' => $horaInicio,
            'descripcion' => $request->descripcion,
            'observaciones' => $request->observaciones,
            'tipo_id' => $request->tipo_id,
            'estado_id' => 11
        ]);

        $tipo = Catalogo::findOrFail($request->tipo_id);
        $jornadaActividad->docenteAsistencia()->associate($docenteAsistencia);
        $jornadaActividad->tipo()->associate($tipo);
        $jornadaActividad->save();

        return response()->json(['jornada_actividad' => $jornadaActividad]);

    }

    public function actualizarActividad(Request $request)
    {
        $jornadaActividad = JornadaActividad::findOrFail($request->jornada_actividad_id);
        if ($jornadaActividad) {
            $jornadaActividad->update([
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'tipo_id' => $request->tipo_id,
                'descripcion' => $request->descripcion,
            ]);
        }
        return response()->json(['jornada_actividad' => $jornadaActividad]);
    }

    public function eliminarActividad(Request $request)
    {
        $jornadaActividad = JornadaActividad::findOrFail($request->jornada_actividad_id);
        if ($jornadaActividad) {
            $jornadaActividad->update(['estado_id' => 3]);
        }
        return response()->json(['jornada_actividad' => $jornadaActividad]);
    }

    public function finalizarActividad(Request $request)
    {
        $jornadaActividad = JornadaActividad::findOrFail($request->jornada_actividad_id);
        $horasInicio = substr($jornadaActividad->hora_inicio, 0, 2);
        $minutosInicio = substr($jornadaActividad->hora_inicio, 3, 2);
        $segundosInicio = substr($jornadaActividad->hora_inicio, 6, 2);

        $fecha = Carbon::create(1990, 12, 04,
            $request->hora_fin_jornada['hora'], $request->hora_fin_jornada['minuto'], $request->hora_fin_jornada['segundo']);
        $horaFin = $fecha->format('H:i:s');
        $tiempo = $horasInicio . ' hours ' . $minutosInicio . ' minutes ' . $segundosInicio . ' seconds';
        $duracion = $fecha->sub($tiempo)->format('H:i:s');

        if ($jornadaActividad) {
            $jornadaActividad->update([
                'hora_fin' => $horaFin,
                'duracion' => $duracion,
                'estado_id' => 12,
            ]);
        }
        return response()->json(['jornada_actividad' => $jornadaActividad]);
    }

    public function crearDocenteAsistencia($fechaActual, $userId, $descripcion)
    {
        $user = User::findOrFail($userId);
        return $user->docenteAsistencias()->create([
            'fecha' => $fechaActual,
            'estado_id' => 1,
        ]);
    }

    public function crearActividad(Request $request)
    {
        $data = $request->json()->all();
        $dataDocenteActividades = $data['docenteActividades'];

        $fecha = Carbon::now();
        $docenteAsistencia = DocenteAsistencia::where('user_id', $request->user_id)
            ->where('fecha', $fecha->format('Y-m-d'))
            ->first();

        if (!$docenteAsistencia) {
            $docenteAsistencia = $this->crearDocenteAsistencia($fecha, $request->user_id, $request->descripcion);
        }
        $docenteAsistencia->docenteActividades()->delete();
        foreach ($dataDocenteActividades as $actividad) {
            $docenteActividad = DocenteActividad::where('tipo_id', $actividad['tipo_id'])->first();
            if (!$docenteActividad) {
                $docenteActividad = new DocenteActividad([
                    'porcentaje_avance' => $actividad['porcentaje_avance'],
                    'tipo_id' => $actividad['tipo_id'],
                    'estado_id' => 1
                ]);
            }
            $tipo = Catalogo::findOrFail($actividad['tipo_id']);
            $docenteActividad->docenteAsistencia()->associate($docenteAsistencia);
            $docenteActividad->tipo()->associate($tipo);
            $docenteActividad->save();
        }

        $docenteActividades = $docenteAsistencia->docenteActividades()->with('tipo')->get();
        return response()->json(['docente_actividades' => $docenteActividades]);

    }

    public function obtenerActividades(Request $request)
    {
        $fecha = Carbon::now()->format('Y/m/d/');

        $docenteAsistencia = DocenteAsistencia::with('docenteActividades')
            ->with('tipo')
            ->where('user_id', $request->user_id)
            ->where('fecha', $fecha)
            ->where('estado_id', '<>', 3)
            ->first();
        $docenteActividades = [];
        if ($docenteAsistencia) {
            $docenteActividades = $docenteAsistencia->docenteActividades()
                ->with('tipo')
                ->where('estado_id', '<>', 3)
                ->get();
        }
        return response()->json(['docente_actividades' => $docenteActividades], 200);
    }

// Exportaciones
    public function exportarJornadasDocente(Request $request)
    {
        return Excel::download(new JornadasExport, 'actividades.xlsx');
    }

}
