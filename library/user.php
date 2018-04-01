<?php
class User{
	public $id;
	public $level;
	public $login;
	private $password;
	private $type;
	const path_ava = $_SERVER['DOCUMENT_ROOT']."upload/avatars/";
	/*public $full_data = array(
	"name" => "",
	"surname" => "",
	"patronymic" => "",
	"level" => "",
	"avatar" => "",
	"form" => "",
	"birth" => "",
	"school" => "",
	"city" => "",
	"email" => ""
	);*/
	function __construct($link, $log = "",$passw = ""){
		try{
			if($log == "" || $password == "") throw new Exception("Отсутствует логин и/или пароль");
			$sql = "SELECT * FROM os_users WHERE login='$log'";
			$res = mysqli_query($link,$sql);
			$row = mysqli_fetch_assoc($res);
			if($row['password'] == trim(strip_tags($passw))){
				$this->full_data = array(
					"name" => $row["name"],
					"surname" => $row["surname"],
					"patronymic" => $row["patronymic"],
					"level" => $row["level"],
					"avatar" => $row["avatar"],
					"form" => $row["class"],
					"birth" => $row["birth"],
					"school" => $row["school"],
					"city" => $row["city"],
					"email" => $row["emai"]
				);
				switch $this->level{
					case 1:
						$this->type = "Ученик";
					break;
					case 2:
						$this->type = "Учитель";
					break;
					case 3:
						$this->type = "Менеджер";
					break;
					case 4:
						$this->type = "Суперадмин";
					break;
				}
			}
			else return false;
		}
		catch{
			print("<div>Произошла ошибка: ".$e->getMessage().". На строке: ".
			$e->getLine().". В документе: ".$e->getFile()."</div>");
		}
	}
	
	function show_info(){
		printf("<div>
			<span>Здравствуйте, %s %s %s</span><br>
			<span>Вы: %s</span>
		</div>",$this->full_data['surname'],$this->full_data['name'],$this->full_data['patronymic'],$this->type);
	}
	
	/*function create_teacher(){
		
	}
	function create_manager(){
		
	}
	function create_super(){
		
	}
	
	function create_new(){
		if($this->level = 4){
			
		}
	}*/
	
};
?>