<?php

include_once("db.php");
header("Refresh:1");
//LINK  ../table_overview?rom=d201&count=7

if(isset($_GET['rom']))//1
{

	$romnr = $_GET['rom'];
	$romnr = mysql_real_escape_string("$romnr");
	$romnr = strtoupper($romnr);
	
	$rom_count = $_GET['count'];
	$rom_count = mysql_real_escape_string("$rom_count");
  $time=date("d.m.Y H:i:s");
	$tilgjenglig=0;//default
	$rest=mysql_query("SELECT * FROM smartsys_sql WHERE roomnr='$romnr'"); //sjekk om rommet er tilgjenglig
	while($row=mysql_fetch_array($rest)) 
	{
    	$tilgjenglig=1;
  }

	if($tilgjenglig==1)
	{//oppdater rommet hvis rommet er tilgjenglig i tabellen
    mysql_query("UPDATE `smartsys_sql` SET counter = '" . $rom_count . "' WHERE roomnr = '$romnr'") or die(mysql_error());

    mysql_query("UPDATE `smartsys_sql` SET type = '" . $rom_count . "' WHERE roomnr = '$type'") or die(mysql_error());

    mysql_query("UPDATE `smartsys_sql` SET capacity = '" . $capacity . "' WHERE roomnr = '$capacity'") or die(mysql_error());

    mysql_query("UPDATE `smartsys_sql` SET lastactivity = '$time' WHERE roomnr = '$romnr'") or die(mysql_error());
  } else
  {//legg til nytt rom
     mysql_query("INSERT INTO smartsys_sql (roomnr,type,counter,capacity,lastactivity) VALUES('$romnr','$type', '$rom_count','$capacity','$time') ") or die(mysql_error());
  }
}


if($_GET['admin']==1)
{
  $admin="<a href='?admin=1&yes=1'>Edit</a>";
  if($_GET['yes']==1)
  {
    $top="<td  style='padding:5px;'>Edit</td>";
    if($_GET['slettid']!=0)
    {//slette denne id'en
    $id=$_GET['slettid'];
      mysql_query("DELETE FROM smartsys_sql WHERE id='$id'");
    }
  } else  $top="";
}
	$table="
	$admin
	<table width='100%' style='background-color:#D8E2C9;padding:5px;text-align:center;'><tbody>
	<tr style='background-color:#8DD2E0;margin:5px;height:40px;'>
   	<td  style='padding:5px;'>Room number</td> <td  style='padding:5px;'>Type of room</td>  <td  style='padding:5px;'>Amount of people</td> <td  style='padding:5px;'>Capacity</td> <td  style='padding:5px;'>Status</td> $top
   	</tr>";
	$rest=mysql_query("SELECT * FROM smartsys_sql");
	while($row=mysql_fetch_array($rest))
	{
    $id=$row["id"];
    $roomnr=$row["roomnr"];
    $counter=$row["counter"];
    $capacity=$row["capacity"];
    $type = $row["type"];
    
    if($counter=="0")
    {
      $state="Empty";
      $color="#9CBA7F";
    } 
    elseif ($capacity == $counter) {
      $state="Full";
      $color="#FF4040";
}
    else
    {
      $state="Busy";
        $color="#FF6A6A";
    }
    if(($_GET['admin']==1)&&($_GET['yes']==1))
    {//slette
      $felt="<td  style='padding:5px;'><a href='?admin=1&yes=1&slettid=$id'>Slett rom</a></td>";
    } else $felt="";
    
   	$table.="<tr style='background-color:$color;margin:5px;'>
   	<td  style='padding:5px;'>$roomnr</td> <td  style='padding:5px;'>$type</td>  <td  style='padding:5px;'>$counter</td> <td  style='padding:5px;'>$capacity</td> <td  style='padding:5px;'>$state</td> $felt
   	</tr>";

	}//loop end
	  	$table.="</tbody></table>";
	///

	echo $table;


?>
