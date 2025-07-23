<?php
function barcode($pdf, $x, $y, $code, $w = .4, $h = 10){
    $barChar['0'] = '101001101101';
    $barChar['1'] = '110100101011';
    $barChar['2'] = '101100101011';
    $barChar['3'] = '110110010101';
    $barChar['4'] = '101001101011';
    $barChar['5'] = '110100110101';
    $barChar['6'] = '101100110101';
    $barChar['7'] = '101001011011';
    $barChar['8'] = '110100101101';
    $barChar['9'] = '101100101101';
    $barChar['A'] = '110101001011';
    $barChar['B'] = '101101001011';
    $barChar['C'] = '110110100101';
    $barChar['D'] = '101011001011';
    $barChar['E'] = '110101100101';
    $barChar['F'] = '101101100101';
    $barChar['G'] = '101010011011';
    $barChar['H'] = '110101001101';
    $barChar['I'] = '101101001101';
    $barChar['J'] = '101011001101';
    $barChar['K'] = '110101010011';
    $barChar['L'] = '101101010011';
    $barChar['M'] = '110110101001';
    $barChar['N'] = '101011010011';
    $barChar['O'] = '110101101001';
    $barChar['P'] = '101101101001';
    $barChar['Q'] = '101010110011';
    $barChar['R'] = '110101011001';
    $barChar['S'] = '101101011001';
    $barChar['T'] = '101011011001';
    $barChar['U'] = '110010101011';
    $barChar['V'] = '100110101011';
    $barChar['W'] = '110011010101';
    $barChar['X'] = '100101101011';
    $barChar['Y'] = '110010110101';
    $barChar['Z'] = '100110110101';
    $barChar['-'] = '100101011011';
    $barChar['.'] = '110010101101';
    $barChar[' '] = '100110101101';
    $barChar['*'] = '100101101101';
    $barChar['$'] = '100100100101';
    $barChar['/'] = '100100101001';
    $barChar['+'] = '100101001001';
    $barChar['%'] = '101001001001';

    $code = strtoupper('*' . $code . '*');
    for($i=0; $i<strlen($code); $i++){
        $char = $code[$i];
        if (!isset($barChar[$char])) {
            $char = ' ';
        }
        $seq = $barChar[$char];
        for($bar=0; $bar<strlen($seq); $bar++){
            if($seq[$bar] == '1'){
                $pdf->Rect($x, $y, $w, $h, 'F');
            }
            $x += $w;
        }
        $x += $w; // Espaço entre caracteres
    }
}
