<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesReportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesReportLogController extends Controller
{
    public function index(Request $request)
    {
        // Check authentication
        if (!Auth::check()) {
            abort(401, 'Unauthenticated.');
        }

        // Only allow super_admin access (not cashiers)
        if (Auth::user()->isCashier()) {
            abort(403, 'Unauthorized access.');
        }

        $query = SalesReportLog::orderBy('report_date', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter by report type
        if ($request->filled('report_type')) {
            $query->where('report_type', $request->report_type);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('report_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('report_date', '<=', $request->end_date);
        }

        $reports = $query->paginate(20);

        return view('admin.sales-reports.index', compact('reports'));
    }
}
