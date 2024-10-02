<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة المحفوظ</title>
    <style>
        img {
            float: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            direction: rtl; /* Right-to-left direction */
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .blue-header {
            background-color: #e0f2fe;
        }

        .blue-row {
            background-color: #f2f8ff;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }

        .header-content {
            text-align: center;
        }

        .header-content h3 {
            margin: 0;
            font-size: 12px;
        }

        h1,
        h2 {
            font-size: 12px;
            margin: 0;
        }

        .centered-text {
            text-align: center;
        }
    </style>
</head>

<body>
    <table style="width: 100%; padding: 10px; margin: auto; border: none; margin-bottom: 3%; border-collapse: collapse;">
        <tr>
            <td style="padding: 10px; text-align: right; border: none;">
                @if($image != null)
                <img src="{{ $image }}" alt="Logo" style="height: 100px; width: auto; max-width: 100%;">
                @endif
            </td>
            <td style="padding: 10px; text-align: center; border: none;">
                <h1 style="font-size: 16px;" class="mb-3">قائمة المحفوظ</h1>
                <h4 style="margin: 0;">السنة دراسية: {{ $configuration->promotion }}</h4>
            </td>
            <td style="padding: 10px; text-align: left; border: none;">
                @if($image != null)
                <img src="{{ $image }}" alt="Logo" style="height: 100px; width: auto; max-width: 100%;">
                @endif
            </td>
        </tr>
    </table>

    <hr style="margin-bottom: 1%;">

    <div style="margin-bottom: 1%; display: flex; justify-content: space-between; direction: rtl;">
    <div style="text-align: right;">
        <h2><strong>المستوى:</strong> {{ $group->level->level}} - {{ $group->group }}</h2>
    </div>
</div>

    <hr style="margin-bottom: 1%;">
    <table>
        <thead class="blue-header">
            <tr>
                <th style="background-color: #94a3b8;">الاسم الكامل</th>
                <th style="background-color: #94a3b8;">المحفوظ الأول</th>
                <th style="background-color: #94a3b8;">المحفوظ الأخير</th> <!-- Inverse the order for RTL -->
            </tr>
        </thead>
        <tbody>
            @foreach ($notesForGroup as $student)
            <tr class="{{ $loop->iteration % 2 == 0 ? 'blue-row' : '' }}">
                <td>{{ $student['name'] }}</td>
                <td>{{ $student['first_note'] }}</td>
                <td>{{ $student['final_note'] }}</td> <!-- Inverse the order for RTL -->
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
