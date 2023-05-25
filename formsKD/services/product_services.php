<?php
    include_once("base/connection.php");

    class ProductServices extends Connection{
        private $table = "products";
        
        public function insertProduct($name, $description, $price){
            if($name == null || $price == null){
                return false;
            }
            
            $products = $this->selectAllProducts();
            
            if(sizeof($products) > 0){
               foreach($products as $product){
                   $isName = $product->getName() == $name;
                   if($isName){
                       return false;
                   }
               } 
            }
            
            $sql = "INSERT INTO $this->table(name, description, price) VALUES ('$name', '$description', $price)";

            $isInserted = $this->conn->query($sql);

            if($isInserted){
                return true;
            }
            else{
                return false;
            }
        }
        
        public function updateProduct($id, $name, $description, $price){            
            if($id == null)
                return false;
            
            $product = $this->selectProductByID($id);
            if($product == null)
                return false;
            
            if($name != null){
                $sql = "UPDATE $this->table SET name = '$name' WHERE id_product = $id";
                $this->conn->query($sql);
            }
            
            if($description != null){
                $sql = "UPDATE $this->table SET description = '$description' WHERE id_product = $id";
                $this->conn->query($sql);
            }
            
            if($price != null){
                $sql = "UPDATE $this->table SET price = $price WHERE id_product = $id";
                $this->conn->query($sql);
            }
            
            return true;
        }
        
        public function deleteProduct($id){
            if($id == null)
                return false;
            
            $sql = "DELETE FROM $this->table WHERE id_product = $id";
            $isDeleted = $this->conn->query($sql);
            
            return $isDeleted;
        }
        
        public function selectProductID($name){
            $id = null;
            $sql = "SELECT id_product FROM $this->table WHERE name = '$name';";

            $result = $this->conn->query($sql);
            $row = $result->fetch_object();
            if($row != null)
                $id = $row->id_product;
            
            return $id;
        }
        
        public function selectProductByID($id){
            $product = null;
            $sql = "SELECT name, description, price FROM $this->table WHERE id_product = $id;";

            $result = $this->conn->query($sql);
            $row = $result->fetch_object();
            if($row != null){
                $name = $row->name;
                $description = $row->description;
                $price = $row->price;
                
                $product = new Product($name, $price);
                $product->setDescription($description);
            }
            
            return $product;
        }
        
        public function selectProductByName($name){
            $product = null;
            $sql = "SELECT description, price FROM $this->table WHERE name = '$name';";

            $result = $this->conn->query($sql);
            $row = $result->fetch_object();
            if($row != null){
                $description = $row->description;
                $price = $row->price;
                
                $product = new Product($name, $price);
                $product->setDescription($description);
            }
            
            return $product;
        }
        
        public function selectAllProducts(){
            $sql = "SELECT * FROM $this->table;";
            
            $products = array();
            $result = $this->conn->query($sql);
            
            while($record = $result->fetch_object()){
                $id = $record->id_product;
                $name = $record->name;
                $description = $record->description;
                $price = $record->price;
                
                $product = new Product($name, $price);
                $product->setId($id);
                $product->setDescription($description);
                
                array_push($products, $product);
            }
            
            return $products;
        }
    }
?>