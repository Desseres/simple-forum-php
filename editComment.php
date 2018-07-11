<?php 
ob_start();
require_once 'db.php';

if($user->is_loggedin() === false)
{
	$user->redirect('index.php?logout=true');
}


$comment_id = $_GET['id'];
$user_id = $_SESSION['user_session'];

if(isset($_POST['editedComment']))
{

	$comment_content = trim($_POST['comment_content']);

	if($comment_content == "" || strlen($comment_content) == 0 || !$comment_content)
	{
		$info[] = "You can't save empty comment! If you want, you can delete the comment instead of this!";
	}
	else
	{

			if($post->saveComment($user_id, $comment_id, $comment_content))
			{
				$user->redirect($_SESSION['topic_url']);
			}
			else
			{
				$info[] = 'Try again!';
			}
	}

}


include 'header.php';

?>


<main class="forum editComment">
	<h1 class="title"><a href="forum.php" title="SEO forum">SEO Forum</a></h1>
	<br />
	<h3>Edit comment</h3>
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
		echo $post->editComment($comment_id, $user_id);
	?>
</main>

<?php 
include 'footer.php';
 ?>



