<!DOCTYPE html>
<html>
<head>
    <title>Tugas Perulangan For</title>
    <style>
        table {
            border-collapse: collapse;
            width: 40%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Perulangan For (1 sampai 15)</h2>

<table>
    <thead>
        <tr>
            <th>Nomor Urut</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 1; $i <= 15; $i++) {
            echo "<tr>";
            echo "<td>$i</td>";
            echo "<td>Ini adalah baris ke-$i</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
