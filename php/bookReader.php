<?php
	session_start();
	$url ="";
	if(isset($_GET['type']) && $_GET['type'] != ''){$type = $_GET['type']; $url = "type=".$type;}
	if(isset($_GET['book_id']) && $_GET['book_id'] != ''){$book_id = $_GET['book_id']; $url .= "&book_id=".$book_id;}
	if(isset($_GET['page']) && $_GET['page'] != ''){$page = preg_split("/-/",$_GET['page']); $url .= "&pagenum=".$page[0];}
	if(isset($_GET['text']) && $_GET['text'] != ''){$text = $_GET['text']; $url .= "&searchText=".$text;}
	//~ *******************Top View Book Hits*************************
	include("connect.php");
	$db = @new mysqli('localhost', "$user", "$password", "$database");
	mysqli_set_charset ( $db , "utf8" );
	
	$query = "select * from topviewed where language = '$type' and bookid = $book_id";
	$result = $db->query($query); 
	$num_rows = $result ? $result->num_rows : 0;
	$top = 'top';
	if($num_rows > 0 && !(isset($_SESSION[$top.$type.$book_id]) && $_SESSION[$top.$type.$book_id] != ""))
	{
		$row = $result->fetch_assoc();
		$query = "update topviewed set hits = ".($row["hits"]+1)." , viewed_date =  ".date("Y-m-d")." where bookid = ".$row["bookid"]." and language = '".$type."'";
		$db->query($query);
	}
	elseif($num_rows == 0)
	{
		$query = "insert into topviewed values('',$book_id,'$type',1,'".date("Y-m-d")."');";
		$db->query($query);
	}
	$_SESSION[$top.$type.$book_id] = $type.$book_id;
	//~ *******************Top View Book Hits*************************
	//~ print_r($_SESSION['sd']);
	header("Location: bookreader/templates/book.php?".$url);
?>
