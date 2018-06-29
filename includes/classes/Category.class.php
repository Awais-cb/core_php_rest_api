<?php
class Category
{
	// DB stuff
    private $db;
    private $table = 'categories';
    // Post Properties
    public $id;
    public $name;
    public $created_at;

    function __construct($db)
    {
    	$this->db = $db;
    }

   function get_category($params)
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
            $query = "SELECT id,name,created_at FROM ".$this->table."";
            
            $cond = " WHERE ";

            if(isset($params['category_id'])){
                $cat_id =mysql_clean($params['category_id']);
                $cond .= " id=:cid ";
                $query .= $cond;
            }

            if(isset($params['category_name'])){
                $cat_name =mysql_clean($params['category_name']);
                $cond .= " name=:cname ";
                $query .= $cond;
            }

            $query .= " ORDER BY created_at DESC";
            // pex($query);
            $stmt = $db->prepare($query);
            
            if(isset($cat_id)){
                $stmt->bindParam(':cid', $cat_id);
            }
            if(isset($cat_name)){
                $stmt->bindParam(':cname', $cat_name);
            }

            $stmt->execute();
            $num = $stmt->rowCount();
            if($num > 0){
                $cats = array();
                $cats['data'] = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    // extracting data row by row with collumn names given in db
                    extract($row);
                    $cat_item = array(
                        'id' => $id,
                        'name' => $name,
                        'created_at' => $created_at
                    );
                    // Push to "data"
                    array_push($cats['data'], $cat_item);
                }
                return json_encode($cats);
                
            }else{
                return throw_err('No Categories Found');
            }

        } catch (Exception $e) {
           return catchException($e->getMessage()); 
        } catch (PDOException $e) {
           return catchException($e->getMessage());
        }
        

    }

    function create_category($params)
    {

        $db = $this->db;
        try {
            
            if(empty($params)){
                throw_err('Posted an empty data array');
            }
            $cat_name = $params['category_name'];    

            if(!$cat_name){
                throw_err('Category name is empty');
            }

            $query="INSERT INTO categories (name,created_at) VALUES (:c_name,:ca)";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':c_name',$cat_name);
            $stmt->bindParam(':ca',now());
            $succes_check = $stmt->execute();
            
            if($succes_check)
                return throw_msg('Category has been added');
            else
                throw_err('Error occured in category creation '.$stmt->error); 

        } catch (Exception $e) {
        
            return catchException($e->getMessage());
        
        } catch (PDOException $e) {
            
            return catchException($e->getMessage());
        
        }

    }

    function update_category($params)
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
            }elseif(!$data['category_name']){
                throw_err('Category name is empty');
            }

            $query="UPDATE categories SET name=:c_name,updated_at=:ua WHERE id=:cid";
            
            $stmt = $db->prepare($query);

            $stmt->bindParam(':cid',$data['category_id']);
            $stmt->bindParam(':c_name',$data['category_name']);
            $stmt->bindParam(':ua',now());
            $succes_check = $stmt->execute();
            
            if($succes_check)
                return throw_msg('Category has been Updated');
            else
                throw_err('Error occured in category updation '.$stmt->error); 

        } catch (Exception $e) {
        
            return catchException($e->getMessage());
        
        } catch (PDOException $e) {
            
            return catchException($e->getMessage());
        
        }

    }
    function delete_category($params)
    {

        $db = $this->db;
        try {
            
            if(empty($params)){
                throw_err('Posted an empty data array');
            }
            $cat_id = $params['category_id'];
            
            if(!$cat_id){
                throw_err('Category id is empty');
            }
            $cat_id = mysql_clean($cat_id);

            // Improvement needed here convert it into one query(foriegn key contraint issue)
            $pquery="DELETE FROM posts WHERE category_id=:cid";
            $stmt = $db->prepare($pquery);
            $stmt->bindParam(':cid',$cat_id);
            $succes_check = $stmt->execute();
            
            $cquery="DELETE FROM categories WHERE id=:cid";
            $stmt = $db->prepare($cquery);
            $stmt->bindParam(':cid',$cat_id);
            $succes_check = $stmt->execute();
            // Improvement needed here
            
            if($succes_check)
                return throw_msg('Category has been Deleted');
            else
                throw_err('Error occured in category deletion '.$stmt->error); 

        } catch (Exception $e) {
        
            return catchException($e->getMessage());
        
        } catch (PDOException $e) {
            
            return catchException($e->getMessage());
        
        }

    }
	
}