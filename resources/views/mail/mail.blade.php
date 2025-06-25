<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Invoice</title>
</head>


<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div class="invoice"
        style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); max-width: 600px; margin: auto;">
        <header style="text-align: center; margin-bottom: 20px;">
            <h1 style="margin: 0; color: #333; font-size: 24px !important;">INVOICE WAJUBODOTA
                <span>{{ $ket_edit }}</span>
            </h1>

        </header>



        <table>
            <tr>
                <td>Penyewa</td>
                <td>: {{ $customer_name }}</td>
            </tr>
            <tr>
                <td>No. Invoice</td>
                <td>: {{ $id }}</td>
            </tr>
            <tr>
                <td>Petugas</td>
                <td>: {{ $user_name }}</td>
            </tr>
            <tr>
                <td>Tanggal Pesan</td>
                <td>: {{ $order_date }}</td>
            </tr>
            <tr>
                <td>Tanggal Ambil</td>
                <td>: {{ $tanggal_pengambilan }}</td>
            </tr>
            <tr>
                <td>Tanggal Mulai</td>
                <td>: {{ $start_date }}</td>
            </tr>
            <tr>
                <td>Tanggal Jatuh Tempo</td>
                <td>: {{ $end_date }}</td>
            </tr>

            <tr>
                <td>Status Pembayaran</td>
                <td>: {{ $full_payment_status }}</td>
            </tr>
        </table>
        <hr>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;">
                            Deskripsi</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;">
                            Ukuran</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;">
                            Umur</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;">
                            Kategori</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;">
                            Jumlah</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;">
                            Harga</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($order_detail as $detail)
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $detail->name }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $detail->ukuran }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $detail->untuk_umur }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $detail->kategori }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $detail->quantity }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">Rp {{ number_format($detail->price) }}
                            </td>

                            <td style="border: 1px solid #ddd; padding: 8px;">Rp
                                {{ number_format($detail->total_price) }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="5"
                            style="border: 1px solid #ddd; padding: 8px; font-weight: bold; text-align: right;">Total
                        </td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($total_price) }}</td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Down payment</th>
                        <th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Sisa Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left; padding: 8px; border: 1px solid #ddd;">
                            Rp. {{ number_format($down_payment) }}</td>
                        <td style="text-align: left; padding: 8px; border: 1px solid #ddd;">
                            Rp. {{ number_format($belum_dibayar) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <footer style="text-align: center; margin-top: 20px; font-size: 14px; color: #777;">
            <p>Terima kasih sudah pesan di WAJUBODOTA</p>
        </footer>
    </div>
</body>

</html>
