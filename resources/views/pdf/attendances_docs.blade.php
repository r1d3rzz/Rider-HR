<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendances</title>
    <style>
        table {
            font-family: Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #cac8c8;
            text-align: center;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        .footerText{
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 0.8rem;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div style="margin-bottom: 10px">
            <div style="font-size: 1.2rem;">Attendances</div>
            <div>Report Time: {{Carbon\Carbon::parse(now())->format('Y-m-d H:i:s')}}</div>
        </div>
        <table>
            <tr>
                <th>Employee Name</th>
                <th>Date</th>
                <th>Checkin Time</th>
                <th>Checkout Time</th>
            </tr>
            @foreach ($attendances as $attendance)
            <tr>
                <td>{{optional($attendance->employee)->name}}</td>
                <td>{{$attendance->date}}</td>
                <td>{{$attendance->checkin_time}}</td>
                <td>{{$attendance->checkout_time}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="footerText">
        <div>Rider HR</div>
    </div>
</body>

</html>
