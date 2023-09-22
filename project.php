
<html>

<head>
<title>URL Shortener</title>
</head>
<body>
<center>
<div style="padding":30px;">
<h1>Project on URL Shortening</h1>
<form method="post"action="#">



<input type="text" name="input_url" required style="width:500px;padding:10px;font-size:1.5em;">




<input type="submit" name="submit " value="shorten"
</form>
</center>
</body>
</html>
<?php


if(isset($_POST['submit'])){

$conn=mysqli_connect('localhost','root','','short_url_data'); 
if(!conn){
echo "connection error";
exit();
}


$orig_url=$_POST['input_url'];
$rand=substr(md5(microtime()), rand(0,26),3);
mysqli_query($conn,"insert into 'short_url_data'('orig_url','unique_exten') values('orig_url','')"); 


echo "localhost/a?"."$rand";
//echo "<a href=http://localhost/a?"."$rand>localhost/a?$rand</a>";


}
?>




