<?php
session_start();
if(!isset($_SESSION['user'])){
    echo "You are not logged in. Click <a href='login.html'>HERE</a> to login!";
} else {
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
    <body>
    <h1><?php echo $_SESSION['user']?>, Welcome to Shopping Cart</h1>
    <a href='../server/logout.php'>Logout</a>
    <FORM ACTION='../server/order.php' method='POST'>
    <table>
      <tr>
        <th>Products</th>
        <th>Price</th>
    	<th>Quantity</th>
    	<th>Subtotal</th>
      </tr>
      <tr>
        <td>Product A<input type='hidden' name='ProductA' id='ProductA' value='ProductA'/></td>
        <td>$10<input type='hidden' name='ProductAprice' id='ProductAprice' value='10'/></td>
    	<td><input id='ProductAquantity' name='ProductAquantity' type='number' value='0' min='0' max='10' onchange='updateCart();'/></td>
    	<td><p id='ProductAsubtotal'>0</p><input id='ProductAtotal' name='ProductAtotal' type='hidden'/></td>
      </tr>
      <tr>
        <td>Product B<input type='hidden' name='ProductB' id='ProductB' value='ProductB'/></td>
        <td>$15<input type='hidden' name='ProductBprice' id='ProductBprice' value='15'/></td>
    	<td><input id='ProductBquantity' name='ProductBquantity' type='number' value='0' min='0' max='10' onchange='updateCart();'/></td>
    	<td><p id='ProductBsubtotal'>0</p><input id='ProductBtotal' name='ProductBtotal' type='hidden'/></td>
      </tr>
      <tr>
      <tr>
        <td>Product C<input type='hidden' name='ProductC' id='ProductC' value='ProductC'/></td>
        <td>$20<input type='hidden' name='ProductCprice' id='ProductCprice' value='20'/></td>
    	<td><input id='ProductCquantity' name='ProductCquantity' type='number' value='0' min='0' max='10' onchange='updateCart();'/></td>
    	<td><p id='ProductCsubtotal'>0</p><input id='ProductCtotal' name='ProductCtotal' type='hidden'/></td>
      </tr>
      <tr>
        <th></th>
        <th>Total</th>
    	<th><p id='Quantity' >0</p><input id='totalQuantity' name='totalQuantity' type='hidden'/></th>
    	<th><p id='Price' >0</p><input id='totalPrice' name='totalPrice' type='hidden'/></th>
      </tr>
      <tr>
        <td colspan='2' style='text-align: right;'>Enter DES key:</td>
    	<td colspan='2'><input type='text' name='DESkey' id='DESkey'/></td>
      </tr>
      </tr>
    	<tr>
        <td colspan='2' style='text-align: right;'>Card Number:</td>
    	<td colspan='2'><input maxlength='16' id='cardNumber' name='cardNumber' type='text'/></td>
      </tr>
        <td></td>
        <td></td>

    	<td><button type='submit' onclick='encryptData();'>Submit</button></td>
      </tr>
    </table>
<input id='ProductAdata' name='ProductAdata' type='hidden'>
<input id='ProductBdata' name='ProductBdata' type='hidden'>
<input id='ProductCdata' name='ProductCdata' type='hidden'>
    <br/><br/>


    </FORM>

   <?php
}
   ?>

<script type="text/javascript" src="js/rsa.js"></script>
<script type="text/javascript" src="js/sha256.js"></script>
<script type="text/javascript" src="js/des.js"></script>
<script type='text/javascript'>

function encryptData() {
  var key = document.getElementById('DESkey').value;
  var encryptedKey = RSAEncrypt(key);

  document.getElementById('DESkey').value = encryptedKey;

  var cardNumber = document.getElementById('cardNumber').value;
  var encryptedCard = javascript_des_encryption(key, cardNumber);
  document.getElementById('cardNumber').value = encryptedCard;
  var prodAdata = document.getElementById('ProductAdata').value;
  document.getElementById('ProductAdata').value = javascript_des_encryption(key, prodAdata);
  var prodBdata = document.getElementById('ProductBdata').value;
  document.getElementById('ProductBdata').value = javascript_des_encryption(key, prodBdata);
  var prodCdata = document.getElementById('ProductCdata').value;
  document.getElementById('ProductCdata').value = javascript_des_encryption(key, prodCdata);
}

function RSAEncrypt(k) {
  var publicKey = '-----BEGIN PUBLIC KEY-----MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzdxaei6bt/xIAhYsdFdW62CGTpRX+GXoZkzqvbf5oOxw4wKENjFX7LsqZXxdFfoRxEwH90zZHLHgsNFzXe3JqiRabIDcNZmKS2F0A7+Mwrx6K2fZ5b7E2fSLFbC7FsvL22mN0KNAp35tdADpl4lKqNFuF7NT22ZBp/X3ncod8cDvMb9tl0hiQ1hJv0H8My/31w+F+Cdat/9Ja5d1ztOOYIx1mZ2FD2m2M33/BgGY/BusUKqSk9W91Eh99+tHS5oTvE8CI8g7pvhQteqmVgBbJOa73eQhZfOQJ0aWQ5m2i0NUPcmwvGDzURXTKW+72UKDz671bE7YAch2H+U7UQeawwIDAQAB-----END PUBLIC KEY-----';
  var jse = new JSEncrypt();
  jse.setPublicKey(publicKey);

  var message = jse.encrypt(k);
  return message;
}

function desEncrypt(key, message) {
  return javascript_des_encryption(key, message);
}


function calculateSubTotal(productName){

	var quantity = parseInt(document.getElementById(productName+'quantity').value);
	if(quantity > 0){
		var price = parseInt(document.getElementById(productName+'price').value);

		var subtotal = price * quantity;
		document.getElementById(productName+'subtotal').innerHTML = subtotal;
    document.getElementById(productName+'total').value = subtotal;
    document.getElementById(productName+'data').value = productName + ":" + quantity + ":" + price + ":" + subtotal;
		return subtotal;
	}
	document.getElementById(productName+'subtotal').innerHTML = 0;
	document.getElementById(productName+'total').value = 0;
  document.getElementById(productName+'data').value = productName + ":0:0:0";
	return 0;
}

function updateCart(){

	var total = calculateSubTotal('ProductA')+calculateSubTotal('ProductB')+calculateSubTotal('ProductC');

	var quantity = parseInt(document.getElementById('ProductAquantity').value)+parseInt(document.getElementById('ProductBquantity').value)+parseInt(document.getElementById('ProductCquantity').value);

	document.getElementById('Quantity').innerHTML = quantity;
	document.getElementById('totalQuantity').value = quantity;

	document.getElementById('Price').innerHTML = total;
	document.getElementById('totalPrice').value = total;

}

</script>

</body>
    </html>
