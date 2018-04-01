<?php
	class QuestOver{
		/*** БЛОК с представлением вопросов в разборе на правильные и нет ***/
    
    function first_type($quest,$answ){
        if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru') {
            $didntAnswer = "Вы не дали ответ на этот вопрос";
        } else {
            $didntAnswer = "Ви не надали відповідь на це запитання";
        }
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
        $qid = $quest;
        $sql_question = "SELECT * FROM os_test_quest WHERE id_q='$qid'";
        $res_question = $mysqli->query($sql_question);
        $row_question = $res_question->fetch_assoc();
        $sql = "SELECT * FROM os_test_answs WHERE id_quest='$qid'";
        //print("<br>$sql<br>");    
        $result = $mysqli->query($sql);
        
        $num = $result->num_rows;
        $num_el = 1;
        
        /**
         * Строка для тестирования:
         * print("<br>$answ --- $num_el --- ".$row['correct']);
         **/
        //var_dump($result);
            printf("<tr>
                <td colspan='3'>%s</td>
            </tr>
            <tr>
            <td>
            <ul style='list-style:none;'>",$row_question['name']);
                    /*for($i = 1; $i<=$num; $i++){
                        if($answ == $i)
                           
                        else
                            print("<li><input type='radio' name='id$qid value='$i' disabled class='radio'></li>");
                    }*/
                print("</ul>
                <ul style='list-style:none;'>");
                $flag = 0;
                while($row = $result->fetch_assoc()){
                    
                    //print("<br>$answ --- $num_el --- ".$row['correct']);
                    if($answ == $row['id_a'] && $row['correct'] == 1){
                        $flag = 1;
                        print("<li class='test_q_true'>");
                        print("<input type='radio' name='id$qid' value='$num_el' checked disabled class='radio'>");
                        //print("<br>$answ --- $num_el --- ".$row['correct']);
                        printf("<label>%s</label>",Quest::strip_conors($row['answer']));
                        print("</li>");
                    }
                    if($answ == $row['id_a'] && $row['correct'] != 1){
                        print("<li class='test_q_false'>");
                        print("<input type='radio' name='id$qid' value='$num_el' checked disabled class='radio'>");
                        //print("<br>$answ --- $num_el --- ".$row['correct']);
                        printf("<label>%s</label>",Quest::strip_conors($row['answer']));
                        print("</li>");
                        $flag = 1;
                    }
                    if($answ != $row['id_a'] && $row['correct'] == 1){

                        print("<li class='test_q_true'>");
                        print("<input type='radio' name='id$qid' value='$num_el' checked disabled class='radio'>");
                        //print("<br>$answ --- $num_el --- ".$row['correct']);
                        printf("<label>%s</label>",Quest::strip_conors($row['answer']));
                        print("</li>");
                    }
                    if($answ != $row['id_a'] && $row['correct'] != 1){
                        print("<li>");
                        print("<input type='radio' name='id$qid' value='$num_el' disabled class='radio'>");
                        //print("<br>$answ --- $num_el --- ".$row['correct']);
                        printf("<label>%s</label>",Quest::strip_conors($row['answer']));
                        print("</li>");
                    }
                    $num_el++;
                    
                }
                print("</ul>
                </td>");
                $num_el = 0;
                print("<td></td>
                    <td>");
                if ($flag == 0) {
                    print("<p>$didntAnswer</p>");
                }
                print("</td>");
                
            print("</tr>");
        
    }
    function second_type($quest,$answ){
        if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru') {
            $didntAnswer = "Вы не дали ответ на этот вопрос";
        } else {
            $didntAnswer = "Ви не надали відповідь на це запитання";
        }
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
        $qid = $quest;
        $sql_question = "SELECT * FROM os_test_quest WHERE id_q='$qid'";
        $res_question = $mysqli->query($sql_question);
        $row_question = $res_question->fetch_assoc();
        $sql = "SELECT * FROM os_test_answs WHERE id_quest='$qid'";
        $result = $mysqli->query($sql);
        
        $num = $result->num_rows;
        $num_el = 1;
        
        /**
         * Строка для тестирования:
         * print("<br>$answ --- $num_el --- ".$row['correct']);
         **/
         printf("<tr>
                <td colspan='3'>%s</td>
            </tr>
            <tr>
            <td>
            <ul style='list-style:none;'>",$row_question['name']);
                    /*for($i = 1; $i<=$num; $i++){
                        if(in_array($i,$answ))
                            print("<li><input type='checkbox' name='id$qid' checked disabled class='radio'></li>");
                        else
                            print("<li><input type='checkbox' name='id$qid' disabled class='radio'></li>");
                    }*/
                print("</ul>
                <ul style='list-style:none;'>");
                $flag = 0;
                while($row = $result->fetch_assoc()){
                    //print("<br>$answ --- $num_el --- ".$row['correct']);
                    
                    if(in_array($qid.$row['id_a'],$answ) && $row['correct'] == 1){
                        $flag = 1;
                        print("<li class='test_q_true'>");
                        print("<input type='radio' name='id$qid$num_el' value='$num_el' checked disabled class='radio'>");
                        /*var_dump($answ);
                        print("<br>$answ --- $num_el --- ".$row['correct']);*/
                        printf("<label>%s</label>",Quest::strip_conors($row['answer']));
                        print("</li>");
                    }
                    if(in_array($qid.$row['id_a'],$answ) && $row['correct'] != 1){
                        $flag = 1;
                        print("<li class='test_q_false'>");
                        print("<input type='radio' name='id$qid$num_el' value='$num_el' checked disabled class='radio'>");
                        /*var_dump($answ);
                        print("<br>$answ --- $num_el --- ".$row['correct']);*/
                        printf("<label>%s</label>",Quest::strip_conors($row['answer']));
                        print("</li>");
                    }
                    if(!in_array($qid.$row['id_a'],$answ) && $row['correct'] == 1){
                        print("<li class='test_q_true'>");
                        print("<input type='radio' name='id$qid$num_el' value='$num_el' checked disabled class='radio'>");
                        /*var_dump($answ);
                        print("<br>$answ --- $num_el --- ".$row['correct']);*/
                        printf("<label>%s</label>",Quest::strip_conors($row['answer']));
                        print("</li>");
                    }
                    if(!in_array($qid.$row['id_a'],$answ) && $row['correct'] != 1){
                        print("<li>");
                        print("<input type='radio' name='id$qid$num_el' value='$num_el' disabled class='radio'>");
                        /*var_dump($answ);
                        print("<br>$answ --- $num_el --- ".$row['correct']);*/
                        printf("<label>%s</label>",Quest::strip_conors($row['answer']));
                        print("</li>");
                    }
                    $num_el++;
                    
                }
                print("</ul>
                </td>");
                $num_el = 0;
                print("<td></td>
                    <td>");
                if ($flag == 0) {
                    print("<p>$didntAnswer</p>");
                }
                print("</td>");
                
            print("</tr>");
    }
    
    function third_type($quest,$answ){
        if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru') {
            $didntAnswer = "Вы не дали ответ на вопросы: ";
        } else {
            $didntAnswer = "Ви не надали відповідь на запитання: ";
        }
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
        $alphabet = Quest::getAlphabet();
        $abc = array("a","b","c","d","e","f","g","h","i","j");

        $qid = $quest;
        $sql_question = "SELECT * FROM os_test_quest WHERE id_q='$qid'";
        $res_question = $mysqli->query($sql_question);
        $row_question = $res_question->fetch_assoc();
        $sql = "SELECT * FROM os_test_answs WHERE id_quest='$qid'";
        $result = $mysqli->query($sql);
        
        $num = $result->num_rows;
        $num_el = 0;
        $used_arr = array();
        $rarr = array();
        $num_el = 0;
        /**
         * Строка для тестирования:
         * print("<br>".$answ[$num_el]."--- $num_el --- ".$row['correct']);
         **/
        //var_dump($answ);
        printf("<tr>
                <td colspan='3'>%s</td>
            </tr>
            <tr>
            <td>
                <table>
                    <tr>
                        <td>
                <ul style='list-style:none;' class='test_ul_sootv'>",$row_question['name']);
                while($row = $result->fetch_assoc()){
                    //print("<br>".$answ[$num_el]."--- $num_el --- ".$row['correct']);
                    /*if(in_array($num_el+1,$answ) && $row['correct'] == $answ[$num_el]){
                        //print("<br>".$answ[$num_el]."--- $num_el --- ".$row['correct']);
                        printf("<li class='test_q_true'><span>%s) </span>%s</li>",$num_el+1,$row['answer']);
                    }
                    if(in_array($num_el+1,$answ) && $row['correct'] != $answ[$num_el]){
                        //print("<br>".$answ[$num_el]."--- $num_el --- ".$row['correct']);
                        printf("<li class='test_q_false'><span>%s) </span>%s</li>",$num_el+1,$row['answer']);
                    }
                    if(!in_array($num_el+1,$answ) && $row['correct'] == $answ[$num_el]){
                        //print("<br>".$answ[$num_el]."--- $num_el --- ".$row['correct']);
                        printf("<li class='test_q_false'><span>%s) </span>%s</li>",$num_el+1,$row['answer']);
                    }
                    if(!in_array($num_el+1,$answ) && $row['correct'] != $answ[$num_el]){
                        //print("<br>".$answ[$num_el]."--- $num_el --- ".$row['correct']);*/
                        printf("<li class='bukva_%s'>%s</li>",$abc[$num_el],$row['answer']);
                   // }
                    $num_el++;
                    $rarr[] = $row['correct'];
                }
                print("</ul>
                    </td>
                    <td>
                    <ul style='width:250px;margin-top:25px; list-style:none;'>");
                        /*var_dump($answ);
                        print("<br>");
                        var_dump($rarr);
                        print("<br>");*/
                    print("<li>
                        <ul class='matchRadio'>");
                        printf("<li style='width:30px;'></li>");
                        
                        for($it = 0; $it < $num; $it++){
                            printf("<li>%s</li>",$alphabet[$it]);
                        }
                        print("</ul>
                        <div style='clear:both;'></div>
                    </li>");
                        //for($i = 1; $i <= $num; $i++){
                        $i = 1;
                        $result = $mysqli->query($sql);
                        $flag = 0;
                        $unseted = array();
                        while($row = $result->fetch_assoc()){
                            print("<li>
                            <ul class='matchRadio'>");
                            printf("<li style='width:30px;'><span>%s</span></li>",$i);
                            
                            
                            for($it = 1; $it <= $num; $it++){
                                //print("<br><span style='margin-left:50px;'>".$row['correct'].'  '.$it.'  '.$i.'  '.$answ[$i-1].'  '.$rarr[$i-1]."</span><br>");
                                if($answ[$i-1] == $rarr[$i-1] && $it == $row['correct']/* && $answ[$i-1] == $row['correct'] */){
                                    printf("<li class='test_q_true aa'><input type='radio' name='%s%s' value='%s' checked disabled class='radio'><label></label></li>",$qid,$it,$i,$i);
                                    $flag = 1;
                                }
                                else if($answ[$i-1] != $rarr[$i-1] && $it == $answ[$i-1]/* && $answ[$i-1] == $row['correct'] */){
                                    printf("<li class='test_q_false'><input type='radio' name='%s%s' value='%s' disabled class='radio'><label></label></li>",$qid,$it,$i,$i);
                                    $flag = 1;
                                }
                                else if($answ[$i-1] != $rarr[$i-1] && $it == $row['correct']/* && $answ[$i-1] == $row['correct'] */){
                                    printf("<li class='test_q_true bb'><input type='radio' name='%s%s' value='%s' disabled class='radio'><label></label></li>",$qid,$it,$i,$i);
                                    if ($flag != 1) {
                                        $unseted[] = $i;
                                    }
                                    $flag = 0;
                                    
                                }
                                else
                                    printf("<li><input type='radio' name='%s%s' value='%s' disabled class='radio'><label></label></li>",$qid,$it,$i,$i);
                                
                            }
                            /*for($it = 1; $it <= $num; $it++){
                                if($answ[$i-1] == $it)
                                    printf("<li><input type='radio' name='%s%s' value='%s' checked disabled class='radio'><label></label></li>",$qid,$it,$i,$i);
                                else
                                    printf("<li><input type='radio' name='%s%s' value='%s' disabled class='radio'><label></label></li>",$qid,$it,$i,$i);
                                
                            }*/
                            print("</ul>
                            <div style='clear:both;'></div>
                            </li>");
                            $i++;
                        }
                        //}
                    print("</ul></td></tr></table></td>
                    <td>");
                    //var_dump($unseted);
                if (count($unseted) != 0) {
                    print("<p>$didntAnswer</p>");
                    $questions = "";
                    foreach ($unseted as $value) {
                        //printf("%s --- %s", $value, $alphabet[$value-1]);
                        $questions .= $alphabet[$value-1].", ";
                    }
                    $questions = rtrim($questions,", ");
                    print($questions);
                    $unseted = array();
                }
                print("</td>");

                
            print("</tr>");
    }
    
    function forth_type($quest,$answ){
        if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru') {
            $didntAnswer = "Вы не дали ответ на вопросы: ";
        } else {
            $didntAnswer = "Ви не надали відповідь на запитання: ";
        }
        $db = Database::getInstance();
        $mysqli = $db->getConnection();
        $alphabet = Quest::getAlphabet();
        $abc = array("a","b","c","d","e","f","g","h","i","j");

        $qid = $quest;
        $sql_question = "SELECT * FROM os_test_quest WHERE id_q='$qid'";
        $res_question = $mysqli->query($sql_question);
        $row_question = $res_question->fetch_assoc();
        $sql = "SELECT * FROM os_test_answs WHERE id_quest='$qid'";
        $result = $mysqli->query($sql);
        //print("<br>$sql<br>");
        $sqlm = "SELECT * FROM os_test_matches WHERE id_quest='$qid'";
        $resultm = $mysqli->query($sqlm);
        //print("<br>$sqlm<br>");
        $num = $result->num_rows;
        $num_matches = $resultm->num_rows;

        $num_el = 1;
        $used_arr = array();
        $marr = array();
        while ($rowm = $resultm->fetch_assoc()) {
            $marr[] = $rowm['id_ma'];
        }
        $rmarr = array();
        while($row = $result->fetch_assoc()){
            $sql_rm = sprintf("SELECT * FROM os_test_matches WHERE id_quest='$qid' AND num='%s'",$row['correct']);
            $res_rm = $mysqli->query($sql_rm);
            $row_rm = $res_rm->fetch_assoc();
            $rmarr[] = $row_rm['id_ma'];
        }
        /**
         * Строка для тестирования:
         * print("<br>".$answ[$num_el]."--- $num_el --- ".$row['correct']);
         **/
        $result = $mysqli->query($sql);
        printf("<tr>
                <td colspan='3'>%s</td>
            </tr>
            <tr>
            <td>
                <table>
                    <tr>
                        <td>
                <ul style='list-style:none;' class='test_ul_sootv'>",$row_question['name']);
                while($row = $result->fetch_assoc()){
					    printf("<li class='bukva_a$num_el'>%s</li>",$row['answer']);

                    $num_el++;
                }

                print("</ul>
                        </td><td><ul style='list-style:none;' class='test_ul_sootv'>");
                    $sqlm = "SELECT * FROM os_test_matches WHERE id_quest='$qid'";
                    $resultm = $mysqli->query($sqlm);
                    $it_m = 0;
                    while($row_m = $resultm->fetch_assoc()){
                        printf("<li class='bukva_%s'>%s</li>",$abc[$it_m],$row_m['match_text']);
                        $it_m++;
                    }
                    print("</ul></td></tr>
                </table>
                    <ul style='width:250px;margin-top:25px; list-style:none;'>");
                    /*var_dump($answ);
                    print("<br>");
                    print("<br>");
                    var_dump($marr);
                    print("<br>");
                    print("<br>");
                    var_dump($rmarr);
                    print("<br>");*/
                    print("<li>
                        <ul class='matchRadio'>");
                        printf("<li style='width:30px;'></li>");
                        
                        for($it = 0; $it < $num_matches; $it++){
                            printf("<li>%s</li>",$alphabet[$it]);
                        }
                        print("</ul>
                        <div style='clear:both;'></div>
                    </li>");
                        $unseted = array();
                        for($i = 1; $i <= $num; $i++){
                            
                            print("<li>
                            <ul class='matchRadio'>");
                            printf("<li style='width:30px;'><span>%s:</span></li>",$i);
                            $sqlm = "SELECT * FROM os_test_matches WHERE id_quest='$qid'";
                            $resultm = $mysqli->query($sqlm);
                            $it = 1;
                            $flag = 0;
                            
                            while($row_m = $resultm->fetch_assoc()){
                            //for($it = 1; $it <= $num_matches; $it++){
                                
                                //if($answ[$i-1] == $marr[$i-1]){
                                    if($rmarr[$i-1] == $answ[$i-1] && $answ[$i-1] == $row_m['id_ma']/* && $rmarr[$i-1] == $marr[$i-1]*/){
                                        printf("<li class='test_q_true'><input type='radio' name='%s%s' value='%s' checked disabled class='radio'><label style='padding: 0;'></label></li>",$qid,$i,$it,$i);
                                        $flag = 1;
                                    }
                                    else if($rmarr[$i-1] != $answ[$i-1] && $answ[$i-1] == $row_m['id_ma']/* && $rmarr[$i-1] == $marr[$i-1] */){
                                        printf("<li class='test_q_false'><input type='radio' name='%s%s' value='%s' checked disabled class='radio'><label style='padding: 0;'></label></li>",$qid,$i,$it,$i);
                                        $flag = 1;
                                    }
                                    else if($rmarr[$i-1] != $answ[$i-1] && $rmarr[$i-1] == $row_m['id_ma']/* && $rmarr[$i-1] == $marr[$i-1] */){
                                        printf("<li class='test_q_true'><input type='radio' name='%s%s' value='%s' checked disabled class='radio'><label style='padding: 0;'></label></li>",$qid,$i,$it,$i);
                                    }
                                    else {
                                        printf("<li><input type='radio' name='%s%s' value='%s' disabled class='radio'><label style='padding: 0;'></label></li>",$qid,$i,$it,$i);
                                    }
                                    
                                $it++;
                            }
                            print("</ul>
                            <div style='clear:both;'></div>
                            </li>");
                            //print("<br>$flag   -   $i<br>");
                            if ($flag == 0) {
                                $unseted[] = $i;  
                            }
                            //var_dump($unseted);
                            
                                    $flag = 0;
                        }
                    
                    print("</ul>
                </td>");
                $num_el = 0;
                print("<td><ul>");
                    while ($rowm = $resultm->fetch_assoc()) {
                        printf("<li>%s</li>",$rowm['match_text']);
                    }
                print("</ul></td>
                    <td>");
                    //var_dump($unseted);
                if (count($unseted) != 0) {
                    print("<p>$didntAnswer</p>");
                    $questions = "";
                    foreach ($unseted as $value) {
                        //printf("%s --- %s", $value, $alphabet[$value-1]);
                        $questions .= $value.", ";
                    }
                    $questions = rtrim($questions,", ");
                    print($questions);
                    $unseted = array();
                }
                print("</td>");
                
            print("</tr>");
		}
        function fifth_type($quest,$answ){
            if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru') {
                $didntAnswer = "Вы не дали ответ на этот вопрос";
                $correctText = "Правильный ответ";
            } else {
                $didntAnswer = "Ви не надали відповідь на це запитання";
                $correctText = "Правильна відповідь";
            }
            $db = Database::getInstance();
            $mysqli = $db->getConnection();
            $alphabet = Quest::getAlphabet();
            $qid = $quest;
            $sql_question = "SELECT * FROM os_test_quest WHERE id_q='$qid'";
            $res_question = $mysqli->query($sql_question);
            $row_question = $res_question->fetch_assoc();
            $sql_ra = sprintf("SELECT * FROM os_test_short_answ WHERE id_quest='%s'",$row_question['id_q']);
            $res_ra = $mysqli->query($sql_ra);
            $row_ra = $res_ra->fetch_assoc();
            //var_dump($answ);
            printf("<tr>
                <td colspan='3'>%s</td>
            </tr>
            <tr>
            <td>
                ",$row_question['name']);
            $answer = $answ[0];
            if ($row_ra['answer'] == $answer) {
                print("<input type='text' value='$answer' disabled>");
            }
            else{
                print("<input type='text' value='$answer' disabled>");
            }
            printf("<br><span class='right_t_short'>$correctText: %s</span>",$row_ra['answer']);
            print("</td>
                    <td>");
                if ($answer == "") {
                    print("<p>$didntAnswer</p>");
                }
                print("</td></tr>");
        }
    /*** БЛОК с представлением вопросов в разборе на правильные и нет ***/
	}
?>