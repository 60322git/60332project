<?php 
  session_start();
  require 'db.php';

if ($_SESSION['code'] == 2)
{

  echo "<link rel='stylesheet' type='text/css' href='../css/table.css' />";

    if (isset($_POST['delete']) )
  {

   foreach($_POST['emails'] as $theEmail) { 
   $query  = "DELETE FROM users WHERE email='$theEmail'";
   $result = $mysqli->query($query);
  	if (!$result) echo "DELETE failed: $query<br>" .
      $mysqli->error . "<br><br>";
    
   }
   
      
  } 
    if (isset($_POST['first_name'])&&
        isset($_POST['last_name']) &&
        isset($_POST['last_name'])&&
        isset($_POST['usercode']))
    {
    $first_name = get_post($mysqli, 'first_name');
    $last_name    = get_post($mysqli, 'last_name');
    $email = get_post($mysqli, 'email');
    $usercode     = get_post($mysqli, 'usercode');
    $hash = $mysqli->escape_string( md5( rand(0,1000) ) );
    $defaultpass = bin2hex(openssl_random_pseudo_bytes(4));
    $query    = "INSERT INTO users(first_name,last_name,email,password,usercode, hash) VALUES" .
      "('$first_name', '$last_name', '$email', '$defaultpass', '$usercode', '$hash')";
    $result   = $mysqli->query($query);

  	if (!$result) echo "INSERT failed: $query<br>" .
      $mysqli->error . "<br><br>";
  }

  echo <<<_END
  <form action="admin.php" method="post"><pre>
    First Name <input type="text" name="first_name">
    Last Name  <input type="text" name="last_name">
    Email      <input type="text" name="email">
    Usercode   <input type="text" name="usercode">
               <input type="submit" value="ADD RECORD">
  </pre></form>
_END;
  
 $query  = "SELECT * FROM users";
  $result = $mysqli->query($query);
  if (!$result) die ("Database access failed: " . $mysqli->error);

  $rows = $result->num_rows;
  
  echo "<form action='admin.php' method='post'>  
  <table><thead><tr><th>First Name</th><th>Last Name</th><th>Email</th><th>Usercode</th>
  </tr> </thead> ";
  

  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);

    echo <<<_END
    <tr>
    <td> $row[1]</td>
    <td> $row[2]</td>
    <td> $row[3]</td>
    <td> $row[6]</td>
    <td><input type="checkbox" name="emails[]" value="$row[3]"></td>
    </tr>
 
_END;
  }
echo " </table> <input type='submit' name='delete' value='Delete'> </form> ";

  $result->close();
  $mysqli->close();
  
  function get_post($mysqli, $var)
  {
    return $mysqli->real_escape_string($_POST[$var]);
  }
}
else echo "Not authorized. You are not logged in as an administrator.";

?>