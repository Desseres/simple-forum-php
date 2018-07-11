<?php 
class POST {

	private $db;

    public function __construct($DB_con)
    {
       $this->db = $DB_con; 
    }


	public function getPostComments($post_id)
	{
		try
		{
			$stmt = $this->db->prepare("SELECT comment_id, comment_content, UNIX_TIMESTAMP(comment_date) as DATE, user_name, user_id FROM comments INNER JOIN users on comment_by=user_id
											WHERE comment_post_id=:post_id ORDER BY DATE ASC");
			$stmt->bindparam(":post_id", $post_id);
			$stmt->execute();
			$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if(count($comments) > 0)
			{
				foreach($comments as $comment)
				{
					$content = $comment['comment_content'];
					$user = $comment['user_name'];
					$comment_id = $comment['comment_id'];
					$dateConverted = new DateTime($comment['comment_date']);
					$date = ' | <span class="comment-date">' . $dateConverted->format('d.m.Y H:i') . '</span>';
					$html.= '<div class="comment">';
					$html.= '<p>' .  $content;
					$html.= $date . ' <a href="#">' . $user .'</a></p>';
					if($_SESSION['user_session'] === $comment['user_id'])
					{
						$html.= '<a href="deleteComment.php?id=' . $comment_id . '" title="Delete comment"><i class="fas fa-trash-alt comment-i"></i></a>';
						$html.= '<a href="editComment.php?id=' . $comment_id . '" title="Edit comment"><i class="fas fa-edit comment-i"></i></a>';

					}
					$html.= '</div>';
				}
			return $html;
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
	

	public function commentsForm($post_id)
	{
			$html.= '<form class="comments-form" method="POST" autocomplete="off">';
			$html.= '<textarea name="comment_content"
					 placeholder="Enter your comment!" maxlength="600" required></textarea>';
			$html.= '<span class="chars">600</span> characters remaining!<br />';
			$html.= '<input type="hidden" name="comment_post_id" value="' . $post_id . '" />';
			$html.= '<button type="submit" name="addComment">Add</button>';
			$html.= '</form>';
		
	return $html;
	}

	
	public function addComment($content, $post_id, $user_id)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO comments (comment_content, comment_by, comment_post_id) VALUES (:content, :user_id, :post_id)");
			$stmt->bindparam(":content", $content);
			$stmt->bindparam(":user_id", $user_id);
			$stmt->bindparam(":post_id", $post_id);

			$stmt->execute();
			if($stmt)
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



	public function thePosts($topic_id, $user_id)
	{
		try
		{
			$stmt = $this->db->prepare("SELECT post_id, post_content, post_date, post_topic, user_id, user_name, user_signature FROM posts INNER JOIN users ON post_by=user_id WHERE post_topic=:topic_id");
			$stmt->bindparam(":topic_id", $topic_id);
			$stmt->execute();
			$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0)
			{
				foreach($posts as $post)
				{
				$post_id = $post['post_id'];
				$html.= '<div class="posts">';
				$html.= '<div class="profile-preview">';
				$html.= '<img src="img/profile.jpg" alt="User profile" height="100" width="100">';
				$html.= '<p>' . $post['user_name'] . '</p>';	
				if($post['user_id'] === $user_id)
				{
					$html.= '<a href="deletePost.php?id=' . $post_id . '" title="Delete post"><i class="fas fa-trash-alt post-ico"></i></a>';

					$html.= '<a href="editPost.php?id=' . $post_id . '" title="Edit post"><i class="fas fa-edit post-ico"></i></a>';
				}
				$html.= '</div>';	
				$html.= '<div class="post-content">';	
				$html.= '<p>' . $post['post_content'] . '</p>';
				$html.= '<p class="post-date">' . $post['post_date'] . '</p>';
				if(!$post['user_signature'] == NULL)
				{
				$html.= '<div class="signature">';
				$html.= '<p>' . $post['user_signature'] . '</p>';	
				$html.= '</div>';	
				}
					$html.= '<div class="comments">';
					$html.= $this->getPostComments($post_id);
					$html.= '<i class="fas fa-comment-dots comment-ico"></i>';
					$html.= $this->commentsForm($post_id);				
					$html.= '</div>';				
				$html.= '</div>';
				$html.= '</div>';
				}
			}
			else
			{
				$html.= '<div class="posts">';
				$html.= '<div class="post-content">';
				$html.= '<p>Posts not found for this topic.</p>';
				$html.= '</div>';
				$html.= '</div>';
			}

		return $html;
		} 
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}




	public function addPost($post_content, $topic_id, $user_id)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO posts (post_content, post_topic, post_by)
										VALUES (:post_content, :post_topic, :post_by)");
			$stmt->bindparam(":post_content", $post_content);
			// TODO: Łykasz content jak pelikan, XSS itp.
			$stmt->bindparam(":post_topic", $topic_id);
			$stmt->bindparam(":post_by", $user_id);

			$stmt->execute();
			if($stmt)
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

	public function editPost($post_id, $user_id)
	{
		try
		{
			$stmt = $this->db->prepare("SELECT * FROM posts WHERE post_id=:post_id");
			$stmt->bindparam(":post_id", $post_id);
			$stmt->execute();
			$post = $stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt && $user_id === $post['post_by'])
			{
				$html.= '<form method="post" autocomplete="off">';
				$html.= '<textarea id="post-content" name="post_content" placeholder="Edit your post!" required>' . $post['post_content'] . '</textarea>';
				$html.= '<button type="submit" name="editedPost">Save</button>';
				$html.= '</form>';
			}
			else
			{
				$html.= '<p>Post not found. Back to <a href="forum.php">forum</a>.</p>';
			}
		return $html;
		} 
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}


	public function savePost($user_id, $post_id, $post_content)
	{
		try
		{
			$stmt = $this->db->prepare("UPDATE posts SET post_content=:post_content WHERE post_id=:post_id AND post_by=:user_id");
			$stmt->bindparam(":user_id", $user_id);
			$stmt->bindparam(":post_id", $post_id);
			$stmt->bindparam(":post_content", $post_content);
			// TODO: Łykasz content jak pelikan, XSS itp.
			$stmt->execute();
			$count = $stmt->rowCount();
			if($count > 0)
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


	public function editComment($comment_id, $user_id)
	{
		try
		{
			$stmt = $this->db->prepare("SELECT * FROM comments WHERE comment_id=:comment_id");
			$stmt->bindparam(":comment_id", $comment_id);
			$stmt->execute();
			$comment = $stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt && $user_id === $comment['comment_by'])
			{
				$html.= '<form method="post" autocomplete="off">';
				$html.= '<textarea id="comment-content" name="comment_content" placeholder="Edit your comment!" required>' . $comment['comment_content'] . '</textarea> <br />';
				$html.= '<button type="submit" name="editedComment">Save</button>';
				$html.= '</form>';
			}
			else
			{
				$html.= '<p>Comment not found. Back to <a href="forum.php">forum</a>.</p>';
			}
		return $html;
		} 
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}


	public function saveComment($user_id, $comment_id, $comment_content)
	{
		try
		{
			$stmt = $this->db->prepare("UPDATE comments SET comment_content=:comment_content WHERE comment_id=:comment_id AND comment_by=:user_id");
			$stmt->bindparam(":user_id", $user_id);
			$stmt->bindparam(":comment_id", $comment_id);
			$stmt->bindparam(":comment_content", $comment_content);
			// TODO: Łykasz content jak pelikan, XSS itp.
			$stmt->execute();
			$count = $stmt->rowCount();
			if($count > 0)
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
