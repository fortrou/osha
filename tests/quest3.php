<?php
    $alphabet = array("А","Б","В","Г","Д","Е","Ё","Ж","З","И");
?>
<div class='createTest'>
    <div id="quest3" class="collapsed">
        <form method='post' action="<?=$_SERVEr['REQUEST_URI']?>" enctype="multipart/form-data" >
            <?php
            require('choose_mark.php');
            ?>
               
                       <p> <span class='testText'>Введите вопрос</span></p>
                        <textarea style="width: 960px; min-height: 200px;" type='text' name='quest' class='quest'><? print( $_SESSION['correct']['quest']); ?></textarea>
                <script type='text/javascript'>
                CKEDITOR.replace('quest');
            </script>
                  
                    <ul>
                    <?
                        for($i = 0; $i < $cnt; $i++){
                            $value = $_SESSION['correct'][$i];
                            printf("<li>
							<span class='testText'>%sй ответ</span>
                                <textarea style='width: 960px; min-height: 200px;' type='text' name='answ%s' class='answer'>%s</textarea>
                                <script type='text/javascript'>
                                    CKEDITOR.replace('answ%s');
                                </script>
                                <br>
                                
                            </li>",$i+1,$i+1,$value,$i+1);
                        }
                    ?> 
					</ul>
                
            <div class="clear"></div>
            <input type='submit' name='add_more' value='Добавить вариант ответа'><br>
            <input type='submit' name='del_last' value='Убрать последний добавленный вариант ответа'>
			<p> <span class='testText'>Правильный ответ</span></p>
                <ul >
                      
                    <?php
                        for($i = 0; $i < $cnt; $i++){
                            printf("<li>
                                %s:<input type='text' name='id%s'>
                            </li>",$i+1,$i+1);
                        }
                    ?>
                </ul>
            <?php
            require('solution_definition.php');
            ?>
        </form>
    </div>
</div>