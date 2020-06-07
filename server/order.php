<?php

session_start();
include('files/des.php');
include('files/rsa.php');

?>
 <html>
 <style>
 table {
     font-family: arial, sans-serif;
     border-collapse: collapse;
     width: 400px;
 }

 td, th {
     width:100px;
     text-align: center;
     padding: 8px;
 }

 th {
     background-color: rgb(0, 0, 0);
     color: white;
 }
 </style>

<?php

//if DES key or cardNumber is not set, return to cart
if (!isset($_POST['DESkey']) or !isset($_POST['cardNumber'])) {
     header('Location: ../client/cart.php');
}

$serverPrivateKey = get_rsa_privatekey('keys/private.key');

$desKey = rsa_decryption($_POST['DESkey'], $serverPrivateKey);
$encryptedCard = $_POST['cardNumber'];
$cardNumber = php_des_decryption($desKey, $encryptedCard);
$productAdata = explode(":", php_des_decryption($desKey, $_POST['ProductAdata']));
$productBdata = explode(":", php_des_decryption($desKey, $_POST['ProductBdata']));
$productCdata = explode(":", php_des_decryption($desKey, $_POST['ProductCdata']));

$orderInfo = "### Client: " . $_SESSION['user'] . " ###\n";
$orderInfo .= "Item\tQuantity\tPrice\tSub-total\n";
if ($_POST['ProductAquantity'] > 0) {
    $orderInfo .= $_POST['ProductA'] . "\t" . $_POST['ProductAquantity'] . "\t" . $_POST['ProductAprice'] . "\t" . $_POST['ProductAtotal'] . "\n";
}
if ($_POST['ProductBquantity'] > 0) {
    $orderInfo .= $_POST['ProductB'] . "\t" . $_POST['ProductBquantity'] . "\t" . $_POST['ProductBprice'] . "\t" . $_POST['ProductBtotal'] . "\n";
}
if ($_POST['ProductCquantity'] > 0) {
    $orderInfo .= $_POST['ProductC'] . "\t" . $_POST['ProductCquantity'] . "\t" . $_POST['ProductCprice'] . "\t" . $_POST['ProductCtotal'] . "\n";
}

$orderInfo .= "Total Price: " . $_POST['totalPrice'] . "\n";
$orderInfo .= "Creditcard Number: " . $cardNumber . "\n";
?>


 <body>
 <h1> Order Confirmation </h1>
   <table>
     <tr>
       <th>Products</th>
       <th>Price</th>
     <th>Quantity</th>
     <th>Subtotal</th>
     </tr>

     <tr>
       <td><?php echo $productAdata[0]; ?></td>
       <td><?php echo $productAdata[1]; ?></td>
     <td><?php echo $productAdata[2]; ?></td>
     <td><?php echo $productAdata[3]; ?></td>
     </tr>

     <tr>
       <td><?php echo $productBdata[0]; ?></td>
       <td><?php echo $productBdata[1]; ?></td>
     <td><?php echo $productBdata[2]; ?></td>
     <td><?php echo $productBdata[3]; ?></td>
     </tr>

       <tr>
         <td><?php echo $productCdata[0]; ?></td>
         <td><?php echo $productCdata[1]; ?></td>
       <td><?php echo $productCdata[2]; ?></td>
       <td><?php echo $productCdata[3]; ?></td>
       </tr>

       <tr>
        <th></th>
        <th>Total</th>
    	<th><p id='Quantity' ><?php echo $_POST['totalQuantity']; ?></p><input id='totalQuantity' name='totalQuantity' type='hidden'/></th>
    	<th><p id='Price' ><?php echo $_POST['totalPrice']; ?></p><input id='totalPrice' name='totalPrice' type='hidden'/></th>
      </tr>

   </table>
<br><br>
<?php

  echo "<h2>Encrypted DES key: ".$_POST['DESkey']."</h2><br>";
  echo "<h2>Plaintext DES key: ".$desKey."</h2><br>";
  echo "<h2>Encrypted Creditcard Number: ".$_POST['cardNumber']."</h2><br>";
  echo "<h2>Plaintext Creditcard Number: ".$cardNumber."</h2><br>";
  echo "<h4>Encrypted Cart data: ".$_POST['ProductAdata'].$_POST['ProductBdata'].$_POST['ProductCdata']."</h4><br>";
  //echo "<h4>Decrypted Cart data: ".$decryptedCartData ."</h4><br>";


  $outFile = fopen("../database/orders.txt", "a") or die("<br/>Unable to open file orders.txt!");
  fwrite($outFile, $orderInfo);
  fclose($outFile);
?>
</body>
</html>
