<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2013 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
//error_reporting(E_ALL);
ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);


if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/class/PHPExcel.php';

include("folder.php");
require_once(DIR_BASE."/class/class.tickets.php");

session_start();
session_write_close();

$oTicket = new Ticket;

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Soporte Akumen")
							 ->setLastModifiedBy("Sistema de Tickets")
							 ->setTitle("Reporte de Tickets")
							 ->setSubject("Documento de Excel")
							 ->setDescription("Reporte de tickets generado de la aplicación de Soporte")
							 ->setKeywords("office 2007 tickets reporte soporte")
							 ->setCategory("Reportes");


// Add some data
/*
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID Ticket')
			->setCellValue('B1', 'Problema')
			->setCellValue('C1', 'Estatus actual')
			->setCellValue('D1', 'Fecha de Asignación')
			->setCellValue('E1', 'Fecha de Recepción')
			->setCellValue('F1', 'Fecha de Inicio de Atención')
			->setCellValue('G1', 'Fecha de Termino')
			->setCellValue('H1', 'Tiempo de Atención')
			->setCellValue('I1', 'Tiempo de Respuesta')
			->setCellValue('J1', 'Tiempo Total');*/
			
$encabezados = array("ID Ticket","Problema", "Estatus Actual", "Fecha Asignacion", "Fecha Recepcion", "Fecha de inicio de Atencion", "Fecha de Termino", "Tiempo de Atencion", "Tiempo de Respuesta", "Tiempo Total");


$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()
			->fromArray($encabezados, NULL, 'A1');			
			
//Llenado de Datos
function dateDiff($start, $end) {
		
		$datetime1 = new DateTime($start);
		$datetime2 = new DateTime($end);
		$interval = $datetime1->diff($datetime2);
		//return $interval->format('%R%a days');

		return $interval->format("%R%a dia(s) con %H:%I:%S (hrs)");
	}
	
$oTicket = new Ticket;
$oTicket->isReport();
$oTicket->setValores(array("empresa"=>$_GET["empresa"],
							"fechainicio"=>$_GET["fecha_inicio"],
							"fechafin"=>$_GET["fecha_fin"]));
/*$oTicket->setValores(array("empresa"=>"1",
							"fechafin"=>"2013/10/03 09:00",
							"fechainicio"=>"2013/07/01 00:00"));*/

$mytickets = $oTicket->consultaTicket();
	
	if(sizeof($mytickets) > 0){
		$i = 2;
		foreach($mytickets as $indice => $contenido){
			
			//Quitamos los valores que ya no necesitaremos
			unset($contenido['intIdEmpresa']);
			
			$tiempo_atencion = dateDiff($contenido["fecha_problema"],$contenido["fecha_alta"]);
			$tiempo_respuesta = dateDiff($contenido["fecha_alta"],$contenido["fecha_asignacion"]);
			$tiempo_total = dateDiff($contenido["fecha_alta"],$contenido["fecha_termino"]);
			
			
			$objPHPExcel->getActiveSheet()
						->setCellValue('A'.$i, $contenido["intIdUnico"])
						->setCellValue('B'.$i, $contenido["problema"])
						->setCellValue('C'.$i, $contenido["estado_actual"])
						->setCellValue('D'.$i, $contenido["fecha_alta"])
						->setCellValue('E'.$i, $contenido["fecha_problema"])
						->setCellValue('F'.$i, $contenido["fecha_asignacion"])
						->setCellValue('G'.$i, $contenido["fecha_termino"])
						->setCellValue('H'.$i, $tiempo_atencion)
						->setCellValue('I'.$i, $tiempo_respuesta)
						->setCellValue('J'.$i, $tiempo_total);
			$i++;
			/*
			echo "
			<tr>
				<td>".$contenido["intIdUnico"]."</td>
				<td>".$contenido["problema"]."</td>
				<td>".$contenido["estado_actual"]."</td>
				<td>".$contenido["fecha_alta"]."</td>
				<td>".$contenido["fecha_problema"]."</td>
				<td>".$contenido["fecha_asignacion"]."</td>
				<td>".$contenido["fecha_termino"]."</td>
				<td>".$tiempo_atencion."</td>
				<td>".$tiempo_respuesta."</td>
				<td>".$tiempo_total."</td>
			</tr>";*/
			}
	}


// Rename worksheet
$fecha = $oTicket->timeZone();
$fecha = substr($fecha, 0,10);
$fecha = str_replace('/',"-",$fecha);
$objPHPExcel->getActiveSheet()->setTitle('Reporte de Tickets '.$fecha);


//Formato
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(24);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(24);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(24);


$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);

//Formato a las Celdas de titulo
$objPHPExcel->getActiveSheet()
			->getStyle('A1:J1')
			->applyFromArray(array('fill' => array(
													'type' => PHPExcel_Style_Fill::FILL_SOLID,
													'color'	=> array('argb' => 'FFCCFFCC')
													),
									'borders' => array(
													'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
													'right'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
													)
									)
							);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_'.$fecha.'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>