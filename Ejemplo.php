<?php
include("excelwriter.inc.php");  
$excel=new ExcelWriter();
    
if($excel==false) {   
        echo $excel->error;
}

// PRIMERA FORMA DE ESCRITURA DE FILAS
$myArr=array("CeldaA1","CeldaB1","CeldaC1","CeldaD1");
$excel->writeLine($myArr);

$myArr=array("CeldaA2","CeldaB2","CeldaC2","CeldaD2");
$excel->writeLine($myArr);

//SEGUNDA FORMA DE ESCRITURA DE FILAS
$excel->writeRow();
$excel->writeCol("CeldaA3");
$excel->writeCol("CeldaB3");
$excel->writeCol("CeldaC3");
$excel->writeCol("CeldaD3");

$excel->writeRow();
$excel->writeCol("CeldaA4");
$excel->writeCol("CeldaB4");
$excel->writeCol("CeldaC4");
$excel->writeCol("CeldaD4");

$excel->close();
echo "Los datos se han grabado con &eacute;xito.";
?> 