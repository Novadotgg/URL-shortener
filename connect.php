<?php
$servername="localhost";
$username="root";
$password="";
$database_name="short_url_data";

$conn=mysqli_connect($server_name,$username,$password,$database_name);
if(!conn){
echo"connection failed";
exit();
}
if(isset($_POST['submit'])){
$orig_url=$_POST['input_url'];
$rand=substr(md5(microtime()), rand(0,26),3);
mysqli_query($conn,"insert into 'short_url_data'('orig_url','unique_exten') values('orig_url','')");
if(mysqli_query($conn,$sql_query)){
echo "Data entered successfully";}
else{
echo "Error";
}
mysqli_close($conn);

} 
