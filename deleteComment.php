<?php 

include_once('db.php');

$user_id = $_SESSION['user_session'];

if(isset($_GET['id']))
{
	try
	{	
		$stmt = $DB_con->prepare("DELETE FROM comments WHERE comment_id=:comment_id AND comment_by=:user_id");
		$stmt->bindparam(":comment_id", $_GET['id']);
		$stmt->bindparam(":user_id", $user_id);
		$stmt->execute();
		$user->redirect($_SERVER["HTTP_REFERER"]);
	} 
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}

}

?>