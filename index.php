<?php

$reason = false;

$pdo = new PDO("mysql:dbname=jigsaw;host=localhost", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_GET['getNext'])){
	$reason = "getNext";
	$pdo_statement = $pdo->query("SELECT id FROM contacts WHERE saved <> 1 LIMIT 1, 200");
	$pdo_statement->execute();
	die(json_encode($pdo_statement->fetchAll(PDO::FETCH_COLUMN)));
}

if(isset($_GET['status'])){
	$reason = "status";
	$pdo_statement = $pdo->query("SELECT count(id) as ids FROM contacts");
	$pdo_statement->execute();
	$records = $pdo_statement->fetch(PDO::FETCH_ASSOC);
	
	$pdo_statement = $pdo->query("SELECT count(id) as ids FROM contacts WHERE saved <> 1");
	$pdo_statement->execute();
	$pending_records = $pdo_statement->fetch(PDO::FETCH_ASSOC);
?>	Total Records: <?php echo $records['ids']; ?>. Pending Records: <?php echo $pending_records["ids"]; ?>. <?php
	exit;
}

if($reason == false){
	$pdo_statement = $pdo->query("SELECT id FROM contacts WHERE saved <> 1 LIMIT 1, 1");
	$pdo_statement->execute();
	$record = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
	if(empty($record) || !isset($record[0]['id'])){
		echo "No records to scrap.";
	} else {
		header("Location: https://www.jigsaw.com/BC.xhtml?contactId=".$record[0]['id']);
	}
}