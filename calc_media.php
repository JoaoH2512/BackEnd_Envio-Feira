<?php
$nota1 = $_POST['nota1'] ?? 0;
$nota2 = $_POST['nota2'] ?? 0;
$nota3 = $_POST['nota3'] ?? 0;
$nota4 = $_POST['nota4'] ?? 0;

$nota_final = ($nota1 + $nota2 + $nota3 + $nota4) / 4;

if ($nota_final >= 0 && $nota_final <= 2.49) {
    echo "I";
} elseif ($nota_final >= 2.5 && $nota_final <= 4.99) {
    echo "R";
} elseif ($nota_final >= 5 && $nota_final <= 7.49) {
    echo "B";
} elseif ($nota_final >= 7.5 && $nota_final <= 10) {
    echo "MB";
} else {
    echo "Erro ao calcular nota";
}
?>

