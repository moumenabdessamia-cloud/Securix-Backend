<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Mail\StockAlertMail;
use Illuminate\Support\Facades\Mail;

class CheckStockAlert extends Command
{
    protected $signature = 'stock:check';
    protected $description = 'Verifie le stock et envoie une alerte email si necessaire';

    public function handle()
    {
        $produits = Product::where('stock_qty', '<=', \DB::raw('stock_min'))->get();

        if ($produits->count() > 0) {
            Mail::to(env('ADMIN_EMAIL'))->send(new StockAlertMail($produits));
            $this->info('Email d\'alerte envoye pour ' . $produits->count() . ' produit(s).');
        } else {
            $this->info('Tous les stocks sont OK.');
        }
    }
}