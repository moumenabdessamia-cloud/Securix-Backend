<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StockAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $produits;

    public function __construct($produits)
    {
        $this->produits = $produits;
    }

    public function build()
    {
        return $this->subject('⚠️ SECURIX — Alerte Stock Critique')
                    ->html($this->buildHtml());
    }

    private function buildHtml()
    {
        $rows = '';
        foreach ($this->produits as $p) {
            $statut = $p->stock_qty == 0 ? 'RUPTURE' : 'Stock critique';
            $color = $p->stock_qty == 0 ? '#e74c3c' : '#e67e22';
            $rows .= "
                <tr>
                    <td style='padding:10px;border:1px solid #ddd;'>{$p->product_title}</td>
                    <td style='padding:10px;border:1px solid #ddd;color:{$color};font-weight:bold;'>{$p->stock_qty}</td>
                    <td style='padding:10px;border:1px solid #ddd;'>{$p->stock_min}</td>
                    <td style='padding:10px;border:1px solid #ddd;color:{$color};font-weight:bold;'>{$statut}</td>
                </tr>
            ";
        }

        return "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;'>
            <div style='background:#1a3a6b;padding:24px;text-align:center;'>
                <h1 style='color:white;margin:0;font-size:24px;'>SECURIX</h1>
                <p style='color:#c9a84c;margin:4px 0 0;font-size:12px;letter-spacing:2px;'>TECH-INDUSTRIAL SOLUTIONS</p>
            </div>
            <div style='padding:24px;background:#fff;'>
                <h2 style='color:#e74c3c;'>⚠️ Alerte Stock Critique</h2>
                <p style='color:#555;'>Les produits suivants necessitent un reapprovisionnement urgent :</p>
                <table style='width:100%;border-collapse:collapse;margin-top:16px;'>
                    <thead>
                        <tr style='background:#1a3a6b;color:white;'>
                            <th style='padding:10px;text-align:left;'>Produit</th>
                            <th style='padding:10px;text-align:left;'>Stock actuel</th>
                            <th style='padding:10px;text-align:left;'>Seuil alerte</th>
                            <th style='padding:10px;text-align:left;'>Statut</th>
                        </tr>
                    </thead>
                    <tbody>{$rows}</tbody>
                </table>
                <p style='margin-top:24px;color:#555;'>Connectez-vous au dashboard pour gerer le stock.</p>
                <a href='http://localhost:5173/admin' style='display:inline-block;padding:12px 24px;background:#c9a84c;color:white;text-decoration:none;border-radius:4px;margin-top:8px;'>
                    Voir le Dashboard
                </a>
            </div>
            <div style='background:#f5f5f5;padding:16px;text-align:center;font-size:12px;color:#888;'>
                © 2026 SECURIX — Notification automatique de gestion de stock
            </div>
        </div>
        ";
    }
}