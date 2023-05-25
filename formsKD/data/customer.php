<?php
    class Customer{
        private $id;
        private $name;
        private $phone;
        
        public function __construct($name, $phone){
            $this->name = $name;
            $this->phone = $phone;
        }
        
        public function setID(int $id){
            $this->id = $id;
        }
        
        public function getID():int{
            return $this->id;
        }
        
        public function setName($name){
            $this->name = $name;
        }
        
        public function getName(){
            return $this->name;
        }
        
        public function setPhone($phone){
            $this->phone = $phone;
        }
        
        public function getPhone(){
            return $this->phone;
        }
    }
?>