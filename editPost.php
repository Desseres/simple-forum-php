<?php 
ob_start();
require_once 'db.php';
include_once 'class.post.php';

if($user->is_loggedin() === false)
{
	$user->redirect('index.php?logout=true');
}


$post_id = $_GET['id'];
$user_id = $_SESSION['user_session'];

if(isset($_POST['editedPost']))
{

	$post_content = trim($_POST['post_content']);

	if($post_content == "" || strlen($post_content) == 0 || !$post_content)
	{
		$info[] = "You can't save empty post! If you want, you can delete the post instead of this!";
	}
	else
	{

			if($post->savePost($user_id, $post_id, $post_content))
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


<main class="forum">
	<h1 class="title"><a href="forum.php" title="SEO forum">SEO Forum</a></h1>
	<br />
	<h3>Edit your post</h3>
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
		echo $post->editPost($post_id, $user_id);
	?>
</main>

<?php 
include 'footer.php';
 ?>



