<?php   
 $conn = mysqli_connect('localhost', 'root', '1234', 'test2'); //准备SQL语句
 mysqli_query($conn, 'set names utf8');//进行编码统一