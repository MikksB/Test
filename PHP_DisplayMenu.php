<?php
    $table = $_GET["Table"];
    $start = $_GET["rStart"];
    $catV= $_GET["Cat"];
    $sval= $_GET["Val"];

    selectStmt($table, $start, $catV, $sval);

    function checkConn()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "JcoPOS";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) 
        {
            die("Connection failed: " . $conn->connect_error);
            return "e";
        }
        else
        {
            return $conn;
        }
    }

    function selectStmt($tableName, $startID, $cat, $val)
    {
        // echo $tableName, $startID, $cat;
        $tbHead = array();
        $myObj = array();
        $limit = 5;

        $colHead = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'";
        $colHead .= $tableName . "'";

        $sql = "SELECT * FROM $tableName LIMIT $startID,$limit"; //this selects from a table
        
        if(strlen($cat)>0){
            $sql = "SELECT * FROM $tableName WHERE CategoryID = $cat LIMIT $startID,$limit"; //this selects from a table
        }

        if(strlen($val)>0){
            $sql = "SELECT * FROM $tableName  WHERE ProductID = '$val' or ProductName = '$val' or ProductDesc = '$val' or CategoryID = '$val' or Price = '$val' or Availability = '$val'  LIMIT $startID,$limit";
        }     

        $connStat = checkConn();

        if($connStat != "e")
        {
            $stmt = $connStat->query($colHead);
    
            if ($stmt->num_rows > 0) 
            {
                $x = 0;
                while($row = $stmt->fetch_assoc()) 
                { 
                    $tbHead[$x] = $row["COLUMN_NAME"];
                    $x = $x + 1;
                }
            }
    
            $result = $connStat->query($sql);
    
            if ($result->num_rows > 0) 
            {
                // output data of each row
                while($rows = $result->fetch_assoc()) 
                {
                    $temp = array();
                    for($x = 1; $x < count($tbHead); $x++)
                    { 
                        $temp[$tbHead[$x]] = $rows[$tbHead[$x]];
                    }
                    $myObj[$rows[$tbHead[0]]] = $temp;
                }
                //print_r($myObj);
                $myJSON = json_encode($myObj);

                echo $myJSON;
            }
            else 
            {
                echo "0 results";
            }
        }
    }
?> 