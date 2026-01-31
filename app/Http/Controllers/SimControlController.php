<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SimControl;
use App\Imports\SimControlImport;
use Maatwebsite\Excel\Facades\Excel;

class SimControlController extends Controller
{
    protected $folder = 'admin.simcontrol';

    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $simcontrols = SimControl::all();
        return view($this->folder . '.index', compact('simcontrols'));
    }

    /**
     * Summary of create
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */ 
    public function create()
    {
        $simcontrol = new SimControl;
        return view($this->folder . '.create', compact('simcontrol'));
    }

    /**
     * Summary of store
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|string|max:255',
                'compañia' => 'required|string|max:255',
                'numero_sim' => 'required|string|min:10|max:100|unique:simcontrol,numero_sim',
                'numero_publico' => 'required|string|max:100',
            ]);

            $data = $request->all();

            
            SimControl::create($data);

            return response()->json([
                'ok' => true,
                'message' => 'SIM Control created successfully.',
                'redirect' => route('simcontrol.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Failed to create SIM Control. '. $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Summary of edit
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $simcontrol = SimControl::findOrFail($id);
        return view($this->folder . '.edit', compact('simcontrol'));
    }

    /**
     * Summary of update
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'compañia' => 'required|string|max:255',
            'numero_sim' => 'required|string|min:10|max:100',
            'numero_publico' => 'required|string|max:100',
        ]);

        $data = $request->all();

        try {
            $simcontrol = SimControl::findOrFail($id);
            $simcontrol->update($data);

            return response()->json([
                'ok' => true,
                'message' => 'SIM Control updated successfully.',
                'redirect' => route('simcontrol.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => true,
                'message' => 'Failed to update SIM Control.',
            ], 500);
        }
    }

    /**
     * Summary of destroy
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $simcontrol = SimControl::findOrFail($id);
            $simcontrol->delete();

            return redirect()->route('simcontrol.index')->with('success', 'SIM eliminado exitosamente');
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Failed to delete SIM Control.',
            ], 500);
        }
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids', []);

        try {
            SimControl::whereIn('id', $ids)->delete();

            return response()->json([
                'ok' => true,
                'message' => 'Selected SIMs deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Failed to delete selected SIMs.',
            ], 500);
        }
    }
    

    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new SimControlImport, $request->file('file'));
            
            return back()->with('success', 'SimCards importadas exitosamente.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $messages = [];
             foreach ($failures as $failure) {
                 $messages[] = 'Fila ' . $failure->row() . ': ' . implode(', ', $failure->errors());
             }
             return back()->with('error', 'Error de validación: ' . implode('<br>', $messages));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }
}
