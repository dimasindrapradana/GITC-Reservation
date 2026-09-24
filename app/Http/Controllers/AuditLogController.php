<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')
            ->trim()
            ->toString();

        $module = $request->string('module')
            ->toString();

        $action = $request->string('action')
            ->toString();

        $userId = $request->string('user_id')
            ->toString();

        $dateFrom = $request->string('date_from')
            ->toString();

        $dateTo = $request->string('date_to')
            ->toString();

        $logs = AuditLog::query()
            ->with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('description', 'like', '%' . $search . '%')
                        ->orWhere('module', 'like', '%' . $search . '%')
                        ->orWhere('action', 'like', '%' . $search . '%')
                        ->orWhere('target_id', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query
                                ->where('name', 'like', '%' . $search . '%')
                                ->orWhere(
                                    'employee_number',
                                    'like',
                                    '%' . $search . '%'
                                );
                        });
                });
            })
            ->when($module !== '', function ($query) use ($module) {
                $query->where('module', $module);
            })
            ->when($action !== '', function ($query) use ($action) {
                $query->where('action', $action);
            })
            ->when($userId !== '', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when($dateFrom !== '', function ($query) use ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo !== '', function ($query) use ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $modules = AuditLog::query()
            ->select('module')
            ->whereNotNull('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        $actions = AuditLog::query()
            ->select('action')
            ->whereNotNull('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $users = User::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'employee_number',
            ]);

        return view('admin.audit-logs.index', [
            'logs' => $logs,
            'modules' => $modules,
            'actions' => $actions,
            'users' => $users,
            'search' => $search,
            'module' => $module,
            'action' => $action,
            'userId' => $userId,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    public function show(AuditLog $auditLog): View
    {
        $auditLog->load('user');

        return view('admin.audit-logs.show', [
            'auditLog' => $auditLog,
        ]);
    }
}