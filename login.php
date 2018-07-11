<?php 
require_once 'db.php';

include 'header.php';

if($user->is_loggedin()!="")
{
	echo '<meta http-equiv="refresh" content="0; url=home.php">';
}

if(isset($_POST['login']))
{
	$user_name = trim($_POST['login_user_name']);
	$user_pass = trim($_POST['login_user_pass']);
	$user_email = trim($_POST['reg_user_email']);

	if($user->login($user_name, $user_pass))
	{
	echo '<meta http-equiv="refresh" content="0; url=home.php">';
	}
	else
	{
		$info[] = "Wrong login or password. Try again!";
	}
}

 ?>


<main class="main">

	<div class="register">
	<h3>Login</h3>	
	<form method="post">
	<?php
	if(isset($info))
	{
		foreach($info as $info)
		{
		?>
		<div>
			<p>Informacja: <br />
				<?php echo $info; ?>
			</p>
		</div>

		<?php
		}
	}
	 ?>
		<label>Username:
			<input type="text" name="login_user_name" placeholder="Enter username" required />
		</label>
		<label>Password:
			<input type="password" name="login_user_pass" placeholder="Enter password" required />
		</label>
		<button type="submit" name="login">Log in</button>
	</form>
	</div>
</main>

<?php 
include 'footer.php';
 ?>