<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Professional Invoice</title>
  <style>
    @page {
      size: 80mm 297mm; /* Set the size to thermal paper dimensions */
      margin: 0; /* Reset margin for thermal paper */
    }
   
    body {
      font-family:  Arial, sans-serif;
      margin: 0; /* Reset margin for thermal paper */
      padding: 0;
      background-color: #fff;
      direction: rtl; 
    }
    .invoice-container {
  width: 80mm; 
  height: auto; 
  background-color: #fff;
  padding: 5px; 
  box-sizing: border-box; 
}
    .header h1 {
      margin: 0;
      font-size: 20px; 
    }
    .content {
      padding: 10px; /* Adjust padding for thermal paper */
    }
    .content table {
      width: 100%;
      border-collapse: collapse;
    }
    .content td {
      padding: 5px; /* Adjust padding for thermal paper */
      vertical-align: top;
    }
    .content .logo {
      font-size: 20px; /* Adjust font size for thermal paper */
      font-weight: bold;
      color: #007bff;
    }
    .content .invoice-info {
      text-align: centre;
      margin-left: 6px; /* Adjust margin for thermal paper */
      font-size: 10px;
    }
    .content .invoice-info p {
      margin-bottom:4px;
    }
    .content .invoice-info h2 {
      margin: 0;
      font-size: 18px; /* Adjust font size for thermal paper */
      white-space: nowrap;
    }
    .content .client-info,
    .content .seller-info {
      margin-top: 15px; 
      padding-left: 10px;
      padding-right: 20px;
      padding-top:10px;
      padding-bottom:20px;
    }
    .content .client-info td,
    .content .seller-info td {
      padding-bottom: 8px;
      /* border-bottom: 1px solid #eee; */
      font-size: 10px;
   
    }
    .footer {
      text-align: center;
      padding: 10px; 
      background-color: #e9ecef;
      color: #333;
      font-size: 14px; 
    }
  </style>
</head>
<body>
  <div class="invoice-container">
    <div class="content">
      <table>
        <tr>
          <!-- <td class="logo"><img src={{ $image }} alt="Image"></td> -->
         
          <td class="invoice-info">
            <h2 style="margin-bottom:5px;">فاتورة الدفع</h2><br>
        
            <p style="font-size: 8px; margin: 2px 0;">تاريخ الدفع: {{ $Revenu->income_date }}</p>
<p style="font-size: 8px; margin: 2px 0;">رقم الإيصال :  CIM{{ $Revenu->id }}</p>
<p style="font-size: 8px; margin: 2px 0;">{{ $configirations->first()->name }}</p>
          </td>
          @if(isset($image) && $image != null)
          <td class="logo"><img src="{{ $image }}" alt="Image" width="70"></td> 
          @else
          <td></td>
          @endif
          </tr>
      </table>
    
      <table class="client-info" style="width: 100%; border-collapse: collapse;">
    <tr>
        <td style="border-bottom: 1px solid #ccc;"> المصدر :</td>
        <td style="border-bottom: 1px solid #ccc;">{{ $Revenu->source }}</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #ccc;">المبلغ المدفوع :</td>
        <td style="border-bottom: 1px solid #ccc;">{{ $Revenu->amount }}</td>
    </tr>
</table>
    
   
    </div>
  </div>
</body>
</html>
