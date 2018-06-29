<?php
class Post
{
	// DB stuff
    private $db;
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
    	$this->db = $db;
    }

   function read_post($params)
    {	
        /*
        --How PDO Prepared Statements Work
        1-Prepare an SQL query with empty values as placeholders with either a question mark or a variable name with a colon preceding it for each value
        2-Bind values or variables to the placeholders
        3-Execute query
        */
    	$db = $this->db;
        try {
            if(!empty($params)){
                if(count($params)>1){
                    return throw_err('Please try to pass one parameter at a time!');
                }
            }
            $query = "SELECT c.name AS category_name,p.id,p.category_id,p.title,p.body,p.author,p.created_at FROM posts p INNER JOIN categories c on p.category_id =c.id ";
            
            $cond = " WHERE ";

            if(isset($params['postId'])){
                $post_id =mysql_clean($params['postId']);
                $cond .= " p.id=:pid ";
                $query .= $cond;
            }

            if(isset($params['categoryId'])){
                $cat_id =mysql_clean($params['categoryId']);
                $cond .= " c.id=:cid ";
                $query .= $cond;
            }

            $query .= " ORDER BY p.created_at DESC";
            // pex($query);
            $stmt = $db->prepare($query);
            
            if(isset($post_id)){
                $stmt->bindParam(':pid', $post_id);
            }
            if(isset($cat_id)){
                $stmt->bindParam(':cid', $cat_id);
            }

            $stmt->execute();
            $num = $stmt->rowCount();
            if($num > 0){
                $posts = array();
                $posts['data'] = array();
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
                return throw_err('No Posts Found');
            }

        } catch (Exception $e) {
           return catchException($e->getMessage()); 
        } catch (PDOException $e) {
           return catchException($e->getMessage());
        }
        

    }

    function create_post($params)
    {

        $db = $this->db;
        $data = array();
        try {
            
            if(empty($params)){
                throw_err('Posted an empty data array');
            }

            foreach ($params as $key => $value) {
                $data[$key] = mysql_clean($value);
            }

            if(!$data['category_id']){
                throw_err('Category id is empty');
            }elseif(!$data['title']){
                throw_err('Title is empty');
            }elseif(!$data['body']){
                throw_err('Body is empty');
            }elseif(!$data['author']){
                throw_err('Author is empty');
            }

            $query="INSERT INTO posts (category_id,title,body,author,created_at,updated_at) VALUES (:catid,:title,:body,:author,:ca)";
            
            $stmt = $db->prepare($query);

            $stmt->bindParam(':catid',$data['category_id']);
            $stmt->bindParam(':title',$data['title']);
            $stmt->bindParam(':body',$data['body']);
            $stmt->bindParam(':author',$data['author']);
            $stmt->bindParam(':ca',now());
            $succes_check = $stmt->execute();
            
            if($succes_check)
                return throw_msg('Post has been added');
            else
                throw_err('Error occured in post creation '.$stmt->error); 

        } catch (Exception $e) {
        
            return catchException($e->getMessage());
        
        } catch (PDOException $e) {
            
            return catchException($e->getMessage());
        
        }

    }

    function update_post($params)
    {

        $db = $this->db;
        $data = array();
        try {
            
            if(empty($params)){
                throw_err('Posted an empty data array');
            }

            foreach ($params as $key => $value) {
                $data[$key] = mysql_clean($value);
            }

            if(!$data['post_id']){
                throw_err('Post id is empty');
            }elseif(!$data['category_id']){
                throw_err('Category id is empty');
            }elseif(!$data['title']){
                throw_err('Title is empty');
            }elseif(!$data['body']){
                throw_err('Body is empty');
            }elseif(!$data['author']){
                throw_err('Author is empty');
            }

            $query="UPDATE posts SET category_id=:catid,title=:title,body=:body,author=:author,updated_at=:ua WHERE id=:pid";
            
            $stmt = $db->prepare($query);

            $stmt->bindParam(':pid',$data['post_id']);
            $stmt->bindParam(':catid',$data['category_id']);
            $stmt->bindParam(':title',$data['title']);
            $stmt->bindParam(':body',$data['body']);
            $stmt->bindParam(':author',$data['author']);
            $stmt->bindParam(':ua',now());
            $succes_check = $stmt->execute();
            
            if($succes_check)
                return throw_msg('Post has been Updated');
            else
                throw_err('Error occured in post updation '.$stmt->error); 

        } catch (Exception $e) {
        
            return catchException($e->getMessage());
        
        } catch (PDOException $e) {
            
            return catchException($e->getMessage());
        
        }

    }
    function delete_post($params)
    {

        $db = $this->db;
        try {
            
            if(empty($params)){
                throw_err('Posted an empty data array');
            }
            $post_id = $params['post_id'];
            
            if(!$post_id){
                throw_err('Post id is empty');
            }
            $post_id = mysql_clean($post_id);
            $query="DELETE FROM posts WHERE id=:pid";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':pid',$post_id);
            $succes_check = $stmt->execute();
            
            if($succes_check)
                return throw_msg('Post has been Deleted');
            else
                throw_err('Error occured in post deletion '.$stmt->error); 

        } catch (Exception $e) {
        
            return catchException($e->getMessage());
        
        } catch (PDOException $e) {
            
            return catchException($e->getMessage());
        
        }

    }
	
}