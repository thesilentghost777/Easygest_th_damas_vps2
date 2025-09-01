<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ErrorLogController extends Controller
{
    public function index(Request $request)
    {
        // Vérifier que l'utilisateur est développeur ou admin
        $user = auth()->user();
      
        $query = DB::table('error_logs')
            ->leftJoin('users', 'error_logs.user_id', '=', 'users.id')
            ->select(
                'error_logs.*',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderBy('error_logs.error_time', 'desc');

        // Filtres
        if ($request->filled('error_type')) {
            $query->where('error_logs.error_type', 'like', '%' . $request->error_type . '%');
        }

        if ($request->filled('date_from')) {
            $query->where('error_logs.error_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('error_logs.error_time', '<=', $request->date_to . ' 23:59:59');
        }

        if ($request->filled('status_code')) {
            $query->where('error_logs.http_status_code', $request->status_code);
        }

        $errors = $query->paginate(20);

        // Statistiques
        $stats = [
            'total_errors' => DB::table('error_logs')->count(),
            'today_errors' => DB::table('error_logs')
                ->whereDate('error_time', today())
                ->count(),
            'this_week_errors' => DB::table('error_logs')
                ->whereBetween('error_time', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'error_types' => DB::table('error_logs')
                ->select('error_type', DB::raw('count(*) as count'))
                ->groupBy('error_type')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),
            'status_codes' => DB::table('error_logs')
                ->select('http_status_code', DB::raw('count(*) as count'))
                ->whereNotNull('http_status_code')
                ->groupBy('http_status_code')
                ->orderByDesc('count')
                ->get()
        ];

        return view('errors.index', compact('errors', 'stats'));
    }

    public function show($id)
    {
        $user = auth()->user();
       
        $error = DB::table('error_logs')
            ->leftJoin('users', 'error_logs.user_id', '=', 'users.id')
            ->select(
                'error_logs.*',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->where('error_logs.id', $id)
            ->first();

        if (!$error) {
            abort(404, 'Log d\'erreur non trouvé');
        }

        // Décoder les données JSON
        if ($error->request_data) {
            $error->request_data = json_decode($error->request_data, true);
        }

        return view('errors.show', compact('error'));
    }

    public function clear(Request $request)
    {
        $user = auth()->user();
       

        $days = $request->input('days', 30);
        $cutoffDate = Carbon::now()->subDays($days);
        
        $deletedCount = DB::table('error_logs')
            ->where('error_time', '<', $cutoffDate)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "Suppression réussie : {$deletedCount} logs supprimés (plus de {$days} jours)",
            'deleted_count' => $deletedCount
        ]);
    }
}
