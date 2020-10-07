<!DOCTYPE html>
<html>
<head>
    <title>CHECK BTC WALLET ADDRESS</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <?php

    $msg = '';
    $color_class = '';

    if(isset($_POST['submit'])) {
        $wallet = $_POST['address'];
        if(checkAddress(($wallet))) {
            $msg = 'valid!';
            $color_class = 'green';
        } else {
            $msg = 'invalid!';
            $color_class = 'red';
        }
    }

    function checkAddress($address)
    {
        $origbase58 = $address;
        $dec = "0";

        for ($i = 0; $i < strlen($address); $i++)
        {
            $dec = bcadd(bcmul($dec,"58",0),strpos("123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz",substr($address,$i,1)),0);
        }

        $address = "";

        while (bccomp($dec,0) == 1)
        {
            $dv = bcdiv($dec,"16",0);
            $rem = (integer)bcmod($dec,"16");
            $dec = $dv;
            $address = $address.substr("0123456789ABCDEF",$rem,1);
        }

        $address = strrev($address);

        for ($i = 0; $i < strlen($origbase58) && substr($origbase58,$i,1) == "1"; $i++)
        {
            $address = "00".$address;
        }

        if (strlen($address)%2 != 0)
        {
            $address = "0".$address;
        }

        if (strlen($address) != 50)
        {
            return false;
        }

        if (hexdec(substr($address,0,2)) > 0)
        {
            return false;
        }

        return substr(strtoupper(hash("sha256",hash("sha256",pack("H*",substr($address,0,strlen($address)-8)),true))),0,8) == substr($address,strlen($address)-8);
    }

    ?>
    
    <div class="container">

        <h2>CHECK BTC WALLET ADDRESS VALIDITY</h2>
        <br>

        <form method="post">

            <input type="text" name="address" placeholder="BTC Address" required>
            <input type="submit" name="submit" value="Check">
            <br>
            <span class="<?php if($color_class != "") { echo $color_class; } ?>"><?php if($msg != "") { echo 'Status: '.$msg; } else { echo 'Status:'; } ?></span>

        </form>    

    </div>

</body>
</html>
