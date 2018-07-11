<?php 

include_once('db.php');

$user_id = $_SESSION['user_session'];
// TODO: to powinna być funkcja np. delete($id) w modelu Posts
if(isset($_GET['id']))
{
	try
	{	
		$stmt = $DB_con->prepare("DELETE FROM posts WHERE post_id=:post_id AND post_by=:user_id");
		$stmt->bindparam(":post_id", $_GET['id']);
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
