<?php
// ini bagian 1 nya
$nilai = 85; //nilai awalnya
if ($nilai >= 90 && $nilai <= 100) {
    $grade = "A";
} elseif ($nilai >= 80 && $nilai <= 89) {
    $grade = "B";
} elseif ($nilai >= 70 && $nilai <= 79) {
    $grade = "C";
} elseif ($nilai >= 60 && $nilai <= 69) {
    $grade = "D";
} elseif ($nilai >= 0 && $nilai <= 59) {
    $grade = "E";
} else {
    $grade = "Tidak Valid";
}
echo "Nilai Anda adalah $nilai, Grade Anda adalah $grade.<br>";
//ini bagian 2
switch ($grade) {
    case "A":
    case "B":
        $keterangan = "Sangat Memuaskan, Anda Lulus!";
        break;
    case "C":
        $keterangan = "Cukup Memuaskan, Anda Lulus dengan Syarat.";
        break;
    case "D":
    case "E":
        $keterangan = "Mohon Maaf, Anda Tidak Lulus.";
        break;
    default:
        $keterangan = "Grade Tidak Dikenali.";
        break;
}
echo "Keterangan Kelulusan: $keterangan<br>";
//ini bagian 3
$prioritas_bimbingan = ($grade == "D" || $grade == "E") 
                       ? "Wajib Bimbingan" 
                       : "Tidak Wajib Bimbingan";

echo "Status Bimbingan: $prioritas_bimbingan";
?>
