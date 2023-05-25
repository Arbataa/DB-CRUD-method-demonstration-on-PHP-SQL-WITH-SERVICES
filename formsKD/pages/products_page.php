<!doctype html>
<?php
    header("Content-Type: text/html;charset=UTF-8");
    include_once("../includes.php");
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <link rel="stylesheet" href="/css/style.css" type="text/css"/>
    </head>
    
    <body>
        <form method="POST">
            Product name: <input type="text" name="name"><br>
            Product description:<input type="text" name="description"> <br>
            Product price:<input type="number" name="price"> <br>
            <br>
            Product: <select name="product_name">
                <?php
                    $productService = new ProductServices();
                    $products = $productService->selectAllProducts();
                    foreach ($products as $p) {
                        $name = $p->getName();
                        echo "<option>$name</option>";
                    }
                ?>
            </select><br>
            
			<br>
            <input type="submit" name="add" value="Add">
            <input type="submit" name="edit" value="Edit">
            <input type="submit" name="delete" value="Delete">

            <?php
                 $productService = new ProductServices();
                 if(isset($_POST['add'])){
                    $name = $_POST['name'];
                    $description = $_POST['description'];
                    $price = $_POST['price'];
    
                    $productService->insertProduct($name,$description,$price);
                    }
                if(isset($_POST['edit'])){
                    $newName = $_POST['name'];
                    $newDescription = $_POST['description'];
                    $newPrice = $_POST['price'];
                    $selected = $_POST['product_name'];
                    $productID = $productService->selectProductID($selected);

                    $productService->updateProduct($productID,$newName,$newDescription, $newPrice);
                }
                if(isset($_POST['delete'])){
                    $selected = $_POST['product_name'];
                    $productID = $productService->selectProductID($selected);
                    $productService->deleteProduct($productID);
                }
            ?>
        </form>

        <h1>Product data:</h1>
        <table>
                <tr>
                    <th>Nr.</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                </tr>
                <?php
                   $productService = new ProductServices();
                    $products = $productService->selectAllProducts();
                
                    $i = 1;
                    foreach($products as $p){
                        $name = $p->getName();
                        
                        if($p->getDescription() != null){
                            $description = $p->getDescription();
                        }
                        else{
                            $description = "-- Nothing --";
                        }
                        $price= $p->getPrice();
                        echo "<tr>
                            <td>$i</td>
                            <td>$name</td>
                            <td>$description</td>
                            <td>$price</td>
                        </tr>";
                            
                        $i++;
                    }
                ?>
        </table>
    </body>
</html>