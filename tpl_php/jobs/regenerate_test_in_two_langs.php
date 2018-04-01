<?php
	/**
	 * - dev by @fortrou
	 * - восстановить тесты в одноязычных уроках, где создалось 2 теста с одной языковой маркировкой
	 *
	 **/
	require_once('../autoload.php');
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$sql = "SELECT * FROM os_lessons WHERE same_lang = 1";
	$res = $mysqli->query($sql);
	if($res->num_rows != 0) {
		while($row = $res->fetch_assoc()) {
			$sql_tests = sprintf("SELECT * FROM os_lesson_test WHERE id_lesson = %s", $row['id']);
			$res_tests = $mysqli->query($sql_tests);
			$tests_issue_tr = array();
			$tests_issue_contr = array();
			if($res_tests->num_rows != 0) {
				while($row_tests = $res_tests->fetch_assoc()) {
					if($row_tests['type'] == 4) {
						$tests_issue_tr[] = array( 'id' => $row_tests['id'],
												   'id_test' => $row_tests['id_test'],
												   'lang'	 => $row_tests['lang']
												 );
					} else if($row_tests['type'] == 5) {
						$tests_issue_contr[] = array( 'id' => $row_tests['id'],
													  'id_test' => $row_tests['id_test'],
													  'lang'	=> $row_tests['lang']
												 );
					}
				}
				if(count($tests_issue_tr) > 1) {
					if(count($tests_issue_tr) > 2) {
						for($i = 2; $i < count($tests_issue_tr); $i++) {
							Database::delete('os_lesson_test',array('id'=>$tests_issue_tr[$i]['id']));
						}
					}
					if($tests_issue_tr[0]['lang'] == $tests_issue_tr[1]['lang']) {
						Database::update('os_lesson_test', array('lang' => 'ua'), array('id'=>$tests_issue_tr[1]['id']));
					}
				}
				if(count($tests_issue_contr) > 1) {
					if(count($tests_issue_contr) > 2) {
						for($i = 2; $i < count($tests_issue_contr); $i++) {
							Database::delete('os_lesson_test',array('id'=>$tests_issue_contr[$i]['id']));
						}
					}
					if($tests_issue_contr[0]['lang'] == $tests_issue_contr[1]['lang']) {
						Database::update('os_lesson_test', array('lang' => 'ua'), array('id'=>$tests_issue_contr[1]['id']));
					}
				}
			}
		}
	}