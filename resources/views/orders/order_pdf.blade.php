<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <h5 class="text-center">Laporan Order Periode ({{ $date[0] }} - {{ $date[1] }})</h5>
    <hr>
    <table width="100%" class="table table-bordered">
        <thead>
            <tr class="text-center">
                <th>InvoiceID</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse ($orders as $row)
                <tr>
                    <td><strong>{{ $row->invoice }}</strong></td>
                    <td>
                        <strong>{{ $row->customer_name }}</strong><br>
                        <label><strong>Telp:</strong> {{ $row->customer_phone }}</label><br>
                        <label><strong>Alamat:</strong> {{ $row->customer_address }} {{ $row->customer->district->name }} - {{  $row->citie->name }}, {{  $row->citie->postal_code }}</label>
                    </td>
                    <td>{{ $row->created_at->format('d M Y') }}</td>
                    <td class="text-right">Rp {{ number_format($row->subtotal) }}</td>
                </tr>

                @php $total += $row->subtotal @endphp
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: yellow; border: 1px solid black;">
                <td colspan="3" class="text-center">Total</td>
                <td class="text-right">Rp {{ number_format($total) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
