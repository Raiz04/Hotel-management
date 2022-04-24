<?php

include("./config/db_connect.php");

session_start();
$cust_id = $_SESSION['cust_id'];

$sql = "SELECT name FROM customer_list WHERE id= \"$cust_id\"";
$result = mysqli_query($conn, $sql);
$customer_details = mysqli_fetch_all($result, MYSQLI_ASSOC);

//fetch data from all rooms
$sql = "SELECT * FROM customer_choice WHERE cust_id= \"$cust_id\" ORDER BY created_at";
//make query and get result
$result = mysqli_query($conn, $sql);
// fetch the resulting row as an array
$rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <style type="text/css">
        .brand {
            background: #cbb09c !important;
        }

        .brand-text {
            color: #cbb09c !important;
        }

        .room {

            width: 30%;
            margin: 15% auto -30px;
            display: block;
            position: relative;
            top: -50px;
        }

        /* width */
        ::-webkit-scrollbar {
            width: 10px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    <title>Leisure Inn</title>

</head>

<body class="grey lighten-1">
    <nav class="white z-depth-0">
        <div class="container">
            <a href="homePage.php" class="band-logo brand-text">Welcome, <?php echo $customer_details[0]['name'] ?></a>
            <ul id="nav-mobile" class="right hide-on-small-and-down">
                <li>
                    <a href="add.php" class="btn brand z-dedth-0">Add a Room</a>
                </li>
            </ul>
        </div>
    </nav>

    <h4 class="center grey-text">BOOKED ROOMS</h4>

    <div class="container">
        <div class="row">

            <?php foreach ($rooms as $room) : ?>
                <?php if ($room['availability'] == 0) { ?>
                    <div class="col s6 md3">
                        <div class="card z-depth-0">
                            <img src="./img/house.svg" alt="room gon... :(" class="room">
                            <div class="card-content center">
                                <h6><?php echo htmlspecialchars($room['room_number']) ?></h6>
                                <ul class="grey-text">
                                    <?php foreach (explode(',', $room['services']) as $ing) : ?>
                                        <li>
                                            <?php echo htmlspecialchars($ing) ?>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                            <div class="card-action right-align">
                                <a href="billCalculator.php?id=<?php echo $room['id'] ?>" class="brand-text">more info</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php endforeach; ?>

        </div>
    </div>

    <?php include('templates/footer.php'); ?>

</html>