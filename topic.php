<?php 
require_once 'db.php';


if($user->is_loggedin() === false)
{
	$user->redirect('index.php?logout=true');
}


$topic_id = $_GET['id'];
$user_id = $_SESSION['user_session'];
$url = 'http://'. $_SERVER[HTTP_HOST]. $_SERVER[REQUEST_URI];
$_SESSION['topic_url'] = $url;

if(isset($_POST['addPost']))
{
	$post_content = trim($_POST['post_content']);

	if($post_content == "" || strlen($post_content) == 0 || !$post_content)
	{
		$info[] = "Enter your answer!";
	}

	else
	{

			if($post->addPost($post_content, $topic_id, $user_id))
			{
				$user->redirect($_SERVER['REQUEST_URI']);
			}
			else
			{
				$info[] = 'Try again!';
			}
	}

}

if(isset($_POST['addComment']))
{
	$content = trim($_POST['comment_content']);
	$post_id = $_POST['comment_post_id'];

	if($content == "" || strlen($content) == 0 || !$content)
	{
		$commentInfo[] = "Enter your comment!";
	}

	else if(strlen($content) > 600)
	{
		$commentInfo[] = "Your comment is too long. You can enter max 600 characters!";
	}
	else
	{
		if($post->addComment($content, $post_id, $user_id))
		{
			$user->redirect($_SERVER['REQUEST_URI']);
		}

		else
		{
			$commentInfo[] = "Try again!";
		}
	}
}


include 'header.php';

?>


<main class="forum">
	<h1 class="title"><a href="forum.php" title="SEO forum">SEO Forum</a></h1>
	<?php 
	if(isset($commentInfo))
	{	
		echo $commentInfo;
	}
		echo $topic->theTopic($topic_id);

		echo $post->thePosts($topic_id, $user_id);

	?>
	<br />
	<h3>Add new answer</h3>
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
	 ?>	
	<form method="post" autocomplete="off">
			<textarea id="post-content" name="post_content" placeholder="Write your answer!" required></textarea>
		<button type="submit" name="addPost">Add</button>
	</form>
</main>

<?php 
include 'footer.php';
 ?>



