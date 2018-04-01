<div class='createTest'>
    <div id="quest2" class="collapsed" style="width:800px">
        <form method='post' action="<?=$_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data" >
            <?php
            require_once('choose_mark.php');
            ?>
            <br>
                    <input type="hidden" name="typeOfQuest" value="2">
                    
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
                                </script><br>
                                
                            </li>",$i+1,$i+1,$value,$i+1);
                        }
                        ?> 
                    </ul>
                 
                
            
                <div class="clear" style="clear:both;"></div>
                <input type='submit' name='add_more' value='Добавить вариант ответа'> 
                <input type='submit' name='del_last' value='Убрать последний добавленный вариант ответа'>
				<p> <span class='testText'>Выберите правильный ответ</span></p>
				    <table style='width:700px; padding: 10px ;border: 1px solid #1e9cb7;'>
					<tr> 
                            <?php
                                for($i = 0; $i < $cnt; $i++)
                                printf("<td class='chk_yes_qes'><label>                
                                        Ответ №%s:<input type='checkbox' name='id%s' value='%s'></label></td>",$i+1,$i+1,$i+1);
                            ?> 
                           </tr> 
                                             
                    </table><br>
                <?php
                require_once('solution_definition.php');
                ?>
            
        </form>
    </div>
</div>