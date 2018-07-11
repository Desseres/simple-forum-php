<?php 
class TOPIC {

	private $db;

    public function __construct($DB_con)
    {
       $this->db = $DB_con; 
    }

	public function theTopic($topic_id)
	{
		try
		{
			$stmt = $this->db->prepare("SELECT * FROM topics WHERE topic_id=:topic_id");
			$stmt->bindparam(":topic_id", $topic_id);
			$stmt->execute();
			$topic = $stmt->fetch(PDO::FETCH_ASSOC);
			$html = '';
			if($stmt->rowCount() > 0)
			{
				$html.= '<div class="topic-page">';
				$html.= '<h1>' . $topic['topic_subject'] . '</h1>';
				$html.= '<p>' . $topic['topic_description'] . '</p>';
				$html.= '<p class="topic-date">' . $topic['topic_date'] . '</p>';
				$html.= '</div>';
			}
			else
			{
				$html.= '<div class="topic">';
				$html.= '<p>Topic not found.</p>';
				$html.= '</div>';
			}

		return $html;
		} 
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}




}
?>