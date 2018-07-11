<?php 
class FORUM {

	private $db;

    public function __construct($DB_con)
    {
       $this->db = $DB_con; 
    }



	public function theCategories()
	{
		try
		{
			$stmt = $this->db->prepare("SELECT topic_id, topic_subject, topic_date, user_name, cat_name, cat_id FROM topics INNER JOIN categories ON topic_cat=cat_id
								INNER JOIN users on topic_by=user_id");
			$stmt->execute();
			$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);


			$categoriesToTopics = array();

			foreach($topics as $topic)
			{
				$categoryName = $topic['cat_name'];
				
				if(!array_key_exists($categoryName, $categoriesToTopics))
				{
					$categoriesToTopics[$categoryName] = [];					
				} 
				
				array_push($categoriesToTopics[$categoryName], $topic);
			}

			$html = '';
			foreach($categoriesToTopics as $key => $category)
			{
				$html.= '<div class="category">';
					$html.= '<h3 class="category-name">';
					$html.= $key;
					$html.= '</h3>';
					echo '<pre>';

					$html.= '<a href="newTopic.php?cat_id='. $category[0]['cat_id'] . '">';
					$html.= 'New topic <i class="fas fa-plus-square"></i></a>';
				
					foreach($category as $topic)
					{
					$html.= '<div class="topic">';
						$html.= '<div class="topic-title">';
						$html.= '<a href="topic.php?id=' . $topic['topic_id'] . '">' . $topic['topic_subject'] . '</a>';
						$html.= '</div>';
						$html.= '<div class="topic-date">';
						$html.= $topic['user_name'] . ' ';
						$html.= $topic['topic_date'];
						$html.= '</div>';
					$html.= '</div>';

					}
				
				$html.= '</div>';
			}

		return $html;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}


	public function getTopicForm($cat_id)
	{
			$html.= '<form class="newTopic-form" method="POST" autocomplete="off">';
			$html.= '<input type="text" name="topic_subject" placeholder="Enter topic title..." required><br>';
			$html.= '<textarea name="topic_description"
					 placeholder="Enter your topic content!" required></textarea><br>';
			$html.= '<button type="submit" name="addTopic">Add</button>';
			$html.= '</form>';
	return $html;
	}


	public function addTopic($topic_subject, $topic_description, $cat_id, $topic_by)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO topics(topic_subject, topic_description, topic_cat, topic_by) VALUES(:topic_subject, :topic_description, :topic_cat, :topic_by)");
			$stmt->execute(array(':topic_subject'=>$topic_subject, ':topic_description'=>$topic_description, ':topic_cat'=>$cat_id, ':topic_by'=>$topic_by));
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