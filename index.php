<?php 
require_once 'db.php';


if(isset($_GET['logout']))
{
	$user->logout();
	$user->redirect('index.php');
}

if($user->is_loggedin())
{
	$user->redirect('home.php');
}

include 'header.php';






if(isset($_POST['register']))
{
	$user_name = trim($_POST['reg_user_name']);
	$user_pass = trim($_POST['reg_user_pass']);
	$user_pass_check = trim($_POST['reg_user_pass_check']);
	$user_email = trim($_POST['reg_user_email']);
	$user_level = 0;


	if($user_name == "")
	{
		$info[] = "Provide username!";
	}

	else if($user_email == "")
	{
		$info[] = "Provide e-mail!";
	}

	else if(!filter_var($user_email, FILTER_VALIDATE_EMAIL))
	{
		$info[] = "Enter a valid e-mail!";
	}

	else if($user_pass == "")
	{
		$info[] = "Provide password!";
	}

	else if(strlen($user_pass) < 6 || strlen($user_pass_check) < 6)
	{
		$info[] = "Password must have at least 6 characters!";
	}
	else if ($user_pass_check != $user_pass)
	{
		$info[] = "Passwords are different. Try again!";
	}

	else
	{
		try
		{
			$stmt = $DB_con->prepare("SELECT user_name, user_email FROM users
									WHERE user_name=:uname OR user_email=:umail");
			$stmt->execute(array(':uname'=>$user_name, ':umail'=>$user_email));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if($row['user_name'] == $user_name)
			{
				$info[] = "Sorry. This username is already taken!";
			}
			else if($row['user_email'] == $user_email)
			{
				$info[] = "Sorry. This e-mail is already taken!";
			}
			else
			{
				if($user->register($user_name, $user_pass, $user_email, $user_level))
				{
					$info[] = 'Good job! Now you can Log in and add topics, posts!';
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

}


if(isset($_POST['login']))
{
	$user_name = trim($_POST['login_user_name']);
	$user_pass = trim($_POST['login_user_pass']);
	$user_email = trim($_POST['login_user_name']);

	if($user->login($user_name, $user_email, $user_pass))
	{
	echo '<meta http-equiv="refresh" content="0; url=home.php">';
	}
	else
	{
		$error[] = "Wrong login or password. Try again!";
	}
}

 ?>


<main class="main">
	<div class="register">
	<h3>Login</h3>	
	<form method="post">
	<?php
	if(isset($error))
	{
		foreach($error as $error)
		{
		?>
		<div>
			<p>Informacja: <br />
				<?php echo $error; ?>
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
	<div class="register">
	<h3 id="log-in">Register</h3>	
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
			<input type="text" name="reg_user_name" placeholder="Enter username" value="<?php if(isset($info)){echo $user_name;}?>"  required />
		</label>
		<label>E-mail:
			<input type="email" name="reg_user_email" placeholder="Enter e-mail" value="<?php if(isset($info)){echo $user_email;}?>" required />
		</label>
		<label>Password:
			<input type="password" name="reg_user_pass" placeholder="Enter password" required />
		</label>
		<label>Password again:
			<input type="password" name="reg_user_pass_check" placeholder="Enter password again" required />
		</label>
		<button type="submit" name="register">Register</button>
	</form>
	</div>
</main>

<?php 
include 'footer.php';
 ?>