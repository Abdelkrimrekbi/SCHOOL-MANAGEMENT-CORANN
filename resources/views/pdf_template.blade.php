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
            @if(count($payments) > 1)
            <p>تاريخ الدفع: {{ $payments[0]->date_payment }}</p>
            <p>رقم الإيصال :  CSP{{ $payments[0]->id }}</p>
            <p>{{ $configirations->first()->name }}</p>
          </td>
          @if(isset($image) && $image != null)
          <td class="logo"><img src="{{ $image }}" alt="Image" width="70"></td> 
          @else
          <td></td>
          @endif
          </tr>
      </table>
      <table class="client-info">
      <tr>
    <td>اسم الطالب:</td>
    <td>{{ $student->name }}</td>
     </tr>
    <tr>
    <td>مستوى الدراسة:</td>
    <td>{{ $student->niveau_etude }}</td>
    </tr>
    <tr>
    <td>السنة الدراسية:</td>
    <td>{{ $configirations->first()->promotion }}</td>
     </tr>
     <tr>
    <td>التخصص:</td>
    <td>{{ $levels->level }}</td>
    </tr>
        
       
        <tr>
    <td style="border-bottom: 1px solid #eee;" colspan="2"> الشهور :</td>
</tr>
@foreach($payments as $payment)
<tr>
    <td></td>
    <td>{{ $payment->month }} </td>
</tr>
   @endforeach 
       
   @php
$totalAmount = 0;
@endphp 
       <tr>
    <td style="border-bottom: 1px solid #eee;" colspan="2">المبلغ المدفوع :</td>
</tr>
@foreach($payments as $payment)
<tr>
    <td></td>
    <td>{{ $payment->amount }} DH</td>
    @php
    $totalAmount += $payment->amount;
    @endphp
</tr>
   @endforeach 
   <tr style="border-bottom: 1px solid #eee;"><td style="border-bottom: 1px solid #eee;"></td></tr>
   <tr>
    <td style="border-bottom: 1px solid #eee;">مجموع :</td>
    <td style="border-bottom: 1px solid #eee;">{{ $totalAmount }} DH</td>
</tr>
       
            @else
            <p>التاريخ : {{ $payments[0]->date_payment }}</p>
            <p>رقم الإيصال : CSP{{ $payments[0]->id }}</p>
            <p>{{ $configirations->first()->name }}</p>
          </td>
          @if(isset($image) && $image != null)
          <td class="logo"><img src="{{ $image }}" alt="Image" width="70"></td> 
          @else
          <td></td>
          @endif
        </tr>
      </table>
      <table class="client-info">
      <tr>
    <td>اسم الطالب:</td>
    <td>{{ $student->name }}</td>
    </tr>
    <tr>
    <td>مستوى الدراسة:</td>
    <td>{{ $student->niveau_etude }}</td>
       </tr>
    <tr>
    <td>السنة الدراسية:</td>
    <td>{{ $configirations->first()->promotion }}</td>
    </tr>
    <tr>
    <td>المبلغ المطلوب دفعه:</td>
    <td>{{ $payments[0]->amount }} درهم</td>
     </tr>
      <tr>
    <td>الشهر:</td>
    <td>{{ $payments[0]->month }}</td>
    </tr>
     <tr>
    <td>التخصص:</td>
    <td>{{ $levels->level }}</td>
    </tr>

        @endif
      </table>
    </div>
  </div>
</body>
</html>
