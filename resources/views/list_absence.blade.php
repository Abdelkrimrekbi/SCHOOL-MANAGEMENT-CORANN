



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Absences</title>
    <style>
        img {
            float: left;
        }

       

        table {
            width: 100%;
            border-collapse: collapse;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            /* direction: rtl; Right-to-left direction */
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

   
<table style="width: 100%; padding: 10px; margin: auto; border: none; margin-bottom: 3%;">
    <tr style="height: 10%;">
        <td style="text-align: left; padding: 10px; margin: auto; border: none;">
            @if($image != null)
            <img src="{{ $image }}" alt="Logo" style="height: 100px; width: auto; max-width: 100%;">
            @endif
        </td>
        <td style="padding: 10px; margin: auto; border: none; text-align: center;">
            <h1 style="font-size: 28px; margin-bottom: 4px;">FICHE DE PRESENCE</h1>
            <h3 style="font-size: 14px; margin-top: 4px;">Année de formation: {{ $configuration->promotion }}</h3>
        </td>
        <td style="text-align: right; padding: 10px; margin: auto; border: none;">
            @if($image != null)
            <img src="{{ $image }}" alt="Logo" style="height: 100px; width: auto; max-width: 100%;">
            @endif
        </td>
    </tr>
</table>


    <hr style="margin-bottom: 1%;">

    <div style="margin-bottom: 1%; display: flex; justify-content: space-between;">
        <div style="float: left;">
            <h2 style="margin-bottom: 0;"><strong style="font-family:Arial, Helvetica, sans-serif ;">Niveau :
            </strong> 
            {{ $group->level->level}} - {{ $group->group }}
        </h2>
        </div>
       
          
           
             <div style="text-align: center;">
              <!-- <h2 style="margin-bottom: 0; margin-top: 0%; text-align: right; margin-right: 23%;"><strong style="font-family:Arial, Helvetica, sans-serif;">Niveau:</strong>
             {{ $group->level->level }}  -->
    <h2 style="font-size: 24px; margin-top: 0;">Mois : {{ ucfirst(trans($mois)) }}</h2>
</div>
    </div> 

    <hr style="margin-bottom: 1%;">

    <table>
      
        <thead style="background-color: rgb(148 163 184);">
            <tr>
                <th style="font-family:Arial, Helvetica, sans-serif ; background-color: #94a3b8;">NOM ET PRENOM</th>
                @for ($i = 1; $i <= 31; $i++) <th style="font-family:Arial, Helvetica, sans-serif ; background-color: #94a3b8;">
                    {{ $i }}
                    </th>
                    @endfor
                    <th style="border: none;"></th>
                    <th style="font-family:Arial, Helvetica, sans-serif ; background-color: #94a3b8;">NP</th>
                    <!-- <th style="font-family:Arial, Helvetica, sans-serif ;">%</th> -->
            </tr>
        </thead>
        <tbody>
            @foreach ($absencesForGroup as $student)
            <tr>
                <td>{{ $student['name'] }}</td>
                @for ($i = 1; $i <= 31; $i++)
                <td>{{ $student['absences'][$i] ?? '-' }}</td>
                @endfor
                <td style="background-color: #94a3b8;"></td>
                
                <td>{{ $student['total'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
