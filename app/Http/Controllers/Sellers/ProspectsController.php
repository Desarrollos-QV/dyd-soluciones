<?php

namespace App\Http\Controllers\Sellers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prospects;
use App\Traits\NotifiesUsers;
use Illuminate\Support\Facades\Auth;

class ProspectsController extends Controller
{
    use NotifiesUsers;
    public function index()
    {
        $sellerId = Auth::guard('sellers')->id();
        $prospects = Prospects::where('sellers_id', $sellerId)->orderBy('id', 'desc')->get();

        return view('sellers.prospects.index', compact('prospects'));
    }

    public function create()
    {
        $prospect = new Prospects();
        return view('sellers.prospects.create', compact('prospect'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_company' => 'required',
            'name_prospect' => 'required',
            'company' => 'required',
            'potencial' => 'required',
            'location' => 'required',
            'contacts' => 'required',
            'observations' => 'nullable',
        ]);

        $data['sellers_id'] = Auth::guard('sellers')->id();
        $data['status'] = 0; // Default status (Sin atender)

        Prospects::create($data);

        return response()->json([
            'message' => 'Prospecto creado correctamente.',
            'redirect' => route('sellers.prospects.index')
        ]);
    }

    public function edit($id)
    {
        $sellerId = Auth::guard('sellers')->id();
        $prospect = Prospects::where('id', $id)->where('sellers_id', $sellerId)->firstOrFail();

        return view('sellers.prospects.edit', compact('prospect'));
    }

    public function update(Request $request, $id)
    {
        $sellerId = Auth::guard('sellers')->id();
        $prospect = Prospects::where('id', $id)->where('sellers_id', $sellerId)->firstOrFail();

        $data = $request->validate([
            'name_company' => 'required',
            'name_prospect' => 'required',
            'company' => 'required',
            'potencial' => 'required',
            'location' => 'required',
            'contacts' => 'required',
            'observations' => 'nullable',
        ]);

        $prospect->update($data);

        return response()->json([
            'message' => 'Prospecto actualizado correctamente.',
            'redirect' => route('sellers.prospects.index')
        ]);
    }
}
