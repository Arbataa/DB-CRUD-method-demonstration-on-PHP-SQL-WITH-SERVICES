<?php
    include_once("base/connection.php");

    class OrderServices extends Connection{
        private $table = "orders";
        
        public function insertOrder($name, $phone, $orderDate){
            if($name == null || $phone == null || $orderDate == null){
                return false;
            }
            
            $orders = $this->selectAllOrders();
            
            if(sizeof($orders) > 0){
               foreach($orders as $order){
                   $customerName = $order->getCustomer()->getName();
                   $customerPhone = $order->getCustomer()->getPhone();
                   $isCustomer = $customerName == $name && $customerPhone == $phone;
                   
                   $orderDateCheck = date_create($orderDate);
                   $orderDateCheck = date_format($orderDateCheck, 'd.m.Y');
                   
                   $isDate = $orderDateCheck == $order->getOrderDate();
                   if($isCustomer && $isDate){
                       return false;
                   }
               } 
            }
            
            $customerService = new CustomerServices();
            $customerID = $customerService->selectCustomerID($name, $phone);
            
            $sql = "INSERT INTO $this->table(customer_id, order_date) VALUES ($customerID, '$orderDate')";

            $isInserted = $this->conn->query($sql);

            if($isInserted){
                return true;
            }
            else{
                return false;
            }
        }
        
        public function updateOrder($id, $name, $phone, $orderDate){ 
            if($id == null)
                return false;
            
            $order = $this->selectOrderByID($id);
            if($order == null)
                return false;
            
            if($name != null && $phone != null){
                $customerService = new CustomerServices();
                $customerID = $customerService->selectCustomerID($name, $phone);
                
                $sql = "UPDATE $this->table SET customer_id = $customerID WHERE id_order = $id";
                $this->conn->query($sql);
            }
            
            if($orderDate != null){
                $sql = "UPDATE $this->table SET order_date = '$orderDate' WHERE id_order = $id";
                $this->conn->query($sql);
            }
            
            return true;
        }
        
        public function deleteOrder($id){
            if($id == null)
                return false;
            
            $sql = "DELETE FROM $this->table WHERE id_order = $id";
            $isDeleted = $this->conn->query($sql);
            
            return $isDeleted;
        }
        
        public function selectOrderID($name, $phone, $orderDate){
            $id = null;
            
            $customerService = new CustomerServices();
            $customerID = $customerService->selectCustomerID($name, $phone);
            
            if($customerID == null)
                return null;
            
            $sql = "SELECT id_order FROM $this->table WHERE customer_id = $customerID AND order_date = '$orderDate';";

            

            $result = $this->conn->query($sql);
            $row = $result->fetch_object();
            if($row != null)
                $id = $row->id_order;
            
            return $id;
        }
        
        public function selectOrderByID($id){
            $order = null;
            $sql = "SELECT customer_id, order_date FROM $this->table WHERE id_order = $id;";

            $result = $this->conn->query($sql);
            $row = $result->fetch_object();
            if($row != null){
                $customerID = $row->customer_id;
                $orderDate = $row->order_date;
                
                $customerService = new CustomerServices();
                $customer = $customerService->selectCustomerByID($customerID);
                
                $order = new Order($customer, $orderDate);
            }
            
            return $order;
        }
        
        public function selectOrderByCustomerAndDate($name, $phone, $orderDate){
            $customerService = new CustomerServices();
            $customerID = $customerService->selectCustomerID($name, $phone);
            
            if($customerID == null)
                return null;
            
            $customer = new Customer($name, $phone);
            
            $sql = "SELECT customer_id, order_date FROM $this->table WHERE customer_id = $customerID AND order_date = '$orderDate';";
            
            $result = $this->conn->query($sql);
            $row = $result->fetch_object();
            $orderDate = $row->order_date;
                
            $order = new Order($customer, $orderDate);
            
            return $order;
        }
        
        public function selectOrdersByDate($orderDate){
            $customerService = new CustomerServices();
            
            $sql = "SELECT customer_id FROM $this->table WHERE order_date = '$orderDate';";
            
            $orders = array();
            $result = $this->conn->query($sql);
            while($record = $result->fetch_object()){
                $customerID = $record->customer_id;
                $customer = $customerService->selectCustomerByID($customerID);
                
                $order = new Order($customer, $orderDate);
                array_push($orders, $order);
            }
            
            return $orders;
        }
        
        public function selectOrdersByCustomer($name, $phone){
            $customerService = new CustomerServices();
            $customerID = $customerService->selectCustomerID($name, $phone);
            
            if($customerID == null)
                return null;
            
            $customer = new Customer($name, $phone);
            
            $sql = "SELECT order_date FROM $this->table WHERE customer_id = $customerID;";
            
            $orders = array();
            $result = $this->conn->query($sql);
            while($record = $result->fetch_object()){
                $orderDate = $record->order_date;
                
                $order = new Order($customer, $orderDate);
                array_push($orders, $order);
            }
            
            return $orders;
        }
        
        public function selectAllOrders(){
            $customerService = new CustomerServices();
            $sql = "SELECT * FROM $this->table;";
            
            $orders = array();
            $result = $this->conn->query($sql);
            
            while($record = $result->fetch_object()){
                $id = $record->id_order;
                $customerID = $record->customer_id;
                $orderDate = $record->order_date;
                
                
                $customer = $customerService->selectCustomerByID($customerID);
                
                $order = new Order($customer, $orderDate);
                $order->setId($id);
                
                array_push($orders, $order);
            }
            
            return $orders;
        }
    }
?>