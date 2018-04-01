<?php
    
    $_arrWithAnsws = array();
    /*** Будущий массив с ответами ***/
    if(!isset($_SESSION['correct']))
        $_SESSION['correct'] = array();
    /*** Будущий массив с ответами ***/
    if(isset($_POST['quest'])){
            $quest = $_POST['quest'];
            $full_desc = $_POST['descAnswer'];
            $cost = $_POST['rangecost'];
        }
        
    for($var_c = 0;$var_c <= $cnt; $var_c++){
            
            /*** Определяем название элемента массива ПОСТ для варианта ответа ***/
            $tstr1 = sprintf("answ%s",$var_c+1);
            /*** Определяем название элемента массива ПОСТ для варианта ответа ***/
            
            if(isset($_POST[$tstr1])){
                Quest::rewrite_answs($_POST[$tstr1],$_SESSION['correct'],$var_c);
            }
        }

    
?>
<div class='createTest'>
    <div id="quest1" class="collapsed">
        <form method='post' action='<?=$_SERVER['REQUEST_URI']?>' enctype="multipart/form-data" >
            <?php
                require('choose_mark.php');
                //print("<br>$cnt<br>");
                //print("<br>");
                //var_dump($_SESSION['correct']);
                print("<br>");
            ?>
            
                    <input type="hidden" name="typeOfQuest" value="1">
                    <p> <span class='testText'>Введите вопрос</span></p> 
                    <textarea style="width: 960px; min-height: 200px;" type='text' name='quest' class='quest'><? print( $_SESSION['correct']['quest']); ?></textarea>
                    <script type='text/javascript'>
                CKEDITOR.replace('quest');
            </script>
                  
                    
                    <ul>
                        <?
                        for($i = 0; $i < $cnt; $i++){
                            $value = $_SESSION['correct'][$i];
                            printf("<li><span class='testText'>%sй ответ</span>
                                <textarea style='width: 960px; min-height: 200px;'  type='text' name='answ%s' class='answer'>%s</textarea>
                                <script type='text/javascript'>
                                    CKEDITOR.replace('answ%s');
                                </script>
                                <br>
                                
                            </li>",$i+1,$i+1,$value,$i+1);
                        }
                        ?>
                        
                    </ul>
                    
                   
            
            <div class="clear"></div>
            <input type='submit' name='add_more' value='Добавить вариант ответа'> 
            <input type='submit' name='del_last' value='Убрать последний добавленный вариант ответа'>
			<p> <span class='testText'>Выберите правильный ответ</span></p>
				    <table style='width:700px; padding: 10px ;border: 1px solid #1e9cb7;'>
					<tr>
                            <?
                                for($i = 0; $i < $cnt; $i++)
                                printf("<td class='chk_yes_qes'><label>Ответ №%s:<input type='radio' name='id1' value='%s'></label></td>",$i+1,$i+1);
                                ?>
                           </tr> 
                                             
                    </table><br>
            <?php
            require('solution_definition.php');
            ?>
        
        </form>
    </div>
</div>