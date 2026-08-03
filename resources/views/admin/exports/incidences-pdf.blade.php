<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Incidencias</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #ea580c; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #1c1917; font-size: 18px; }
        .header p { margin: 2px 0 0 0; color: #78716c; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e7e5e4; padding: 7px 10px; text-align: left; }
        th { background-color: #f5f5f4; color: #44403c; font-weight: bold; }
        .badge { padding: 3px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .badge-alta { background-color: #ffe4e6; color: #e11d48; }
        .badge-media { background-color: #fef3c7; color: #d97706; }
        .badge-baja { background-color: #f5f5f4; color: #57534e; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Incidencias de Producción</h1>
        <p>Generado el {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Orden</th>
                <th>Título</th>
                <th>Reportado Por</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incidences as $inc)
            <tr>
                <td>#OP-{{ $inc->production_order_id ?? $inc->order_id ?? 'N/A' }}</td>
                <td>{{ $inc->title }}</td>
                <td>{{ $inc->operario->name ?? 'N/A' }}</td>
                <td>
                    <span class="badge badge-{{ $inc->importance }}">{{ $inc->importance }}</span>
                </td>
                <td>{{ strtoupper($inc->status) }}</td>
                <td>{{ $inc->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>