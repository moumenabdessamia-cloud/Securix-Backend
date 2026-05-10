<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function featured()
    {
        $products = Product::where('is_featured', true)->get();
        return response()->json($products);
    }

    public function onSale()
    {
        $products = Product::where('is_on_sale', true)->get();
        return response()->json($products);
    }

    public function byCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->get();
        return response()->json($products);
    }

    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function stats()
{
    $products = Product::all();
    $totalProduits = $products->count();

    $valeurStock = $products->sum(function($p) {
        return $p->product_price * $p->stock_qty;
    });

    $stockTotal = $products->sum('stock_qty');
    $alertes    = $products->filter(fn($p) => $p->stock_qty <= $p->stock_min)->count();
    $ruptures   = $products->filter(fn($p) => $p->stock_qty == 0)->count();
    $enOffre    = $products->filter(fn($p) => $p->is_on_sale)->count();

    $totalVendu   = OrderItem::sum('quantity');
    $stockMoyen   = $stockTotal > 0 ? $stockTotal : 1;
    $tauxRotation = round($totalVendu / $stockMoyen, 2);

    $ventesMoyennesJour = $totalVendu > 0 ? $totalVendu / 30 : 1;
    $couvertureStock    = round($stockTotal / $ventesMoyennesJour, 0);
    $dureeMoyenne       = $tauxRotation > 0 ? round(360 / $tauxRotation, 0) : 0;

    $totalCommande = OrderItem::sum('quantity');
    $totalLivre    = Order::where('status', 'delivered')
        ->with('items')->get()
        ->sum(fn($o) => $o->items->sum('quantity'));
    $tauxService = $totalCommande > 0
        ? round(($totalLivre / $totalCommande) * 100, 1) : 0;

    $totalCommandes   = Order::count();
    $commandesAttente = Order::where('status', 'pending')->count();
    $commandesLivrees = Order::where('status', 'delivered')->count();

    // ✅ VENTES RÉELLES PAR MOIS depuis la base de données
    $ventesParMois = [];
    $moisNoms = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];

    for ($i = 1; $i <= 12; $i++) {
        $ventes = OrderItem::whereHas('order', function($q) use ($i) {
            $q->whereMonth('created_at', $i)
              ->whereYear('created_at', date('Y'));
        })->sum('quantity');

        $ventesParMois[] = [
            'mois'   => $moisNoms[$i - 1],
            'ventes' => (int) $ventes,
        ];
    }

    // ✅ CHIFFRE D'AFFAIRES PAR MOIS
    $caParMois = [];
    for ($i = 1; $i <= 12; $i++) {
        $ca = Order::whereMonth('created_at', $i)
            ->whereYear('created_at', date('Y'))
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $caParMois[] = [
            'mois' => $moisNoms[$i - 1],
            'ca'   => (float) $ca,
        ];
    }

    return response()->json([
        'total_produits'    => $totalProduits,
        'valeur_stock'      => round($valeurStock, 2),
        'stock_total'       => $stockTotal,
        'alertes_stock'     => $alertes,
        'taux_rotation'     => $tauxRotation,
        'couverture_stock'  => $couvertureStock,
        'duree_moyenne'     => $dureeMoyenne,
        'taux_service'      => $tauxService,
        'ruptures'          => $ruptures,
        'en_offre'          => $enOffre,
        'total_commandes'   => $totalCommandes,
        'commandes_attente' => $commandesAttente,
        'commandes_livrees' => $commandesLivrees,
        'ventes_par_mois'   => $ventesParMois,
        'ca_par_mois'       => $caParMois,
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'product_title' => 'required|string|max:255',
            'product_price' => 'required|numeric',
            'stock_qty'     => 'required|integer',
            'stock_min'     => 'nullable|integer',
            'category_id'   => 'nullable|integer',
            'brand_id'      => 'nullable|integer',
            'product_image' => 'nullable|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
        }

        $product = Product::create([
            'product_title' => $request->product_title,
            'product_price' => $request->product_price,
            'stock_qty'     => $request->stock_qty,
            'stock_min'     => $request->stock_min,
            'category_id'   => $request->category_id,
            'brand_id'      => $request->brand_id,
            'product_image' => $imagePath,
            'is_featured'   => $request->is_featured ?? false,
            'is_on_sale'    => $request->is_on_sale ?? false,
            'sale_price'    => $request->sale_price ?? null,
        ]);

        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
            $product->product_image = $imagePath;
        }

        $product->product_title = $request->product_title ?? $product->product_title;
        $product->product_price = $request->product_price ?? $product->product_price;
        $product->stock_qty     = $request->stock_qty     ?? $product->stock_qty;
        $product->stock_min     = $request->stock_min     ?? $product->stock_min;
        $product->category_id   = $request->category_id   ?? $product->category_id;
        $product->brand_id      = $request->brand_id      ?? $product->brand_id;
        $product->is_featured   = $request->has('is_featured') ? $request->is_featured : $product->is_featured;
        $product->is_on_sale    = $request->has('is_on_sale')  ? $request->is_on_sale  : $product->is_on_sale;
        $product->sale_price    = $request->sale_price ?? $product->sale_price;

        $product->save();
        return response()->json($product);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['message' => 'Produit supprimé avec succès']);
    }
}