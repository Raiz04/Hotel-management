<?php

include("./config/db_connect.php");

session_start();
$sql = "SELECT * FROM room_service";
$result = mysqli_query($conn, $sql);
$teams = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
            <a href="admin.php" class="band-logo brand-text">Welcome, Admin</a>
        </div>
    </nav>

    <h4 class="center grey-text">Room service schedule</h4>

    <div class="container">
        <div class="row">

            <?php foreach ($teams as $team) : ?>
                <?php if ($team['Team_id'] == 0) {
                    continue;
                } ?>
                <div class="col s6 md3">
                    <div class="card z-depth-0">
                        <img src="./img/house.svg" alt="room gon... :(" class="room">
                        <div class="card-content center">
                            <h6><?php echo "Team number : " . htmlspecialchars($team['Team_id']) ?></h6><br>
                            <h6><?php echo "Room number : " . htmlspecialchars($team['Assigned_room']) ?></h6><br>
                            <ul class="grey-text">
                                <?php foreach (explode('T', $team['Assigned_time']) as $ing) : ?>
                                    <li>
                                        <?php echo htmlspecialchars($ing) ?>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <?php include('templates/footer.php'); ?>

</html>