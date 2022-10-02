<?php
require './vendor/autoload.php';

$redis =new Predis\Client();
//$redis->set('name', 'Thiagarajan');
//echo $redis->get('name');

$cachedEntry = $redis->get('registration');
$t0 = 0;
$t1 = 0;
if($cachedEntry){
    echo "Data From the redis Cache <br>";
    $t0 = microtime(true)*1000;
    echo $cachedEntry;
    $t1 = microtime(true)*1000;
    echo "Time Taken: ". round($t1-$t0,4);
    exit();
}
else{
    $t0 = microtime(true)*1000;
    $conn=new mysqli('localhost:3306', 'root', '', 'college');
    $sql = "Select UName, email from registration;";
    $result = $conn->query($sql);
    echo "Data From the Database MYSQL <br>";
    $temp ='';
    while($row = $result->fetch_assoc()){
        echo $row['UName'] . '<br>';
        echo $row['email'];
        $temp .= $row['UName'] . '   ' . $row['email'] . '<br>';
    }
    $t1 = microtime(true)*1000;
    echo "<br> Time Taken: ". round($t1-$t0,4);

    $redis->set('registration', $temp);
    $redis->expire('registration',10);
    exit();
}
?>