<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inventaire EPI - Gestion de Stock</title>
    <style>
        /* CSS pour le design */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #3498db; color: white; }
        .low-stock { color: #e74c3c; font-weight: bold; background-color: #ffd7d7; }
        .in-stock { color: #27ae60; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 Inventaire des Équipements (EPI)</h1>
        
        <table>
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th>Prix Unitaire</th>
                    <th>Stock Actuel</th>
                    <th>Seuil Alerte</th>
                    <th>État</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->product_title }}</td>
                    <td>{{ $product->product_price }} DH</td>
                    <td>{{ $product->stock_qty }}</td>
                    <td>{{ $product->stock_min }}</td>
                    <td class="{{ $product->stock_qty <= $product->stock_min ? 'low-stock' : 'in-stock' }}">
                        {{ $product->stock_qty <= $product->stock_min ? '⚠️ Réapprovisionner' : '✅ OK' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>