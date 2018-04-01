<?php
	session_start();

	require_once('../tpl_php/classDatabase.php');
	require_once('Classes/PHPExcel.php');

	$db = Database::getInstance();
	$mysqli = $db->getConnection();

		$phpexcel = new PHPExcel(); // Создаём объект PHPExcel
			/* Каждый раз делаем активной 1-ю страницу и получаем её, потом записываем в неё данные */
			$page = $phpexcel->setActiveSheetIndex(0); // Делаем активной первую страницу и получаем её
			$page->setTitle("Журнал учеников");
		

		$sql = sprintf("SELECT CONCAT(u.surname,' ',u.name,' ',u.patronymic) AS fio, u.class, l.title_ua AS tit_ua, l.title_ru AS tit_ru, s.name,
				l.date_ru,l.date_ua, j.mark_tr, j.mark_contr, j.mark_hw, j.mark_com
				FROM os_users AS u, os_subjects AS s, os_lessons AS l, os_journal AS j WHERE
				u.id = j.id_s AND j.id_l = l.id AND l.subject=s.id AND j.status=1 ORDER BY u.class");
		//print($sql);
		$res = $mysqli->query($sql);
		//var_dump($res);
		$iter = 2;
			$page->setCellValueByColumnAndRow(0, 1, "ФIО");
			$page->setCellValueByColumnAndRow(1, 1, "Клас");
			$page->setCellValueByColumnAndRow(2, 1, "Предмет");
			$page->setCellValueByColumnAndRow(3, 1, "Назва уроку");
			$page->setCellValueByColumnAndRow(4, 1, "Название урока");
			$page->setCellValueByColumnAndRow(5, 1, "Дата(рос)");
			$page->setCellValueByColumnAndRow(6, 1, "Дата(укр)");
			$page->setCellValueByColumnAndRow(7, 1, "Тренувальний тест");
			$page->setCellValueByColumnAndRow(8, 1, "Тестове ДЗ");
			$page->setCellValueByColumnAndRow(9, 1, "Творче дз");
			$page->setCellValueByColumnAndRow(10, 1, "Загальний бал");
		while ($row = $res->fetch_assoc()) {
			//var_dump($row);
			//print("<br>");
			$page->setCellValueByColumnAndRow(0, $iter, $row['fio']);
			$page->setCellValueByColumnAndRow(1, $iter, $row['class']);
			$page->setCellValueByColumnAndRow(2, $iter, $row['name']);
			$page->setCellValueByColumnAndRow(3, $iter, $row['tit_ru']);
			$page->setCellValueByColumnAndRow(4, $iter, $row['tit_ua']);
			$page->setCellValueByColumnAndRow(5, $iter, $row['date_ru']);
			$page->setCellValueByColumnAndRow(6, $iter, $row['date_ua']);
			$page->setCellValueByColumnAndRow(7, $iter, $row['mark_tr']);
			$page->setCellValueByColumnAndRow(8, $iter, $row['mark_contr']);
			$page->setCellValueByColumnAndRow(9, $iter, $row['mark_hw']);
			$page->setCellValueByColumnAndRow(10, $iter, ($row['mark_hw']+$row['mark_contr']));
			$iter++;
		}
		$sql = "SELECT DISTINCT CONCAT(u.surname,' ',u.name,' ',u.patronymic) AS fio, u.class, s.name, j.date_ru,j.date_ua, j.mark_tr, j.mark_contr, j.mark_hw, j.mark_com,j.title_t_ru AS tit_ru, j.title_t_ua AS tit_ua
		FROM os_users AS u, os_subjects AS s, os_journal AS j WHERE
		u.id = j.id_s AND j.id_subj=s.id AND j.status=2 ORDER BY u.class";
		//print($sql);
		$res = $mysqli->query($sql);
		//var_dump($res);
		$iter = 2;
		$phpexcel->createSheet();
		$page2 = $phpexcel->setActiveSheetIndex(1); // Делаем активной вторую страницу и получаем её
			$page2->setTitle("Тематические оценки учеников");
			//var_dump($page2);
			$page2->setCellValueByColumnAndRow(0, 1, "ФИО");
			$page2->setCellValueByColumnAndRow(1, 1, "Класс");
			$page2->setCellValueByColumnAndRow(2, 1, "Предмет");
			$page2->setCellValueByColumnAndRow(3, 1, "Назва тематичної");
			$page2->setCellValueByColumnAndRow(4, 1, "Название тематической");
			$page2->setCellValueByColumnAndRow(5, 1, "Дата(рус)");
			$page2->setCellValueByColumnAndRow(6, 1, "Дата(укр)");
			$page2->setCellValueByColumnAndRow(7, 1, "тренировочный тест");
			$page2->setCellValueByColumnAndRow(8, 1, "Тестовое ДЗ");
			$page2->setCellValueByColumnAndRow(9, 1, "Творческое дз");
			$page2->setCellValueByColumnAndRow(10, 1, "Общий балл");
		while ($row = $res->fetch_assoc()) {
			//var_dump($row);
			//print("<br>");
			$page2->setCellValueByColumnAndRow(0, $iter, $row['fio']);
			$page2->setCellValueByColumnAndRow(1, $iter, $row['class']);
			$page2->setCellValueByColumnAndRow(2, $iter, $row['name']);
			$page2->setCellValueByColumnAndRow(3, $iter, $row['tit_ru']);
			$page2->setCellValueByColumnAndRow(4, $iter, $row['tit_ua']);
			$page2->setCellValueByColumnAndRow(5, $iter, $row['date_ru']);
			$page2->setCellValueByColumnAndRow(6, $iter, $row['date_ua']);
			$page2->setCellValueByColumnAndRow(7, $iter, $row['mark_tr']);
			$page2->setCellValueByColumnAndRow(8, $iter, $row['mark_contr']);
			$page2->setCellValueByColumnAndRow(9, $iter, $row['mark_hw']);
			$page2->setCellValueByColumnAndRow(10, $iter, $row['mark_com']);
			$iter++;
		}
		$phpexcel->createSheet();
		$page3 = $phpexcel->setActiveSheetIndex(2); // Делаем активной третью страницу и получаем её
		$sql = sprintf("SELECT CONCAT(u.surname,' ',u.name,' ',u.patronymic) AS fio, u.class, l.title_ua AS tit_ua, l.title_ru AS tit_ru, s.name,
				l.date_ru,l.date_ua, j.mark_tr, j.mark_contr, j.mark_hw, j.mark_com
				FROM os_users AS u, os_subjects AS s, os_lessons AS l, os_journal AS j WHERE
				u.id = j.id_s AND j.id_l = l.id AND l.subject=s.id AND j.status=3 ORDER BY u.class");
		//print($sql);
		$res = $mysqli->query($sql);
		//var_dump($res);
		$page3->setTitle("Контрольные оценки учеников");
		$iter = 2;
			$page3->setCellValueByColumnAndRow(0, 1, "ФИО");
			$page3->setCellValueByColumnAndRow(1, 1, "Класс");
			$page3->setCellValueByColumnAndRow(2, 1, "Предмет");
			$page3->setCellValueByColumnAndRow(3, 1, "Назва уроку");
			$page3->setCellValueByColumnAndRow(4, 1, "Название урока");
			$page3->setCellValueByColumnAndRow(5, 1, "Дата(рус)");
			$page3->setCellValueByColumnAndRow(6, 1, "Дата(укр)");
			$page3->setCellValueByColumnAndRow(7, 1, "тренировочный тест");
			$page3->setCellValueByColumnAndRow(8, 1, "Тестовое ДЗ");
			$page3->setCellValueByColumnAndRow(9, 1, "Творческое дз");
			$page3->setCellValueByColumnAndRow(10, 1, "Общий балл");
		while ($row = $res->fetch_assoc()) {
			//var_dump($row);
			//print("<br>");
			$page3->setCellValueByColumnAndRow(0, $iter, $row['fio']);
			$page3->setCellValueByColumnAndRow(1, $iter, $row['class']);
			$page3->setCellValueByColumnAndRow(2, $iter, $row['name']);
			$page3->setCellValueByColumnAndRow(3, $iter, $row['tit_ru']);
			$page3->setCellValueByColumnAndRow(4, $iter, $row['tit_ua']);
			$page3->setCellValueByColumnAndRow(5, $iter, $row['date_ru']);
			$page3->setCellValueByColumnAndRow(6, $iter, $row['date_ua']);
			$page3->setCellValueByColumnAndRow(7, $iter, $row['mark_tr']);
			$page3->setCellValueByColumnAndRow(8, $iter, $row['mark_contr']);
			$page3->setCellValueByColumnAndRow(9, $iter, $row['mark_hw']);
			$page3->setCellValueByColumnAndRow(10, $iter, ($row['mark_hw']+$row['mark_contr']));
			$iter++;
		}
		$phpexcel->setActiveSheetIndex(0);
 header ( "Content-type: application/vnd.ms-excel" );
 header ( "Content-Disposition: attachment; filename='reserv_copy.xls'" );
		$objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');

		$objWriter->save('php://output');
		header("Location:".$_SERVER['HTTP_REFERER']);
		?>
