<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>PC Build Quotation</title>
    <style>
        body {
            font-family: 'nikosh', sans-serif; /* বাংলা সাপোর্ট এর জন্য */
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container { padding: 30px; }
        .header {
            width: 100%;
            border-bottom: 2px solid #ef4a23;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header td { vertical-align: middle; }
        .logo { width: 150px; }
        .shop-info { text-align: right; }
        .shop-info h2 { color: #ef4a23; margin: 0; }
        .shop-info p { margin: 2px 0; font-size: 13px; }
        
        .title { text-align: center; text-transform: uppercase; margin: 20px 0; font-size: 20px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 12px; text-align: left; }
        td { border: 1px solid #ddd; padding: 12px; }
        
        .text-right { text-align: right; }
        .total-row { background-color: #fafafa; font-weight: bold; font-size: 16px; }
        .footer { margin-top: 50px; text-align: center; font-size: 11px; color: #666; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <table class="header">
        <tr>
            <td>
                {{-- public_path ব্যবহার করা হয়েছে ইমেজ পাথের জন্য --}}
                <img src="{{$front_ins_url.$front_logo_name}}" class="logo">
            </td>
            <td class="shop-info">
                <h2>{{ $front_ins_name }}</h2>
                <p>{{ $front_ins_add }}</p>
                <p>Phone: {{ $front_ins_phone }}</p>
                <p>Email: {{ $front_ins_email }}</p>
                <p>Date: {{ date('d M, Y') }}</p>
            </td>
        </tr>
    </table>

    <div class="title">PC Configuration Quotation</div>

    <table>
        <thead>
            <tr>
                <th>Component Category</th>
                <th>Product Description</th>
                <th class="text-right">Price</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($selectedComponents as $item)
                <tr>
                    <td style="font-weight: bold;">{{ $item['category_name'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td class="text-right">{{ number_format($item['price'], 0) }}/= </td>
                </tr>
                @php $total += $item['price']; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" class="text-right">Total Estimated Price:</td>
                <td class="text-right" style="color: #ef4a23;">{{ number_format($total, 0) }}/= </td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 40px; font-size: 12px;">
        <p><strong>Notes:</strong></p>
        <ul>
            <li>This is a system-generated quotation for information purposes.</li>
            <li>Prices may change subject to market availability.</li>
            <li>Standard warranty policy applies to each individual component.</li>
        </ul>
    </div>

    <div class="footer">
        <p>Thank you for choosing {{ $front_ins_name }}. Visit us again at {{ url('/') }}</p>
    </div>
</div>

</body>
</html>