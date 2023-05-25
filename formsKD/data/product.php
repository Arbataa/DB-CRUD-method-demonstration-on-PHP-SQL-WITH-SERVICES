<?php
    class Product{
        private $id;
        private $name;
        private $description;
        private $price;
        
        public function __construct($name, $price){
            $this->name = $name;
            $this->price = $price;
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
        
        public function setDescription($description){
            $this->description = $description;
        }
        
        public function getDescription(){
            return $this->description;
        }
        
        public function setPrice($price){
            $this->price = $price;
        }
        
        public function getPrice(){
            return $this->price;
        }
    }
?>