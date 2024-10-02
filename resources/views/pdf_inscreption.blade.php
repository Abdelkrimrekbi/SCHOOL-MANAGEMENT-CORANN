<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Layout</title>
    <style>
        body {
            padding: 10px;
            font-size: 12px;
            direction: rtl;
            text-align: right;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header div {
            flex: 1;
            text-align: center;
            width: 150px;
        }

        header div:first-child {
            text-align: right;
        }

        header div:last-child {
            text-align: left;
        }

        /* Add border to the middle div */
        header div:nth-child(2) {
            border: 2px solid black;
            padding: 10px;
            /* Optional: Add padding to make the border visible */
        }

        /* Style for the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        /* Set width for the first cell in the first row */
        tr.sup td:first-child {
            width: 25%;
            /* 25% of the table width */
        }

        /* Set width for the second cell in the first row */
        tr.sup td:nth-child(2) {
            width: 25%;
            /* 25% of the table width */
        }

        .reglement_class {
            /* margin-top: 20px; */
            padding-right: 20px;
            border: 1px solid black;
            padding-bottom: 70px;
        }

        /* .signatureDirecture {
            height: 150px;
            margin-top: 20px;
            display: flex;
          
            border: 1px solid black;
        } */

        /* .signatureDirecture>div {
            flex: 1;
            padding-left: 20px;
        } */

        .reglement_title {
            text-align: center;
            text-decoration: underline;
        }

        .signature {
            margin-right: 70%;

            text-decoration: underline;
        }

        .signatureD {
            margin-right: 30%;
            text-decoration: underline;

        }

        p {
            font-size: 12px;
        }

        .footer {
            margin-top: 20px;
            height: 200px;
            border: 1px solid black;
            padding-right: 20px;

        }



        .signatureD {
            text-align: left;
            margin-right: 60%;
        }

        .logo {
            border: 1px solid white;

        }

        .logo2 {
            border-bottom: 1px solid white;
            border-left: 1px solid white;
            border-top: 1px solid white;

        }

        .midlle {
            text-align: center;
            padding-bottom: 40px;
            
        }
    </style>
</head>

<body>
    <header>
        <table>
            <tr>

                @if(isset($image) && $image != null)
                <td class="logo"><img src="{{ $image }}" alt="Image" width="120"></td>
                @else
                <td></td>
                @endif

                <td>
                    @if($student->level_id == 1)
                    <div class="midlle" style="padding-bottom: 40px;">استمارة التسجيل</div>
                    @else($student->level_id == 2)
                    <div class="midlle" style="padding-bottom: 40px;">استمارة تسجيل و إعادة تسجيل النساء والمتفرغات</div>
                    @endif
                    <div class="">2023/2024 </div>

                </td>

                @if(isset($image) && $image != null)
                <td class="logo2"><img src="{{ $image }}" alt="Image" width="120"></td>
                @else
                <td></td>
                @endif
            </tr>

        </table>
    </header>
    <table>
        <tr>
            <td style="width: 25%;"> الاسم الكامل :</td>
            <td style="width: 75%;">{{$student->name}}</td>

        </tr>
        <tr>

            <td style="width: 25%;"> العنوان:</td>
            <td style="width: 75%;">{{$student->address}}</td>
        </tr>
    </table>
    <!-- Table below the header -->
    <table>
        <tr>
            <td style="width: 25%;">تاريخ الازدياد :</td>
            <td style="width: 25%;">{{$student->date_birth}}</td>
            <td style="width: 25%;">مكان الازدياد :</td>
            <td style="width: 25%;">{{$student->lieu_birth}}</td>
        </tr>
        <tr>
        @if($student->level_id == 1)
            <td style="width: 25%;">رقم هاتف ولي الأمر :</td>
            @else
            <td style="width: 25%;">رقم هاتف :</td>
            @endif

            <td style="width: 25%;">{{$student->phone}}</td>
            </td>
            @if($student->level_id == 1)
            <td style="width: 25%;">مهنة ولي الأمر :</td>
            @else
            <td style="width: 25%;">مهنة :</td>
            @endif
            <td style="width: 25%;">{{$student->profession}}</td>
        </tr>
        <tr>
            <td style="width: 25%;">المستوى الدراسي :</td>
            <td style="width: 25%;">{{$student->niveau_etude}}</td>
            @if($student->class_id == 1)
            <td style="width: 25%;"> اسم المؤسسة :</td>
            <td style="width: 25%;">{{$student->name_school}}</td>
            @else
            <td style="width: 25%;">الحالة العائلية :</td>
            <td style="width: 25%;">{{$student->la_situation}}</td>
            @endif
            
        </tr>
    </table>
    <table>
        <tr style="height: 120px;">
            <td>
                <div class="price">الواجب الشهري : </div>
                <div class="prix" dir="rtl"> {{$student->service_mensuel	}} </div>
            </td>
            <td>
                <div class="price">الواجب السنوي :</div>
                <div class="prix" dir="rtl">{{$student->droit_annuel}} </div>
            </td>
        </tr>
    </table>
    <div class="reglement_class">
        <p dir="rtl" class="reglement_title">التزام ولي الأمر</p>
        <p dir="rtl">أنا الموقع (ة) أسفله :............................................الحامل (ة) للبطاقة
            الوطنية رقم
            :.............................</p>
        <p dir="rtl">التمس من السيد رئيس الجمعية قبول انخراط ابني/ابنتي ، و أشهد بصحة المعلومات الواردة اعلاه، وأتحمل
            كامل مسؤوليتي في ما قد يصدر عنه (ها) من أفعال و أقوال تمس بالجمعية و كذا تنقلاته (ها) من و إلى الجمعية.
        </p>
        <p dir="rtl" class="signature">توقيع ولي الأمر</p>
    </div>
    <div class="footer">
        <p class="commitment">تاريخ التسجيل :..........................</p>
        <p class="commitment">رقم التسجيل :...........................</p>
        <p dir="rtl" class="signature">توقيع ولي الأمر</p>




    </div>
</body>



</html>