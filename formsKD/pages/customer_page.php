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
            Customer name: <input type="text" name="name"><br>
            Phone nbr:<input type="text" name="phone"> <br>
            <br>
            Customer: <select name="customer">
                <?php
                    $customerService = new CustomerServices();
                    $customers = $customerService->selectAllCustomers();
                    foreach ($customers as $c) {
                        $name = $c->getName();
                        echo "<option>$name</option>";
                    }
                ?>
            </select><br>
            
			<br>
            <input type="submit" name="add" value="Add">
            <input type="submit" name="edit" value="Edit">
            <input type="submit" name="delete" value="Delete">

            <?php
                 $customerService = new CustomerServices();
                 if(isset($_POST['add'])){
                    $name = $_POST['name'];
                    $phone = $_POST['phone'];
    
                    $customerService->insertCustomer($name,$phone);
                    }
                if(isset($_POST['edit'])){
                    $newName = $_POST['name'];
                    $newPhone = $_POST['phone'];
                    $selected = $_POST['customer'];
                    $customer = $customerService->selectCustomerByName($selected);
                    $name = $customer->getName();
                    $phone = $customer->getPhone();
                    $customerID = $customerService->selectCustomerID($name,$phone);
                    $customerService->updateCustomer($customerID,$newName,$newPhone);
                }
                if(isset($_POST['delete'])){
                    $selected = $_POST['customer'];
                    $customer = $customerService->selectCustomerByName($selected);
                    $name = $customer->getName();
                    $phone = $customer->getPhone();
                    $customerID = $customerService->selectCustomerID($name,$phone);
                    $customerService->deleteCustomer($customerID);
                }
            ?>
        </form>

        <h1>Customer data:</h1>
        <table>
                <tr>
                    <th>Nr.</th>
                    <th>Name</th>
                    <th>Phone</th>
                </tr>
                <?php
                    $customerService = new CustomerServices();
                    $customers = $customerService->selectAllCustomers();
                
                    $i = 1;
                    foreach($customers as $c){
                        $name = $c->getName();
                        $phone= $c->getPhone();
                        echo "<tr>
                            <td>$i</td>
                            <td>$name</td>
                            <td>$phone</td>
                        </tr>";
                            
                        $i++;
                    }
                ?>
        </table>
    </body>
</html>