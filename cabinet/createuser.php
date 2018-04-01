<?php
    session_start();
    require_once("../tpl_php/autoload.php");
    $db = Database::getInstance();
    $mysqli = $db->getConnection();

    if (!isset($_GET['type'])) {
        header("Location:".$_SERVER['HTTP_REFERER']);
    }
    
    //var_dump($_POST);
    if(isset($_POST['create'])){
        if($_POST['password']==$_POST['password1']){
            User::createUser($_POST, $_GET['type']);

            if($_GET['type'] == 2){
                $sql_id = "SELECT MAX(id) FROM os_users WHERE level=2";
                $res_id = $mysqli->query($sql_id);
                $row_id = $res_id->fetch_assoc();
                foreach ($_POST['classes'] as $value) {
                    $sql = sprintf("INSERT INTO os_teacher_class(id_teacher,id_c) VALUES(%s,%s)",$row_id['MAX(id)'],$value);
                    $res = $mysqli->query($sql);
                }
                foreach ($_POST['subjects'] as $value) {
                    $sql = sprintf("INSERT INTO os_teacher_subj(id_teacher,id_s) VALUES(%s,%s)",$row_id['MAX(id)'],$value);
                    $res = $mysqli->query($sql);
                }
            }
            header("Location:createuser.php");
        }
        else{
            $_SESSION['error'] = "Пароли не совпадают, попробуйте снова";
        }
    }
?>
<!DOCTYPE html>
<html>

  <head>
	<title>Главная - ВнеШколы - образовательный портал</title>
    <?php require_once('../tpl_blocks/head.php'); ?>
    <script src="https://code.jquery.com/jquery-2.2.1.min.js"   integrity="sha256-gvQgAFzTH6trSrAWoH1iPo9Xc96QxSZ3feW6kem+O00="   crossorigin="anonymous"></script>
    <script src="../tpl_js/creation.js"></script>
  </head>

    <body>
        <?php require_once('../tpl_blocks/header.php'); ?>
	
	<div class="content">
		<div class="block0">
            <?php 
                if (isset($_SESSION['error']) && $_SESSION['error']!="") {
                    printf("<br>%s<br>",$_SESSION['error']);
                    unset($_SESSION['error']);
                }
            ?>
        <div class="create_us">
            
                
                <?php
                    switch($_GET['type']){
                        case 1:
                        print("<h3>Создается ученик</h3>");
                            require_once('req_mods/create_pup.php');
                        break;
                        case 2:
                        print("<h3>Создается учитель</h3>");
                            require_once('req_mods/create_high.php');
                        break;
                        case 3:
                        print("<h3>Создается менеджер</h3>");
                            require_once('req_mods/create_high.php');
                        break;
                        case 4:
                        print("<h3>Создается Админ</h3>");
                            require_once('req_mods/create_high.php');
                        break;
                    }
                ?>
            </form>
        </div>		
		</div> 
	</div> 
	<?php require_once('../tpl_blocks/footer.php'); ?>    
</body>
</html>