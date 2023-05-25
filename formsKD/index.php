<!doctype html>
<?php
    header("Content-Type: text/html;charset=UTF-8");
    include_once('includes.php');
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <link rel="stylesheet" href="/css/style.css" type="text/css"/>
    </head>
    
    <body>
        <form method="post">
            Order: <select name="orders">
                <<?php
                    $orderService = new OrderServices();
                    $orders = $orderService->selectAllOrders();
                    foreach ($orders as $o) {
                        $customer = $o->getCustomer()->getName();
                        $date = $o->getOrderDate();
                        $phone = $o->getCustomer()->getPhone();
                        echo "<option>$customer/$phone/$date</option>";
                    }
                ?>
            </select><br><br>
            Product: <select name="products">
                <?php
                    $productService = new ProductServices();
                    $products = $productService->selectAllProducts();
                    foreach ($products as $p) {
                        $name = $p->getName();
                        echo "<option>$name</option>";
                    }
                ?>
            </select><br><br>
            Quantity: <input type="number" name="quantity"><br><br>

            Order-Item: <select name="orderItem">
                <?php
                    $orderItemService = new OrderItemServices();
                    $orderItems = $orderItemService->selectAllOrderItems();
                    foreach ($orderItems as $or) {
                       $name = $or->getOrder()->getCustomer()->getName();
                       $phone = $or->getOrder()->getCustomer()->getPhone();
                       $orderDate = date('Y-m-d',strtotime($or->getOrder()->getOrderDate()));
                       $product = $or->getProduct()->getName();
                       $quantity = $or->getQuantity();

                       echo "<option>$name/$phone/$orderDate/$product/$quantity</option>";
                    }
                ?>
            </select><br><br>
            
			<br>
            <input type="submit" name="add" value="Add">
            <input type="submit" name="edit" value="Edit">
            <input type="submit" name="delete" value="Delete"><br>

            <?php
                $orderItemService = new OrderItemServices();
                if(isset($_POST['add'])){
                    $order = $_POST['orderItem'];
                    $productName = $_POST['products'];

                    $orderName = explode("/",$order)[0];
                    $orderPhone = explode("/",$order)[1];
                    $orderDate = date('Y-m-d',strtotime(explode("/",$order)[2]));

                    $quantity = $_POST['quantity'];

                    $orderItemService->insertOrderItem($orderName,$orderPhone,$orderDate,$productName,$quantity);
                }
                if(isset($_POST['edit'])){
                    $selected = $_POST['orderItem'];

                    $name = explode("/",$selected)[0];
                    $phone = explode("/",$selected)[1];
                    $date = explode("/",$selected)[2];
                    $product = explode("/",$selected)[3];

                    $orderItemID = $orderItemService->selectOrderItemID($name,$phone,$date,$product);
                    
                    $order = $_POST['orders'];
                    $newProduct = $_POST['products'];

                    $newName = explode("/",$order)[0];
                    $newPhone = explode("/",$order)[1];
                    $newDate = date('Y-m-d',strtotime(explode("/",$order)[2]));

                    $newQuantity = $_POST['quantity'];

                    $orderItemService->updateOrderItem($orderItemID, $newName, $newPhone, $newDate, $newProduct, $newQuantity);
                }
                if(isset($_POST['delete'])){
                    $selected = $_POST['orderItem'];

                    $name = explode("/",$selected)[0];
                    $phone = explode("/",$selected)[1];
                    $date = explode("/",$selected)[2];
                    $product = explode("/",$selected)[3];

                    $orderItemID = $orderItemService->selectOrderItemID($name,$phone,$date,$product);
                    
                    $orderItemService->deleteOrderItem($orderItemID);
                }
            ?>
        </form>

        <h1>Order Item data:</h1>
        <table>
                <tr>
                    <th>Nr.</th>
                    <th>Order</th>
                    <th>Product</th>
                    <th>Quantity date</th>
                </tr>
                <?php
                    $orderItemService = new OrderItemServices();
                    $ordersItems = $orderItemService->selectAllOrderItems();
                
                    $i = 1;
                    foreach($ordersItems as $o){
                       $order = $o->getOrder();
                       $product = $o->getProduct();
                       $quantity = $o->getQuantity();

                       $orderName = $order->getCustomer()->getName();
                       $orderPhone = $order->getCustomer()->getPhone();
                       $orderDate = $order->getOrderDate();

                       $productName = $product->getname();
                       $productPrice = $product->getPrice();
                       
                        echo "<tr>
                            <td>$i</td>
                            <td>$orderName      $orderPhone     $orderDate</td>
                            <td>$productName    $productPrice</td>
                            <td>$quantity</td>
                        </tr>";
                            
                        $i++;
                    }
                ?>
        </table>
    </body>
</html>