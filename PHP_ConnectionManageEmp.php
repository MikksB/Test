<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "JcoPOS";
$table = $_GET["Table"];
$cat= $_GET["Cat"];
$val= $_GET["Val"];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) 
{
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM $table";

if(strlen($cat)>0){
  $sql .=" WHERE UserTypeID = ".$cat;
}

if(strlen($val)>0){
    $sql .= " WHERE UserID = '$val' or LastName = '$val' or FirstName = '$val' or UserTypeID = '$val' ";
}  

$colHead = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'";
$colHead .= $table . "'";

$stmt = $conn->query($colHead);
$result = $conn->query($sql);

$tbHead = array();
$myObj = array();

if ($stmt->num_rows > 0) 
{
    $x = 0;
    while($row = $stmt->fetch_assoc()) 
    {

        $tbHead[$x] = $row["COLUMN_NAME"];
          
        //echo "id: " . $row["COLUMN_NAME"]. "<br>";
        $x = $x + 1;
    }
}


if ($result->num_rows > 0) 
{
  // output data of each row

  while($rows = $result->fetch_assoc()) 
  {

    $temp = array();

    for($x = 0; $x < count($tbHead); $x++)
    {
        $temp[$tbHead[$x]] = $rows[$tbHead[$x]];
    }
    $myObj[$rows[$tbHead[0]]] = $temp;
    
    $myJSON = json_encode($myObj);
}
  $myJSON = json_encode($myObj);

  echo $myJSON;
} 
else 
{
  echo "0 results";
}
$conn->close();
?> 