<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #0B192C;
        }

        .header {
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .brand {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 3px;
            color: #0B192C;
        }

        .subtitle {
            color: #C87D55;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .badge-paid {
            background: #d1fae5;
            color: #065f46;
            padding: 4px 10px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #F4EBE1;
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #D4AF37;
            font-size: 11px;
        }

        td {
            padding: 10px 8px;
            border-bottom: 1px solid #eee;
        }

        .totals {
            margin-top: 20px;
            width: 45%;
            margin-left: auto;
            background: #FAF6F2;
            padding: 12px;
            border-radius: 8px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .ttc {
            font-size: 15px;
            font-weight: bold;
            color: #C87D55;
            border-top: 1px solid #D4AF37;
            padding-top: 8px;
        }
    </style>
</head>

<body>
    <div class="header">
        <table style="margin-top:0;">
            <tr>
                <td style="border:none; padding:0;">
                    <div class="brand">GROUPE</div>
                    <div class="subtitle">Hôtels & Resorts — {{ $invoice->booking->hotel->name }}</div>
                    <p style="font-size:10px; color:#666;">{{ $invoice->booking->hotel->address }}</p>
                </td>
                <td style="border:none; padding:0; text-align:right;">
                    <span class="badge-paid">{{ strtoupper($invoice->payment_status) }}</span>
                    <h3 style="margin:8px 0 2px;">FACTURE N° {{ $invoice->invoice_number }}</h3>
                    <small style="color:#666;">Émise le {{ $invoice->issued_at->format('d/m/Y H:i') }}</small>
                </td>
            </tr>
        </table>
    </div>

    <table style="margin-top:0;">
        <tr>
            <td style="border:none; width:50%; vertical-align:top;">
                <strong>FACTURÉ À :</strong><br>
                {{ $invoice->booking->client_name }}<br>
                Email : {{ $invoice->booking->client_email }}<br>
                Tél : {{ $invoice->booking->client_phone }}
            </td>
            <td style="border:none; width:50%; text-align:right; vertical-align:top;">
                <strong>DÉTAILS DU SÉJOUR :</strong><br>
                Réf. Dossier : <strong>{{ $invoice->booking->reference }}</strong><br>
                Du {{ $invoice->booking->check_in->format('d/m/Y') }} au
                {{ $invoice->booking->check_out->format('d/m/Y') }} ({{ $invoice->booking->nights }} nuits)<br>
                {{ $invoice->booking->guests_summary }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Prestation Hôtelière</th>
                <th style="text-align:center;">Quantité</th>
                <th style="text-align:right;">Total HT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $invoice->booking->room->name }}</strong><br>
                    <small style="color:#666;">Option : {{ $invoice->booking->extra_option_name }}</small>
                </td>
                <td style="text-align:center;">{{ $invoice->booking->nights }} nuit(s)</td>
                <td style="text-align:right;">{{ number_format($invoice->amount_ht, 0, ',', ' ') }} XOF</td>
            </tr>
        </tbody>
    </table>

    <div class="totals">
        <p style="margin:4px 0;">Total Hors Taxes (HT) : <strong>{{ number_format($invoice->amount_ht, 0, ',', ' ') }}
                XOF</strong></p>
        <p style="margin:4px 0;">TVA Hôtelière ({{ $invoice->tva_rate }}%) :
            <strong>{{ number_format($invoice->tva_amount, 0, ',', ' ') }} XOF</strong></p>
        <p class="ttc" style="margin:8px 0 0;">NET À PAYER (TTC) :
            {{ number_format($invoice->amount_ttc, 0, ',', ' ') }} XOF</p>
    </div>

    <p style="text-align:center; font-size:10px; color:#888; margin-top:40px;">
        Merci d'avoir choisi Groupe Hôtels & Resorts. Document généré électroniquement par Hotel ERP.
    </p>
</body>

</html>
