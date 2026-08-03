<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Incidence;
use App\Models\ProductionOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    // ==========================================
    // 1. EXPORTACIONES DE INCIDENCIAS
    // ==========================================

    public function incidencesExcel(Request $request): StreamedResponse
    {
        $incidences = $this->getFilteredIncidences($request);
        $fileName = 'incidencias_' . date('Y-m-d_H-i') . '.csv';

        return response()->streamDownload(function () use ($incidences) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // BOM para caracteres especiales en Excel

            fputcsv($handle, ['ID', 'Orden Prod.', 'Título', 'Reportado Por', 'Prioridad', 'Estado', 'Fecha Registro']);

            foreach ($incidences as $inc) {
                fputcsv($handle, [
                    $inc->id,
                    '#OP-' . ($inc->production_order_id ?? $inc->order_id ?? 'N/A'),
                    $inc->title,
                    $inc->operario->name ?? 'Usuario borrado',
                    strtoupper($inc->importance),
                    strtoupper($inc->status),
                    $inc->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function incidencesPdf(Request $request)
    {
        $incidences = $this->getFilteredIncidences($request);
        
        $pdf = Pdf::loadView('admin.exports.incidences-pdf', compact('incidences'));
        return $pdf->download('reporte_incidencias_' . date('Y-m-d') . '.pdf');
    }

    // Método auxiliar para mantener filtros activos en la exportación
    private function getFilteredIncidences(Request $request)
    {
        $query = Incidence::with(['order', 'operario'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('importance')) {
            $query->where('importance', $request->importance);
        }

        return $query->get();
    }

    // ==========================================
    // 2. EXPORTACIONES DEL DASHBOARD GENERAL
    // ==========================================

    public function dashboardExcel(): StreamedResponse
    {
        $fileName = 'resumen_dashboard_' . date('Y-m-d_H-i') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            // Consulta corregida usando la relación 'roles' definida en el modelo User
            $totalOperarios = User::whereHas('roles', function ($query) {
                $query->where('slug', 'operario')->orWhere('name', 'operario');
            })->count();

            fputcsv($handle, ['Métrica / Concepto', 'Valor Actual']);
            fputcsv($handle, ['Total Órdenes de Producción', ProductionOrder::count()]);
            fputcsv($handle, ['Total Operarios', $totalOperarios]);
            fputcsv($handle, ['Total Incidencias', Incidence::count()]);
            fputcsv($handle, ['Incidencias Pendientes', Incidence::where('status', 'pendiente')->count()]);
            fputcsv($handle, ['Incidencias En Proceso', Incidence::where('status', 'en_proceso')->count()]);
            fputcsv($handle, ['Incidencias Resueltas', Incidence::where('status', 'resuelta')->count()]);

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function dashboardPdf()
    {
        // Consulta corregida usando la relación 'roles'
        $totalOperarios = User::whereHas('roles', function ($query) {
            $query->where('slug', 'operario')->orWhere('name', 'operario');
        })->count();

        $stats = [
            'total_orders'      => ProductionOrder::count(),
            'total_operarios'   => $totalOperarios,
            'total_incidencias' => Incidence::count(),
            'pending_incidences' => Incidence::where('status', 'pendiente')->count(),
            'process_incidences' => Incidence::where('status', 'en_proceso')->count(),
            'resolved_incidences'=> Incidence::where('status', 'resuelta')->count(),
        ];

        $latestIncidences = Incidence::with(['order', 'operario'])->latest()->take(5)->get();

        $pdf = Pdf::loadView('admin.exports.dashboard-pdf', compact('stats', 'latestIncidences'));
        return $pdf->download('reporte_general_dashboard_' . date('Y-m-d') . '.pdf');
    }
}