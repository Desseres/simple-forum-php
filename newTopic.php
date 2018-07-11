<?php 
ob_start();
require_once 'db.php';

if($user->is_loggedin() === false)
{
	$user->redirect('index.php?logout=true');
}


$cat_id = $_GET['cat_id'];
$topic_by = $_SESSION['user_session'];
if(isset($_POST['addTopic']))
{
	$topic_subject = trim($_POST['topic_subject']);
	$topic_description = trim($_POST['topic_description']);


	if($topic_description == "" || strlen($topic_description) == 0 || !$topic_description)
	{
		$info[] = "You can't add a new topic with empty content!";
	}

	else if($topic_subject == "" || strlen($topic_subject) == 0 || !$topic_subject)
	{
		$info[] = "You can't add a new topic without title!";
	}
	else
	{

			if($forum->addTopic($topic_subject, $topic_description, $cat_id, $topic_by))
			{
				$user->redirect('forum.php');
			}
			else
			{
				$info[] = 'Try again!';
			}
	}

}


include 'header.php';

?>


<main class="forum newTopic">
	<h1 class="title"><a href="forum.php" title="SEO forum">SEO Forum</a></h1>
	<br />
	<h3>New Topic</h3>
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
		echo $forum->getTopicForm($cat_id);
	?>
</main>

<?php 
include 'footer.php';
 ?>



