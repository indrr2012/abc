<?php
	$pdo = new PDO("mysql:dbname=jigsaw;host=localhost", "root", "");
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	function add_id($id){
		global $pdo;
		
		$pdo_statement = $pdo->prepare("
			INSERT INTO
				contacts 
			(
				id
			)
			VALUES (
				:id
			)
			ON DUPLICATE KEY 
			UPDATE 
				id = id
		");
		$pdo_statement->bindValue("id", $id);
		$pdo_statement->execute();
	}
	
	if(isset($_POST['ids'])){
		$ids = $_POST['ids'];
		if(get_magic_quotes_gpc()){
			$ids = stripslashes($ids);
		}
		$ids_nonfiltered = $ids;
		$ids = "";
		for($i = 0; $i < strlen($ids_nonfiltered); $i++){
			$char = substr($ids_nonfiltered, $i, 1);
			if(ord($char) <= 128){
				$ids .= $char;
			}
		}
		$ids = json_decode($ids);
		foreach($ids as $id){
			add_id($id);
		}
	}
?>OK