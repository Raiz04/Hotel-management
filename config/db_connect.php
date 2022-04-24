<?php
    //connect to database
    $conn = mysqli_connect('localhost','Raiz','wmEz6yJ0WV47o]]q','motel_project');

    //check connection
    if(!$conn){
        echo "Connection error: " . mysqli_connect_error();
    }
?>