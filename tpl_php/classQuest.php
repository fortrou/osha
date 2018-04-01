<?php
	class Quest{
		private $name;
/** @made by fortrou
 * Тип 1 - тип с одним вариантом ответа
 * Тип 2 - тип с несколькими вариантами ответа
 * Тип 3 - тип с установкой правильной последовательности
 * Тип 4 - тип с установкой соответствий
 * Тип 5 - тип с выпадающим списком, аналог типа 1
 * Тип 6 - тип с письменным ответом(ответ на пример)
 **/

    public static function rewrite_answs($string, $arr,$pos){
        
        $arr[$pos] = $string;
        print("<br>$string<br>$pos<br>");
    }
    public static function unset_answs($arr){
        unset($arr);
    }
    public static function unset_data(){
        $_SESSION['correct'] = array();
        $_SESSION['match'] = array();
        $_SESSION['matchcnt'] = 5;
        $_SESSION['counter'] = 4;
        unset($_SESSION['correct']);
        unset($_SESSION['match']);
        unset($_SESSION['matchcnt']);
        unset($_SESSION['counter']);
        unset($_SESSION['an_pics']);
        //unset($_SESSION['doc']);
    }
    public static function strip_conors($string){
        $flag = 0;
        $iter = 0;
        //printf("<br>%s<br>",$string[4]);
        $cnt = strlen($string);
        //var_dump($cnt);

        $string = rtrim($string,"<br>");
        $string = rtrim($string,"<br><br>");
        $string = rtrim($string,"<br><br><br>");
        $string = rtrim($string,"<br>");
        $string = rtrim($string,"<br><br>");
        $string = rtrim($string,"<br><br><br>");
        $string = ltrim($string,"<br>");
        $string = ltrim($string,"<br><br>");
        $string = ltrim($string,"<br><br><br>");
        $string = ltrim($string,"<br>");
        $string = ltrim($string,"<br><br>");
        $string = ltrim($string,"<br><br><br>");

        for ($i = 0; $i <= $cnt; $i++) {
            //print($string[$i]."-");
            if ($string[$i] == ">") {
                //print("<br>Uhuuu<br>");
                $flag = 1;
                break;
            }
            $iter++;
        }
        if ($flag) {
            $string = substr($string, $iter+1,strlen($string));
            $flag = 0;
        }
        $cnt = strlen($string);

        $string = rtrim($string,"<br>");
        $string = rtrim($string,"<br><br>");
        $string = rtrim($string,"<br><br><br>");

        //var_dump($cnt);
        for ($i = $cnt; $i >= 0; $i--) {
            //print($string[$i]."-");
            if (isset($string[$i]) && !empty($string[$i]) && $string[$i] == "<") {
                //print("<br>Uhuuu<br>");
                $flag = 1;
                break;
            }
            $iter++;
        }
        if ($flag) {
            $string = substr($string,0, $i+3);
            $flag = 0;
        }
        //print("<br>$i --- $iter --- $flag --- $string<br>");
        return $string;

    }
    public static function getAlphabet(){
        $alphabet = array("А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й","К");
        return $alphabet;
    }
    
    /*** БЛОК функций для создания 1-5го типов тестов ***/
    
    public static function format_question_name($quest,$type,$cost,$id_of_test,$desc,$_doc=""){
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
        $sql = "INSERT INTO os_test_quest(name,type,cost, doc, full_desc ,id_test) VALUES('$quest','$type','$cost','$_doc','$desc','$id_of_test')";
        $result = $mysqli->query($sql);
			
        //var_dump($res);
        //print("<br>$sql<br>");
    }
    
    public static function get_idq($quest){
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
        $sql = "SELECT MAX(id_q) AS id_q FROM os_test_quest WHERE name = '$quest'";
        //print("<br>$sql<br>");
        $result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
        //var_dump($res);
        var_dump($row);
        //var_dump($link);
        return $row['id_q'];
    }
    
    public static function format_sql_1($quest, $arr_a, $type, $cost, $cnt,$id_of_test,$right,$desc,$_doc=""){
		
		Quest::format_question_name($quest,$type,$cost,$id_of_test,$desc,$_doc);
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
        $id_q = Quest::get_idq($quest);
        for($i = 0; $i < $cnt;$i++){
            if($_SESSION['correct'][$i] !== ""){
                
                $answ = $_SESSION['correct'][$i];
                
                if(!is_array($right) && $i+1==$right || is_array($right) && in_array($i+1,$right))
                    $sql = "INSERT INTO os_test_answs(answer,correct,id_quest) VALUES('$answ','1','$id_q')";
                else
                    $sql = "INSERT INTO os_test_answs(answer,correct,id_quest) VALUES('$answ','0','$id_q')";
//print("<br>$sql<br>");
                $result = $mysqli->query($sql);
                //var_dump($link);
                //var_dump($res);
                //print("<br>$sql<br>");
            }
            /*if($ans_pics != ""){
                var_dump($ans_pics[$i-1]);
            }*/
        }
        //unset($_SESSION['correct'][$i]);
        //header("Loacation:createQuest.php");
    }
    
    public static function format_sql_3($quest, $arr_a, $type, $cost, $cnt, $id_of_test, $numeration, $desc){
       
        Quest::format_question_name($quest,$type,$cost,$id_of_test,$desc);
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
        $id_q = Quest::get_idq($quest);
        for($i = 0; $i < $cnt;$i++){
            
            $answ = $_SESSION['correct'][$i];
            $match = $numeration[$i];
            $sql = "INSERT INTO os_test_answs(answer,correct,id_quest) VALUES('$answ','$match','$id_q')";
            $result = $mysqli->query($sql);
            //var_dump($res);
            //print("<br>$sql<br>");
        }
        unset($_SESSION['correct']);
        
    }
    
    public static function format_sql_4($quest, $arr_a, $type, $cost, $cnt,$id_of_test,$right,$desc){
       
        Quest::format_question_name($quest,$type,$cost,$id_of_test,$desc);
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
        $id_q = Quest::get_idq($quest);
        //var_dump($right);
        for($i = 0; $i < $cnt;$i++){
            $answ = $_SESSION['correct'][$i];
            $match = $right[$i];
            $sql = "INSERT INTO os_test_answs(answer,correct,id_quest) VALUES('$answ','$match','$id_q')";
            $result = $mysqli->query($sql);
            //print("<br>$sql<br>");
        }
        //header("Loacation:createQuest.php");
    }
    
    public static function format_sql_5($quest,$type,$cost,$id_of_test,$desc,$answer){
        Quest::format_question_name($quest,$type,$cost,$id_of_test,$desc);
        $db = Database::getInstance();
        $mysqli = $db->getConnection();
        $id_q = Quest::get_idq($quest);
        $sql = "INSERT INTO os_test_short_answ(answer,id_quest) VALUES('$answer','$id_q')";
        $res = $mysqli->query($sql);

    }

    function add_matches($quest,$arr_m,$mcount){
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
        $id_q = Quest::get_idq($quest);
        for($i = 1;$i <= $mcount;$i++){
            $match = $arr_m[$i-1];
            $sql = "INSERT INTO os_test_matches(match_text,num,id_quest) VALUES('$match','$i','$id_q')";
            $result = $mysqli->query($sql);
            //var_dump($res);
            //print("<br>$sql<br>");
        }
    }
    
    /*** БЛОК функций для создания 1-5го типов тестов ***/
    
    /*** БЛОК с добавлением и удалением поля для варианта ответа ***/
    
    function add_field($counter){
        $counter++;
        return $counter;
    }
    
    function del_field($counter){
        $counter--;
        return $counter;
    }
    
    /*** БЛОК с добавлением и удалением поля для варианта ответа ***/
    
    function update_q($text,$cost,$full_desc,$id){
        $db = Database::getInstance();
        $mysqli = $db->getConnection();
        //print($id);
        $sql_upd = "UPDATE os_test_quest SET name='$text', cost='$cost', full_desc='$full_desc' WHERE id_q='$id'";
        //print("<br>$sql<br>");
        $res_upd = $mysqli->query($sql_upd);
        //var_dump($res);
    }

    function update_1($arr_a,$id_ar,$right){
        //var_dump($arr_a);
        //var_dump($id_ar);
        //var_dump($right);
        $db = Database::getInstance();
        $mysqli = $db->getConnection();
        for($i = 0; $i <= count($arr_a)-1;$i++){

                $answ = $arr_a[$i];
                $id_a = $id_ar[$i];
                if(in_array($i+1,$right) || $i+1==$right)
                    $sql_upd = "UPDATE os_test_answs SET answer='$answ',correct='1' WHERE id_a='$id_a'";
                else
                    $sql_upd = "UPDATE os_test_answs SET answer='$answ',correct='0' WHERE id_a='$id_a'";
                //print("<br>$sql_upd<br>");
                $res_upd = $mysqli->query($sql_upd);
                //var_dump($res_upd);
            }
    }

    function update_2($arr_a,$id_ar,$right){
        //var_dump($arr_a);
        //var_dump($id_ar);
        //var_dump($right);
        $db = Database::getInstance();
        $mysqli = $db->getConnection();
        for($i = 0; $i <= count($arr_a)-1;$i++){
            $answ = $arr_a[$i];
            $match = $right[$i];
            $id_a = $id_ar[$i];
            $sql_upd = "UPDATE os_test_answs SET answer='$answ',correct='$match' WHERE id_a='$id_a'";
            $res_upd = $mysqli->query($sql_upd);
            //print("<br>$sql_upd<br>");
        }
    }

    function update_m($id_q,$arr_m,$mnum){
        $db = Database::getInstance();
        $mysqli = $db->getConnection();
        for($i = 0;$i < count($mnum);$i++){
            $match = $arr_m[$i];
            $num = $mnum[$i];
            $sql_upd = "UPDATE os_test_matches SET match_text='$match' WHERE id_quest='$id_q' AND num='$num'";
            $res_upd = $mysqli->query($sql_upd);
            //var_dump($res);
            //print("<br>$sql_upd<br>");
        }
    }
    
    public static function mix_1_data($id_q){
        $sql_data = "SELECT id_a, answer FROM os_test_answs WHERE id_quest='$id_q'";
        //print("<br>$sql_data<br>");
        $db = Database::getInstance();
        $mysqli = $db->getConnection();
        $res_data = $mysqli->query($sql_data);
        //var_dump($res_data);
        $num_data = $res_data->num_rows;

        $array = array("data" => array(),"id" => array());
        $pre_arr = array();
        //print("<br>$num_data<br>");
        while ($row_data = $res_data->fetch_assoc()) {
            //$pos = rand(0,$num_data-1);
            
            $random = mt_rand(0, $num_data-1);
            //print("<br>$random<br>");
            //var_dump($pre_arr);
            while (in_array($random, $pre_arr)){
                $random = mt_rand(0, $num_data-1);
            }
            //print("<br>$random<br>");
            $pre_arr[] = $random;
            $array['id'][$random] = $row_data['id_a'];
            $array['data'][$random] = $row_data['answer'];
            
        }
        return $array;    
    }
    /*function mix_3_data($id_q){
        $sql_data = "SELECT id_a, answer FROM os_test_answs WHERE id_quest='$id_q'";
        //print("<br>$sql_data<br>");
        $db = Database::getInstance();
        $mysqli = $db->getConnection();
        $res_data = $mysqli->query($sql_data);
        //var_dump($res_data);
        $num_data = $res_data->num_rows;

        $array = array("data" => array(),"id" => array());
        $pre_arr = array();
        //print("<br>$num_data<br>");
        while ($row_data = $res_data->fetch_assoc()) {
            //$pos = rand(0,$num_data-1);
            
            $random = mt_rand(0, $num_data-1);
            //print("<br>$random<br>");
            //var_dump($pre_arr);
            while (in_array($random, $pre_arr)){
                $random = mt_rand(0, $num_data-1);
            }
            //print("<br>$random<br>");
            $pre_arr[] = $random;
            $array['id'][$random] = $row_data['id_a'];
            $array['data'][$random] = $row_data['answer'];
            
        }
        return $array;  
    }*/
    public static function mix_m_data($id_q){
    $sql_data = "SELECT id_ma, match_text FROM os_test_matches WHERE id_quest='$id_q'";
    $db = Database::getInstance();
    $mysqli = $db->getConnection();
    $res_data = $mysqli->query($sql_data);
    $num_data = $res_data->num_rows;

    $array = array("data" => array(),"id" => array());
    $pre_arr = array();
    //print("<br>$num_data<br>");
    while ($row_data = $res_data->fetch_assoc()) {
        //$pos = rand(0,$num_data-1);
        /*if (!in_array($row_data['id_a'],$array['id']) && !in_array($row_data['answer'],$array['data'])) {
            
        }*/
        $random = mt_rand(0, $num_data-1);
        //print("<br>$random<br>");
        //var_dump($pre_arr);
        while (in_array($random, $pre_arr)){
            $random = mt_rand(0, $num_data-1);
        }
        //print("<br>$random<br>");
        $pre_arr[] = $random;
        $array['id'][$random] = $row_data['id_ma'];
        $array['data'][$random] = $row_data['match_text'];
        
    }
    return $array;    
    }
    
    
	}
?>