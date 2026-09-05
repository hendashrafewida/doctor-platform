<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view with organization statistics.
     */
    public function index(): View
    {
        $totalOrganizations = Organization::count();
        $activeOrganizations = Organization::where('status', 'active')->count();
        $suspendedOrganizations = Organization::where('status', 'suspended')->count();
        
        // Get 10 most recently created organizations
        $recentOrganizations = Organization::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalOrganizations',
            'activeOrganizations',
            'suspendedOrganizations',
            'recentOrganizations'
        ));
    }
}
