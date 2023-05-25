<?php
    include_once("base/connection.php");

    class CustomerServices extends Connection{
        private $table = "customers";
        
        public function insertCustomer($name, $phone){
            if($name == null || $phone == null){
                return false;
            }
            
            $customers = $this->selectAllCustomers();
            
            if(sizeof($customers) > 0){
               foreach($customers as $customer){
                   $isName = $customer->getName() == $name;
                   if($isName){
                       return false;
                   }
               } 
            }
            
            $sql = "INSERT INTO $this->table(name, phone) VALUES ('$name', '$phone')";

            $isInserted = $this->conn->query($sql);

            if($isInserted){
                return true;
            }
            else{
                return false;
            }
        }
        
        public function updateCustomer($id, $name, $phone){
            
            if($id == null)
                return false;
            
            $customer = $this->selectCustomerByID($id);
            if($customer == null)
                return false;
            
            if($name != null){
                $sql = "UPDATE $this->table SET name = '$name' WHERE id_customer = $id";
                $this->conn->query($sql);
            }
            
            if($phone != null){
                $sql = "UPDATE $this->table SET phone = '$phone' WHERE id_customer = $id";
                $this->conn->query($sql);
            }
            
            return true;
        }
        
        public function deleteCustomer($id){
            if($id == null)
                return false;
            
            $sql = "DELETE FROM $this->table WHERE id_customer = $id";
            $isDeleted = $this->conn->query($sql);
            
            return $isDeleted;
        }
        
        public function selectCustomerID($name, $phone){
            $id = null;
            $sql = "SELECT id_customer FROM $this->table WHERE name = '$name' AND phone = '$phone';";

            $result = $this->conn->query($sql);
            $row = $result->fetch_object();
            if($row != null)
                $id = $row->id_customer;
            
            return $id;
        }
        
        public function selectCustomerByID($id){
            $customer = null;
            $sql = "SELECT name, phone FROM $this->table WHERE id_customer = $id;";

            $result = $this->conn->query($sql);
            $row = $result->fetch_object();
            if($row != null){
                $name = $row->name;
                $phone = $row->phone;
                
                $customer = new Customer($name, $phone);
            }
            
            return $customer;
        }
        
        public function selectCustomerByName($name){
            $customer = null;
            $sql = "SELECT phone FROM $this->table WHERE name = '$name';";

            $result = $this->conn->query($sql);
            $row = $result->fetch_object();
            if($row != null){
                $phone = $row->phone;
                
                $customer = new Customer($name, $phone);
            }
            
            return $customer;
        }
        
        public function selectCustomerByPhone($phone){
            $customer = null;
            $sql = "SELECT name FROM $this->table WHERE phone = '$phone';";

            $result = $this->conn->query($sql);
            $row = $result->fetch_object();
            if($row != null){
                $name = $row->name;
                
                $customer = new Customer($name, $phone);
            }
            
            return $customer;
        }
        
        public function selectAllCustomers(){
            $sql = "SELECT * FROM $this->table;";
            
            $customers = array();
            $result = $this->conn->query($sql);
            
            while($record = $result->fetch_object()){
                $id = $record->id_customer;
                $name = $record->name;
                $phone = $record->phone;
                
                $customer = new Customer($name, $phone);
                $customer->setId($id);
                
                array_push($customers, $customer);
            }
            
            return $customers;
        }
    }
?>