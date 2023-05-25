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
        Customer: <select name="customers">
                <?php
                    $customerService = new CustomerServices();
                    $customers = $customerService->selectAllCustomers();
                    foreach ($customers as $c) {
                        $name = $c->getName();
                        echo "<option>$name</option>";
                    }
                ?>
            </select><br><br>
            Order date: <input type="date" name="date"><br>
            <br>
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
            </select><br>
            
			<br>
            <input type="submit" name="add" value="Add">
            <input type="submit" name="edit" value="Edit">
            <input type="submit" name="delete" value="Delete">
            
        </form>
            <?php
                $customerService = new CustomerServices();
                $orderService = new OrderServices();

                if(isset($_POST['add'])){
                    $customer = $_POST['customers'];
                    $date = $_POST['date'];
                    
                    $customer = $customerService->selectCustomerByName($customer);
                    $name = $customer->getName();
                    $phone = $customer->getPhone();
                    
                    $orderService->insertOrder($name,$phone,$date);
                }
                if(isset($_POST['edit'])){
                    $customer = $_POST['customers'];
                    $date = $_POST['date'];
                    $selected = $_POST['orders'];
                    $orderName = explode("/",$selected)[0];
                    $orderPhone = explode("/",$selected)[1];
                    $orderDate = date('Y-m-d',strtotime(explode("/",$selected)[2]));
                    echo $orderDate;
                    $orderID = $orderService->selectOrderID($orderName,$orderPhone,$orderDate);
                    
                    $customer = $customerService->selectCustomerByName($customer);
                    $name = $customer->getName();
                    $phone = $customer->getPhone();
                    
                    $orderService->updateOrder($orderID,$name,$phone,$date);
                }
                if(isset($_POST['delete'])){
                    $selected = $_POST['orders'];
                    $orderName = explode("/",$selected)[0];
                    $orderPhone = explode("/",$selected)[1];
                    $orderDate = explode("/",$selected)[2];
                    $orderDate = date('Y-m-d',strtotime(explode("/",$selected)[2]));
                    $orderID = $orderService->selectOrderID($orderName,$orderPhone,$orderDate);

                    $orderService->deleteOrder($orderID);
                }
            ?>
        <h1>Order data:</h1>
        <table>
                <tr>
                    <th>Nr.</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Order date</th>
                </tr>
                <?php
                    $orderService = new OrderServices();
                    $orders = $orderService->selectAllOrders();
                
                    $i = 1;
                    foreach($orders as $o){
                        $customer = $o->getCustomer()->getName();
                        $phone = $o->getCustomer()->getPhone();
                        $date = $o->getOrderDate();
                        echo "<tr>
                            <td>$i</td>
                            <td>$customer</td>
                            <td>$phone</td>
                            <td>$date</td>
                        </tr>";
                            
                        $i++;
                    }
                ?>
        </table>
    </body>
</html>