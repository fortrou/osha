<?php
    $alphabet = array("А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й","К");
?>
<div class='createTest'>
    <div id="quest4" class="collapsed" >
        <form method='post' action="<?=$_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data" >
            <?php
            require('choose_mark.php');
            ?>
            <br>
                <!--<input type="hidden" name="typeOfQuest" value="4">-->
                <div style='clear:both;'></div>
               <p> <span class='testText'>Введите вопрос</span></p>
                <textarea style='width:960px; min-height: 200px;' type='text' name='quest' class='quest'><? print( $_SESSION['correct']['quest']); ?></textarea>
                <script type='text/javascript'>
                CKEDITOR.replace('quest');
            </script>
                <ul style='float:left;list-style:none; width:450px;'>
                    <li>
                        <h2>Варианты ответов</h2>
                    </li>
                    <?
                        for($i = 0; $i < $cnt; $i++){
                            $value = $_SESSION['correct'][$i];
                            printf("<li><span class='testText'>ответ №%s </span>
                                <textarea style='width:470px; min-height: 200px;' type='text' name='answ%s' class='answer'>%s</textarea>
                                <script type='text/javascript'>
                                    CKEDITOR.replace('answ%s');
                                </script>
                                <br>
                            </li>",$i+1,$i+1,$value,$i+1);
                        }
                    ?>
                </ul>

                <ul style='float:right;margin-right:20px;list-style:none;width:450px;'>
                    <li>
                        <h2>Варианты соответствий</h2>
                    </li>
                    <?php
                        for($i = 0; $i < $matchcnt; $i++){
                            $value = $_SESSION['match'][$i];
                            printf("<li><span class='testText'>соответствие №%s </span>
                                <textarea style='width:470px; min-height: 200px;' type='text' name='match%s' class='match'>%s</textarea>
                                <script type='text/javascript'>
                                    CKEDITOR.replace('match%s');
                                </script><br>
                                
                            </li>",$i+1,$i+1,$value,$i+1);
                        }
                    ?>
                </ul>
                <div style='clear:both;'></div>
                <ul style='width:450px;margin-top:25px; list-style:none;'>
                    <?php
                    print("<li>
                        <ul class='matchRadio'>");
                        printf("<li style='width:80px;'></li>");
                        
                        for($it = 0; $it < $matchcnt; $it++){
                            printf("<li>%s</li>",$alphabet[$it]);
                        }
                        print("</ul>
                        <div style='clear:both;'></div>
                    </li>");
                        for($i = 1; $i <= $cnt; $i++){
                            print("<li>
                            <ul class='matchRadio'>");
                            printf("<li style='width:80px;'><span>Ответ №%s</span></li>",$i);
                            for($it = 1; $it <= $matchcnt; $it++){
                                printf("<li><input type='radio' name='id%s' value='%s'></li>",$i,$it);
                            }
                            print("</ul>
                            <div style='clear:both;'></div>
                            </li>");
                        }
                    ?>
                </ul>
            <div class="clear"></div>
            <table style="text-align:center;">
                <tr>
                    <td>
                        <input type='submit' name='add_more' value='Добавить вариант ответа'><br>
                    </td>
                    <td>
                        <input type='submit' name='add_match' value='Добавить новый вариант соответствия'>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='submit' name='del_last' value='Убрать последний добавленный вариант ответа'>
                    </td>
                    <td>
                        <input type='submit' name='del_match' value='Убрать последний добавленный вариант соответствия'>
                    </td>
                </tr>
            </table>
            <?php
            require('solution_definition.php');
            ?>
        </form>
    </div>
</div>