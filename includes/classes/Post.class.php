<?php
class Post
{
	// DB stuff
    private $conn;
    private $table = 'posts';
    // Post Properties
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    function __construct($db)
    {
    	$this->conn = $db;
    }

   function read()
    {	
    	$db = $this->conn;
    	$query ="SELECT c.name AS category_name,p.id,p.category_id,p.title,p.body,p.author,p.created_at FROM posts p INNER JOIN categories c on p.category_id =c.id ORDER BY p.created_at DESC";
    	// preparing PDO query
    	$stmt = $db->prepare($query);
    	// executing query
    	$stmt->execute();
    	// getting row count
    	$num = $stmt->rowCount();
    	if($num > 0){
    		$posts = array();
    		$posts['data'] = array();
    		// finally fetching records as associative array
    		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    			// extracting data row by row with collumn names given in db
    			extract($row);
    			$post_item = array(
			        'id' => $id,
			        'title' => $title,
			        'body' => html_entity_decode($body),
			        'author' => $author,
			        'category_id' => $category_id,
			        'category_name' => $category_name,
			        'created_at' => $created_at
		      	);
			    // Push to "data"
			    array_push($posts['data'], $post_item);
    		}
    		return json_encode($posts);
    		
    	}else{
    		return json_encode(
		      array('message' => 'No Posts Found')
		    );
    	}

    }
	
}