<!DOCTYPE html>
<html>
<head>
    <title>Tugas Perulangan While</title>
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

<h2>Perulangan While (Perkalian 3)</h2>

<table>
    <thead>
        <tr>
            <th>Operasi</th>
            <th>Hasil</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $angka = 1;

        while ($angka <= 10) {
            $hasil = 3 * $angka;

            echo "<tr>";
            echo "<td>3 x $angka</td>";
            echo "<td>$hasil</td>";
            echo "</tr>";

            $angka++;
        }
        ?>
    </tbody>
</table>

</body>
</html>
