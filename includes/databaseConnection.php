<?php
//////////////////////////////////////////////////
//Script Name: Scrapper
//Author: Ankit Sinha
//Version: 1.0
/////////////////////////////////////////////////
   
 // Database Constants
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "scrapper");



// 1. Create a database connection
$connection = mysql_connect(DB_SERVER,DB_USER,DB_PASS);
if (!$connection) {
    die("Database connection failed: " . mysql_error());
}

select_db(DB_NAME,$connection);

function select_db($dbname,$connection) {

    // 2. Select a database to use 
    $db_select = mysql_select_db($dbname,$connection);
    if (!$db_select) {
        die("Database selection failed: " . mysql_error());
    }
}

function sanitizeForCsv($data){
    $data = trim( $data );
    $data = trim(preg_replace('~[\r\n]+~', ' ', $data));
    $data = str_replace(',','',$data);
    $data = str_replace("'",'',$data);
    return $data;
}
//function to  scrap buisness
//function  scrapData($mileDistance, $companyName, $url, $street, $city, $state, $phoneNumber, $type)
function scrapData($name, $address, $tele )
{   
    global $connection;
    $NameOfChurch  = sanitizeForCsv($name);
    $Address = sanitizeForCsv($address);
    $Telephone = sanitizeForCsv($tele);
    //$Fax = sanitizeForCsv($Fax);
    //$emai = sanitizeForCsv($emai);
    //$website = sanitizeForCsv($website);
    
    
    
    $query="INSERT INTO scrap1 (`name`,`address`,`telephone`) ";//`Fax`,`emai`,`website`)";
    $query.=" VALUES ( '{$NameOfChurch}','{$Address}','{$Telephone}' ";
    $query.= " ) ON DUPLICATE   ";
    $query.= "KEY UPDATE id = id, name = '{$NameOfChurch}',address = '{$Address}', telephone = '{$Telephone}'";//",emai = '{$emai}',website = '{$website}'";
    echo $query;
    mysql_query($query,$connection) or die(mysql_error());
    
}

//function to get Scrapped data from database
function getScrappedData(){
    
    global $connection;
    $query="SELECT `name`,`address`,`telephone` FROM scrap1 ";
        $query.=" ORDER BY name ";
    //echo $query;
    $resultSet=mysql_query($query,$connection);
    while($array=mysql_fetch_array($resultSet,MYSQL_ASSOC)){
        $returnArray[]=$array;
    }
    return $returnArray;
}

?>
