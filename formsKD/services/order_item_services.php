<?php
    include_once("base/connection.php");

    class OrderItemServices extends Connection{
        private $table = "order_items";
        
        public function insertOrderItem($customer, $phone, $orderDate, $product, $quantity){
            if($customer == null || $phone == null || $orderDate == null || $product == null){
                return false;
            }
            
            $orderItems = $this->selectAllOrderItems();
            
            if(sizeof($orderItems) > 0){
               foreach($orderItems as $orderItem){
                   $customerName = $orderItem->getOrder()->getCustomer()->getName();
                   $customerPhone = $orderItem->getOrder()->getCustomer()->getPhone();
                   
                   $orderDateCheck = date_create($orderDate);
                   $orderDateCheck = date_format($orderDateCheck, 'd.m.Y');
                   
                   $isOrder = $customerName == $customer && 
                       $customerPhone == $phone &&
                       $orderDateCheck == $orderItem->getOrder()->getOrderDate();
                   
                   $productName = $orderItem->getProduct()->getName();
                   $isProduct = $productName == $product;
                   
                   if($isOrder && $isProduct){
                       return false;
                   }
               } 
            }
            
            $orderService = new OrderServices();
            $orderID = $orderService->selectOrderID($customer, $phone, $orderDate);
            
            $productService = new ProductServices();
            $productID = $productService->selectProductID($product);
            
            if($quantity == null)
                $quantity = 1;
            
            $sql = "INSERT INTO $this->table(order_id, product_id, quantity) VALUES ($orderID, $productID, $quantity)";

            $isInserted = $this->conn->query($sql);

            if($isInserted){
                return true;
            }
            else{
                return false;
            }
        }
        
        public function updateOrderItem($id, $customer, $phone, $orderDate, $product, $quantity){ 
            if($id == null)
                return false;
            
            $orderItem = $this->selectOrderItemByID($id);
            if($orderItem == null)
                return false;
            
            if($customer != null && $phone != null && $orderDate){
                $orderService = new OrderServices();
                $orderID = $orderService->selectOrderID($customer, $phone, $orderDate);
                
                $sql = "UPDATE $this->table SET order_id = $orderID WHERE id_item = $id";
                $this->conn->query($sql);
            }
            
            if($product != null){
                $productService = new ProductServices();
                $productID = $productService->selectProductID($product);
                
                $sql = "UPDATE $this->table SET product_id = $productID WHERE id_item = $id";
                $this->conn->query($sql);
            }
            
            if($quantity != null){
                $sql = "UPDATE $this->table SET quantity = $quantity WHERE id_item = $id";
                $this->conn->query($sql);
            }
            
            return true;
        }
        
        public function deleteOrderItem($id){
            if($id == null)
                return false;
            
            $sql = "DELETE FROM $this->table WHERE id_item = $id";
            $isDeleted = $this->conn->query($sql);
            
            return $isDeleted;
        }
        
        public function selectOrderItemID($customer, $phone, $orderDate, $product){
            $id = null;
            
            $orderService = new OrderServices();
            $orderID = $orderService->selectOrderID($customer, $phone, $orderDate);
            
            $productService = new ProductServices();
            $productID = $productService->selectProductID($product);
            
            if($orderID == null || $productID == null)
                return null;
            
            $sql = "SELECT id_item FROM $this->table WHERE order_id = $orderID AND product_id = $productID;";

            $result = $this->conn->query($sql);
            $row = $result->fetch_object();
            if($row != null)
                $id = $row->id_item;
            
            return $id;
        }
        
        public function selectOrderItemByID($id){
            $order = null;
            $sql = "SELECT order_id, product_id, quantity FROM $this->table WHERE id_item = $id;";

            $result = $this->conn->query($sql);
            $row = $result->fetch_object();
            if($row != null){
                $orderID = $row->order_id;
                $productID = $row->product_id;
                $quantity = $row->quantity;
                
                $orderService = new OrderServices();
                $order = $orderService->selectOrderByID($orderID);
                
                $productService = new ProductServices();
                $product = $productService->selectProductByID($productID);
                
                $orderItem = new OrderItem($order, $product, $quantity);
            }
            
            return $orderItem;
        }
        
        public function selectAllOrderItems(){
            $orderService = new OrderServices();
            $productService = new ProductServices();
            
            $sql = "SELECT * FROM $this->table;";
            
            $orderItems = array();
            $result = $this->conn->query($sql);
            
            while($record = $result->fetch_object()){
                $id = $record->id_item;
                $orderID = $record->order_id;
                $productID = $record->product_id;
                $quantity = $record->quantity;
                
                
                $order = $orderService->selectOrderByID($orderID);
                $product = $productService->selectProductByID($productID);
                
                $orderItem = new OrderItem($order, $product, $quantity);
                $orderItem->setId($id);
                
                array_push($orderItems, $orderItem);
            }
            
            return $orderItems;
        }
    }
?>