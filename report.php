<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();
date_default_timezone_set('Europe/Volgograd');

if(!isset($_SESSION['password']))
{
    header('Location: login.php');
    exit;
}




function sort_nested_arrays($array, $args = array('name' => 'asc','join_time' => 'asc') ) {
	
	usort($array, function($a, $b) use ($args){
		
		$res = 0;

		$a = (object) $a;
		$b = (object) $b;

		foreach($args as $k=>$v){
			if($a->$k == $b->$k) continue;

			$res = ($a->$k < $b->$k) ? -1 : 1;
			if($v=='desc') $res = -$res;
			break;
		}

		return $res;
	});

	return $array;
}

if (!empty($_POST['uid'])) {

	$zoom_uid = $_POST['uid'];	
		
	switch((int)$_POST['ftype']){
		case 1:
			$ftype='xlsx';
			break;
		case 2:
			$ftype='doc';
			break;
	}

	if((int)$_POST['ftype']==1){

		$filename = dirname(__FILE__).'/tmp/report.xlsx';
		
		// Смещение таблицы
		$num_offcet=7;

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Список студентов');

		$sheet->getComment('D'.($num_offcet-1))->getText()->createTextRun('просмотра лекции');
		$sheet->getComment('E'.($num_offcet-1))->getText()->createTextRun('при нескольких подключениях студента к лекции');

		$user_name=$_POST['name'];
		$sheet->setCellValue('B2', 'Пользователь: ');
		$sheet->getStyle('B2')->getFont()->setBold(true);
		$sheet->setCellValue('C2', $user_name);

		$topic=$_POST['topic'];
		$sheet->setCellValue('B3', 'Тема: ');
		$sheet->getStyle('B3')->getFont()->setBold(true);
		$sheet->setCellValue('C3', $topic);
		$sheet->setCellValue('B4', 'Длительность: ');
		$sheet->getStyle('B4')->getFont()->setBold(true);
		$sheet->setCellValue('D4', 'мин.');

		$sheet->setCellValue('H'.($num_offcet-1), 'Статистика посещения лекции:');
		$sheet->getStyle('H'.($num_offcet-1))->getFont()->setBold(true);

		$sheet->setCellValue('J'.$num_offcet, '+ - студент присутствовал на лекции более 50% времени');
		$sheet->setCellValue('J'.($num_offcet+1), 'нб - студент присутствовал на лекции менее 10 мин');
		$sheet->setCellValue('J'.($num_offcet+2), '1/2 - студент присутствовал на лекции дольше 10 мин, но меньше 50% времени');
		$sheet->setCellValue('H'.$num_offcet, 'Инфо:');
		$sheet->mergeCells('H'.$num_offcet.':H'.($num_offcet+2));
		$sheet->getStyle('H'.$num_offcet)->getAlignment()
							->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
							->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$sheet->getStyle('I')->getAlignment()
							->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
							->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$sheet->getStyle('H'.$num_offcet.':H'.($num_offcet+2))->getFill() ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID) ->getStartColor()->setRGB('FFE4D3');

		$sheet->setCellValue('I'.$num_offcet, '=COUNTIF(F'.$num_offcet.':F400,"+")');
		$sheet->setCellValue('I'.($num_offcet+1), '=COUNTIF(F'.$num_offcet.':F400,"нб")');
		$sheet->setCellValue('I'.($num_offcet+2), '=COUNTIF(F'.$num_offcet.':F400,"1/2?")');

		$sheet->setCellValue('J'.($num_offcet+4), '* - статистика может быть искажена, если студент изменял Имя в процессе лекции');

		//добавляем лог в конец файла

		
		file_put_contents("logs.txt", date("d/m/Y H:i:s").' - Получен отчёт за '.$_POST['date'].' Пользователь: '.$user_name.' - Тема: "'.$topic.'" (IP: '.$_SERVER['REMOTE_ADDR'].')'.PHP_EOL, FILE_APPEND | LOCK_EX);

		$sheet->getColumnDimension('A')->setWidth(5);

		$sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$sheet->getColumnDimension('B')->setWidth(20);
		$sheet->getStyle('B'.($num_offcet-1))->getFont()->setBold(true);
		$sheet->getStyle('B'.($num_offcet-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		$sheet->getStyle('B')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$sheet->getColumnDimension('C')->setWidth(15);

		$sheet->getColumnDimension('D')->setWidth(10);

		$sheet->getColumnDimension('E')->setWidth(10);

		$sheet->getStyle('C:E')->getAlignment()
							->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
							->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A'.($num_offcet-1).':E'.($num_offcet-1))->getAlignment()->setWrapText(true);

		$sheet->getStyle('A'.($num_offcet-1).':E'.($num_offcet-1))->getFont()->setBold(true);

		$sheet->getStyle('F')->getAlignment()
							->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
							->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		// Комментарии справа
		$comm_offcet=17;


		$sheet->mergeCells('H'.$comm_offcet.':J'.($comm_offcet+1));
		$sheet->getStyle('H'.$comm_offcet)->getAlignment()->setWrapText(true);
		$sheet->getStyle('H'.$comm_offcet)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getComment('H'.$comm_offcet)->setWidth("400px");
		$sheet->getComment('H'.$comm_offcet)->setHeight("300px");


		if (!empty($_POST['uid'])) {
			
			include('load.php');

			$result=send_api("/report/meetings/".$zoom_uid."/participants?page_size=300", "GET", $_POST['token']);

			//var_dump($result);
			$sheet->setCellValue('A'.($num_offcet-1), '№');
			$sheet->setCellValue('B'.($num_offcet-1), 'Ф.И.О. студента');
			$sheet->setCellValue('C'.($num_offcet-1), 'Время');
			$sheet->setCellValue('D'.($num_offcet-1), 'Длительность, мин');
			$sheet->setCellValue('E'.($num_offcet-1), 'Общее время, мин');

			$st_number=1;
			$previos_name='';
			$previos_duration=0;

			if($result['page_count']==2){
				
				$result2=send_api("/report/meetings/".$zoom_uid."/participants?page_size=300&next_page_token=".$result['next_page_token'], "GET", $_POST['token']);

				$result = array_merge_recursive($result, $result2);
			}

			$result['participants']=sort_nested_arrays($result['participants']);

			$num=2;
			$repeat=0;
			$rkey=0;

			foreach($result['participants'] as $key=>$value)
			{
				$duration=ceil($value['duration']/60);
				
				
				$timestamp_s=date("H:i", strtotime($value['join_time'])); 
				$timestamp_e=date("H:i", strtotime($value['leave_time']));
				
				//echo  ($key+1). "	".$value['name']."	".$value['join_time'] ."	".(int)($value['duration']/60)."\r\n";
				if($value['name']==$previos_name){
					if($repeat!=0){
						$sheet->unmergeCells('A'.$rkey.':A'.($key+$num_offcet-1));
						$sheet->unmergeCells('B'.$rkey.':B'.($key+$num_offcet-1));
						$sheet->unmergeCells('E'.$rkey.':E'.($key+$num_offcet-1));
						$sheet->unmergeCells('F'.$rkey.':F'.($key+$num_offcet-1));
						
					}
					
					$sheet->mergeCells('A'.$rkey.':A'.($key+$num_offcet));
					$sheet->mergeCells('B'.$rkey.':B'.($key+$num_offcet));

					$sheet->setCellValue('C'.($key+$num_offcet), $timestamp_s.' - '.$timestamp_e);
					
					$sheet->setCellValue('D'.($key+$num_offcet), $duration);	
					
					$previos_duration=$previos_duration+$duration;
					
					$sheet->mergeCells('E'.$rkey.':E'.($key+$num_offcet));
					$sheet->mergeCells('F'.$rkey.':F'.($key+$num_offcet));
					
					$sheet->setCellValue('E'.$rkey, $previos_duration);
					
					if($previos_duration<=10) $sheet->setCellValue('F'.$rkey, '=IF(E'.$rkey.'<=10,"нб","+")');
					else $sheet->setCellValue('F'.$rkey, '=IF(E'.$rkey.'>(C4/2),"+","1/2?")');
					
					$repeat++;
				
				} else {
					
					$repeat=0;
					$rkey=$key+$num_offcet;
					$sheet->setCellValue('A'.($key+$num_offcet), ($num-1));
					$sheet->setCellValue('B'.($key+$num_offcet), $value['name']);
					$sheet->setCellValue('C'.($key+$num_offcet), $timestamp_s.' - '.$timestamp_e);
					$sheet->setCellValue('D'.($key+$num_offcet), $duration);
					$sheet->setCellValue('E'.($key+$num_offcet), $duration);
					
					if($duration<=10) {
						$sheet->setCellValue('F'.($key+$num_offcet), '=IF(E'.($key+$num_offcet).'<=10,"нб","+")');
					} else {
						$sheet->setCellValue('F'.($key+$num_offcet), '=IF(E'.($key+$num_offcet).'>(C4/2),"+","1/2?")');
					}
					
					if($user_name==$value['name']) {
						$sheet->getStyle('A'.($key+$num_offcet).':E'.($key+$num_offcet))->getFill() ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID) ->getStartColor()->setRGB('E0E0E0');
						$sheet->setCellValue('C4', $duration);
						$sheet->setCellValue('F'.($key+$num_offcet), 'Лектор');
					}
						
					$previos_duration=$duration;
					$num++;
				}
				
				$previos_name=$value['name'];

			}
		}

		// Манипуляции с Excel
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