<?php

namespace App\Http\Controllers\Sellers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Prospects;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    public function index()
    {
        $sellerId = Auth::guard('sellers')->id();
        
        // KPIs
        $assigned_count = Prospects::where('sellers_id', $sellerId)->count();
        $in_process_count = Prospects::where('sellers_id', $sellerId)->where('status', 1)->count();
        $completed_count = Prospects::where('sellers_id', $sellerId)->where('status', 2)->count();

        // Latest Notes
        // We need to join with prospects to filter by seller
        $latest_notes = \App\Models\ProspectNote::whereHas('prospect', function($q) use ($sellerId) {
                $q->where('sellers_id', $sellerId);
            })
            ->with('prospect')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Upcoming Events
        $events = \App\Models\ProspectEvent::whereHas('prospect', function($q) use ($sellerId) {
                $q->where('sellers_id', $sellerId);
            })
            ->with('prospect')
            ->where('start', '>=', now()->startOfDay()) // Events from today onwards
            ->orderBy('start', 'asc')
            ->get();

        return view('sellers.dashboard', compact(
            'assigned_count', 
            'in_process_count', 
            'completed_count', 
            'latest_notes', 
            'events'
        ));
    }
}