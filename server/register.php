<html>

    <body>
        <?php
            $receivedUsername = $_POST['username'];
            $receivedPassword = $_POST['password'];
            
            $found = 0;

            foreach(file('../database/users.txt') as $line ) {
                list($username, $password) = explode(",", $line);

                if ($receivedUsername == $username){
                    $found = 1;
                }
            }

            if ($found == 1) {
                echo "Username already exists. Click <a href='../client/register.html'>HERE</a> to try again.";
            } else {
                $file = fopen("../database/users.txt", "a");
                fwrite($file, $receivedUsername.",".$receivedPassword."\n");
                fclose($file);
                echo "Registration successful. Click <a href='../client/login.html'>HERE</a> to login.";
            }
        ?>
</html>
