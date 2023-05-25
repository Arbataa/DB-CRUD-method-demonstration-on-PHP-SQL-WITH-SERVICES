<?php
    class OrderItem{
        private $id;
        private $order;
        private $product;
        private $quantity;
        
        public function __construct(Order $order, Product $product, $quantity){
            $this->order = $order;
            $this->product = $product;
            $this->quantity = $quantity;
        }
        
        public function setID(int $id){
            $this->id = $id;
        }
        
        public function getID():int{
            return $this->id;
        }
        
        public function setOrder(Order $order){
            $this->order = $order;
        }
        
        public function getOrder(){
            return $this->order;
        }
        
        public function setProduct(Product $product){
            $this->product = $product;
        }
        
        public function getProduct(){
            return $this->product;
        }
        
        public function setQunatity($quantity){
            $this->quantity = $quantity;
        }
        
        public function getQuantity(){
            return $this->quantity;
        }
    }
?>