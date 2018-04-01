<?php
    session_start();
    require_once("../tpl_php/autoload.php");
    $db = Database::getInstance();
    $mysqli = $db->getConnection();
    if (isset($_POST["auth"])) {
        $check_array = explode('-', $_POST["id"]);
        if(count($check_array) != 2 || $check_array[1] != Date('d')) {
            $_SESSION['error'] = "Э не, все фигня";
        } else {
            $sql = sprintf("SELECT * FROM os_users WHERE id='%s'",(int)$_POST["id"]);
            $result = $mysqli->query($sql);
            if ($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                print_r($row);
                foreach( $row as $key => $value ){
                    //if ( $key == 'level' ) continue;
                    $_SESSION['data'][$key] = $value;
                    //header("Location:index.php#tab_1");
                }    
            }
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
                    printf("<br><p style='color:red'>%s</p><br>",$_SESSION['error']);
                    unset($_SESSION['error']);
                }
            ?>
        <div class="create_us">
            <form method="post" action="">
                <input type="text" name="id" />
                <input type="submit" name="auth" />
            </form>
        </div>		
		</div> 
	</div> 
	<?php require_once('../tpl_blocks/footer.php'); ?>    
</body>
</html>