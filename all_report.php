<?php
error_reporting(E_ALL);ini_set('display_errors',1);

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function sort_nested_arrays( $array, $args = array('name' => 'asc','join_time' => 'asc') ){
	usort( $array, function( $a, $b ) use ( $args ){
		$res = 0;

		$a = (object) $a;
		$b = (object) $b;

		foreach( $args as $k => $v ){
			if( $a->$k == $b->$k ) continue;

			$res = ( $a->$k < $b->$k ) ? -1 : 1;
			if( $v=='desc' ) $res= -$res;
			break;
		}

		return $res;
	} );

	return $array;
}

if (isset($_POST['type'])&&$_POST['type']!="") {
switch((int)$_POST['type']){
	case 1:
        $type='xlsx';
        break;
	case 2:
        $type='doc';
        break;
}

if((int)$_POST['type']==1){

$filename = "report.xlsx";

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Список студентов');

$sheet->getComment('D1')->getText()->createTextRun('просмотра лекции');
$sheet->getComment('E1')->getText()->createTextRun('при нескольких подключениях студента к лекции');


$sheet->getColumnDimension('A')->setWidth(5);

$sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getStyle('B1')->getFont()->setBold(true);
$sheet->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('B')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

$sheet->getColumnDimension('C')->setWidth(15);

$sheet->getColumnDimension('D')->setWidth(10);

$sheet->getColumnDimension('E')->setWidth(10);

$sheet->getStyle('C:E')->getAlignment()
					->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
					->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

$sheet->getStyle('A1:E1')->getAlignment()->setWrapText(true);

$sheet->getStyle('A1:E1')->getFont()->setBold(true);

$sheet->setCellValue('H3', 'Лайфхак: определение "Онлайн прогульщиков"');
$sheet->getComment('H3')->getText()->createTextRun('Данная категория студентов в конференции ZOOM присутствует, но лектора  не смотрят и не слушают. После окончания лекции можно подождать минуть 10: те студенты, которые смотрели лекцию, как правило, выходят из конференции. Но остаются студенты, которые висят в конференции до последнего. Можно сделать логическое заключение: данные студенты просто зашли в конференцию, оставилили гаджеты включенными и отошли по делам. В отчёте у данных студентов время выхода будет соответствовать вашему (имя лектора в отчёте имеет вид: "ВолгГМУ** ВолгГМУ**") минуту в минуту (сразу видно таких студентов). PS: НЕ является педагогической рекомендацией! Лишь предположение, основанное на логических заключениях и не может исключать других возможных причин у студента не покидать конференцию ZOOM');
$sheet->mergeCells('H3:J4');
$sheet->getStyle('H3')->getAlignment()->setWrapText(true);
$sheet->getStyle('H3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getComment("H3")->setWidth("400px");
$sheet->getComment("H3")->setHeight("300px");


if (isset($_POST['uid'])&&$_POST['uid']!="") {


	
include('load.php');


$result=send_api("https://api.zoom.us/v2/report/meetings/".$_POST['uid']."/participants?page_size=300");

//var_dump($result);
$sheet->setCellValue('A1', '№');
$sheet->setCellValue('B1', 'Ф.И.О. студента');
$sheet->setCellValue('C1', 'Время');
$sheet->setCellValue('D1', 'Длительность, мин');
$sheet->setCellValue('E1', 'Общее время, мин');



$st_number=1;
$previos_name='';
$previos_duration=0;


if($result['page_count']==2){
	
	$result2=send_api("https://api.zoom.us/v2/report/meetings/".$_POST['uid']."/participants?page_size=300&next_page_token=".$result['next_page_token']);

$result = array_merge_recursive($result, $result2);
}


$result['participants']=sort_nested_arrays($result['participants']);
$num=2;
$repeat=0;
$rkey=0;

foreach($result['participants'] as $key=>$value)
{
	$duration=ceil($value['duration']/60);
	
	date_default_timezone_set('Europe/Volgograd');
	$timestamp_s=date("H:i", strtotime($value['join_time'])); 
	$timestamp_e=date("H:i", strtotime($value['leave_time']));
    
	//echo  ($key+1). "	".$value['name']."	".$value['join_time'] ."	".(int)($value['duration']/60)."\r\n";
	if($value['name']==$previos_name){
	if($repeat!=0){
		$sheet->unmergeCells('A'.$rkey.':A'.($key+1));
		$sheet->unmergeCells('B'.$rkey.':B'.($key+1));
		$sheet->unmergeCells('E'.$rkey.':E'.($key+1));
		
		
	}
	$sheet->mergeCells('A'.$rkey.':A'.($key+2));
	$sheet->mergeCells('B'.$rkey.':B'.($key+2));


	$sheet->setCellValue('C'.($key+2), $timestamp_s.' - '.$timestamp_e);
	
	
	
	
	$sheet->setCellValue('D'.($key+2), $duration);	
	
	$previos_duration=$previos_duration+$duration;
	
	$sheet->mergeCells('E'.$rkey.':E'.($key+2));
	$sheet->setCellValue('E'.$rkey, $previos_duration);
	$repeat++;
	} else {
	$repeat=0;
	$rkey=$key+2;
	$sheet->setCellValue('A'.($key+2), ($num-1));
	$sheet->setCellValue('B'.($key+2), $value['name']);
	$sheet->setCellValue('C'.($key+2), $timestamp_s.' - '.$timestamp_e);
	$sheet->setCellValue('D'.($key+2), $duration);
	$sheet->setCellValue('E'.($key+2), $duration);
	$previos_duration=$duration;
	$num++;
	}
	
	$previos_name=$value['name'];

}


	





}

try {
    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);
    $content = file_get_contents($filename);
} catch(Exception $e) {
    exit($e->getMessage());
}
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename='.$filename);


unlink($filename);
exit($content);


}

}
?>

