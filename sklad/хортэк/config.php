<?php
/*Connect to database */
try {
	$pdo = new PDO('mysql:dbname=sklad; charset=UTF8; host=localhost', 'root', 'root');
} catch (PDOException $e) {
	die($e->getMessage());
}

