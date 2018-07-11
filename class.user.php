<?php 
/**
 * User CLASS
 */
class USER
{
	private $db;

    public function __construct($DB_con)
    {
       $this->db = $DB_con; 
    }



	public function register($user_name, $user_pass, $user_email, $user_level)
	{
		try 
		{
			$hashed_password = password_hash($user_pass, PASSWORD_DEFAULT);	

			$stmt = $this->db->prepare("INSERT INTO users
				(user_name, user_pass, user_email, user_level)
				VALUES (:user_name, :user_pass, :user_email, :user_level)");

			$stmt->bindparam(":user_name", $user_name);
			$stmt->bindparam(":user_pass", $hashed_password);
			$stmt->bindparam(":user_email", $user_email);
			$stmt->bindparam(":user_level", $user_level);

			$stmt->execute();

			return $stmt;
		}
		catch (PDOException $e) 
		{
			echo $e->getMessage();
		}
	}


	public function login($user_name, $user_email, $user_pass)
	{
		try
		{
			$stmt = $this->db->prepare("SELECT * FROM users
										WHERE user_name=:uname OR user_email=:umail");
			$stmt->execute(array('uname'=>$user_name, 'umail'=>$user_email));
			$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0)
			{
				if(password_verify($user_pass, $userRow['user_pass']))
				{
					$_SESSION['user_session'] = $userRow['user_id'];
					return true;
				}
			}
			else
			{
				return false;
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
		else{
			return false;
		}
	}

	public function redirect($url)
   {
       header("Location: $url");
       exit();
   }
	 

	public function logout()
	{
		$_SESSION = array();
	}


	public function getProfile($session_user_id)
	{
		try
		{
			$stmt = $this->db->prepare("SELECT * FROM users
										WHERE user_id=:uid");
			$stmt->bindparam(":uid", $session_user_id);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0)
			{
			$user_id = $user['user_id'];
			$user_signature = $user['user_signature'];
			$html.= '<form class="profile-form" method="POST" autocomplete="off">';
			$html.= '<textarea name="user_signature"
					 placeholder="Enter text of your signature!" maxlength="300" required>'
					  . $user_signature . '</textarea>';
			$html.= '<p>300 characters max!</p>';
			$html.= '<input type="hidden" name="user_id" value="' . $user_id . '" />';
			$html.= '<button type="submit" name="setProfile">Add</button>';
			$html.= '</form>';				
			}
			else
			{
				$html.= '<p>Profile not found. Try again!</p>';
			}
		return $html;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function setProfile($user_id, $user_signature)
	{
		try
		{
			$stmt = $this->db->prepare("UPDATE users SET user_signature=:user_signature
											WHERE user_id=:user_id");
			$stmt->bindparam(":user_signature", $user_signature);
			$stmt->bindparam(":user_id", $user_id);
			$stmt->execute();
			if($stmt->rowCount() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}
 ?>