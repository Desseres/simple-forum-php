<?php 
require_once 'db.php';

if($user->is_loggedin() === false)
{
	$user->redirect('index.php?logout=true');
}

include 'header.php';





 ?>


<main class="forum">
<h1 class="title"><a href="forum.php" title="SEO forum">SEO Forum</a></h1>
<?php 
	echo $forum->theCategories();	
?>
</main>

<?php 
include 'footer.php';
 ?>



