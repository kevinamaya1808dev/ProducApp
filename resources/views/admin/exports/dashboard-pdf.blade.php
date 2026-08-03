<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte General del Dashboard</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #1c1917; font-size: 18px; }
        .card-grid { width: 100%; margin-bottom: 20px; }
        .card { background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; border-radius: 6px; text-align: center; }
        .card-title { font-size: 10px; color: #64748b; font-weight: bold; }
        .card-value { font-size: 18px; font-weight: bold; color: #0f172a; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e2e8f0; padding: 7px 10px; text-align: left; }
        th { background-color: #f1f5f9; color: #334155; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte Ejecutivo del Dashboard</h1>
        <p>Generado el {{ date('d/m/Y H:i') }}</p>
    </div>

    <h3>Resumen General</h3>
    <table class="card-grid">
        <tr>
            <td class="card"><div class="card-title">ÓRDENES</div><div class="card-value">{{ $stats['total_orders'] }}</div></td>
            <td class="card"><div class="card-title">OPERARIOS</div><div class="card-value">{{ $stats['total_operarios'] }}</div></td>
            <td class="card"><div class="card-title">INCIDENCIAS</div><div class="card-value">{{ $stats['total_incidencias'] }}</div></td>
        </tr>
    </table>

    <h3>Últimas Incidencias Registradas</h3>
    <table>
        <thead>
            <tr>
                <th>Orden</th>
                <th>Título</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($latestIncidences as $inc)
            <tr>
                <td>#OP-{{ $inc->production_order_id ?? $inc->order_id ?? 'N/A' }}</td>
                <td>{{ $inc->title }}</td>
                <td>{{ strtoupper($inc->status) }}</td>
                <td>{{ $inc->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>