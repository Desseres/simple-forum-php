<?php 
require_once 'db.php';

if($user->is_loggedin() == false)
{
	$user->redirect('index.php?logout=true');
}

$user_id = $_SESSION['user_session'];

include 'header.php';
?>


<main class="main home">
<p>Welcome in our forum!</p>
<a href="profile.php?id=<?php echo $user_id ?>">MY PROFILE</a>
<a href="forum.php">FORUM</a>
<a href="index.php?logout=true" class="logout">LOG OUT</a>
</main>

<?php 
include 'footer.php';
 ?>