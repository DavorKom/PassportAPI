<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <style>
        table, th, td {
        padding: 10px;
        border: 1px solid black;
        border-collapse: collapse;
        }
    </style>
</head>
<body>
<table cellspacing="0" style="border: 1px solid black; width: 100%;">
    <thead>
    <tr align="left">
        <th style="border-bottom: 2px solid black; text-align:center">ID</th>
        <th style="border-bottom: 2px solid black; text-align:left">Name</th>
        <th style="border-bottom: 2px solid black; text-align:left">E-mail</th>
        <th style="border-bottom: 2px solid black; text-align:center">Contract Start Date</th>
        <th style="border-bottom: 2px solid black; text-align:center">Contract End Date</th>
        <th style="border-bottom: 2px solid black; text-align:left">Type</th>
        <th style="border-bottom: 2px solid black; text-align:center">Verified</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
        <tr>
            <td style="text-align:center">{{ $user['id'] }}</td>
            <td style="text-align:left">{{ $user['name'] }}</td>
            <td style="text-align:left">{{ $user['email'] }}</td>
            <td style="text-align:center">{{ $user['contract_start_date'] }}</td>
            <td style="text-align:center">{{ $user['contract_end_date'] }}</td>
            <td style="text-align:left">{{ $user['type'] }}</td>
            <td style="text-align:center">{{ $user['verified'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
