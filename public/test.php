<?php
$serverName = "103.146.244.74";
$connectionInfo = array( "Database"=>"MILLENA","UID"=>"adm_milena", "PWD"=>"ptpnjuara1");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     echo "Connection established.<br />";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>