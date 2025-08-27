<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body{ font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1{ margin:0 0 12px; }
        table{ width:100%; border-collapse: collapse; }
        th,td{ border:1px solid #ccc; padding:6px; }
    </style>
    <title>{{ $title }}</title>
    </head>
<body>
    <h1>{{ $title }}</h1>
    <table>
        <thead><tr><th>#</th><th>Value</th></tr></thead>
        <tbody>
        @forelse($rows as $i => $row)
            <tr><td>{{ $i + 1 }}</td><td>{{ $row }}</td></tr>
        @empty
            <tr><td colspan="2">No data</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>

