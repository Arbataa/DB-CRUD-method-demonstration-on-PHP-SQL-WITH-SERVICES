<?php
    class Order{
        private $id;
        private $customer;
        private $orderDate;
        
        public function __construct(Customer $customer, $orderDate){
            $this->customer = $customer;
            $orderDate = date_create($orderDate);
            $this->orderDate = date_format($orderDate, 'd.m.Y');
        }
        
        public function setID(int $id){
            $this->id = $id;
        }
        
        public function getID():int{
            return $this->id;
        }
        
        public function setCustomer(Customer $customer){
            $this->customer = $customer;
        }
        
        public function getCustomer(){
            return $this->customer;
        }
        
        public function setOrderDate($orderDate){
            $this->orderDate = date('d F Y', $orderDate);
        }
        
        public function getOrderDate(){
            return $this->orderDate;
        }
    }
?>