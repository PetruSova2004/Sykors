<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Processing Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>Task Processing Report</h1>
<p>The following tasks have been processed:</p>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Status</th>
        <th>Description</th>
        <th>Execution Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tasks as $task)
        <tr>
            <td>{{ $task->id }}</td>
            <td>{{ $task->status_name }}</td>
            <td>{{ $task->status_description }}</td>
            <td>{{ $task->execution_date }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<p>Thank you.</p>
</body>
</html>
