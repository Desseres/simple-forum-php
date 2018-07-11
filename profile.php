<?php 
ob_start();
require_once 'db.php';

$user_id = $_GET['id'];
$session_user_id = $_SESSION['user_session'];

if($user->is_loggedin() === false || $user_id !== $session_user_id)
{
	$user->redirect('index.php?logout=true');
}



if(isset($_POST['setProfile']))
{

	$user_signature = strip_tags( trim($_POST['user_signature']) );


			if($user->setProfile($user_id, $user_signature))
			{
				$user->redirect('home.php');
			}
			else
			{
				$info[] = 'Try again!';
			}
}


include 'header.php';

?>
	

<main class="forum profile">
	<h3>Your signature</h3>
	<h4>You can't enter HTML code like anchors!</h4>
		<?php
	if(isset($info))
	{
		foreach($info as $info)
		{
		?>
		<div>
			<p>
				<?php echo $info; ?>
			</p>
		</div>
		<?php
		}
	}
		echo $user->getProfile($session_user_id);
	?>
</main>

<?php 
include 'footer.php';
 ?>



