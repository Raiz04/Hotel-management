<?php
include("./Config/db_connect.php");

$name = '';
$password = '';
$errors = array('name' => '', 'password' => '');

if (isset($_POST['submit'])) {

    if (empty($_POST['name'])) {
        $errors['name'] = 'Please enter a name';
    } else {
        $name = $_POST['name'];
    }

    if (empty($_POST['password'])) {
        $errors['password'] = 'Please enter a password';
    } else {
        $password = $_POST['password'];
        if (preg_match('/\\s/', $password)) {
            $errors['password'] = 'Password cannot contain space';
        }
        if (!preg_match('/^[\w\d]{7,}$/', $password)) {
            $errors['password'] = 'Password must be at least 7 charecters long';
        }
    }
    if (!(array_filter($errors))) {
        $sql = "INSERT INTO `customer_list`(`name`, `password`) VALUES (\"$name\",\"$password\")";
        if(mysqli_query($conn, $sql)){
            // Updating data;
        } else {
            echo "Query error: " . mysqli_error($conn);
        }
    }

    if (!(array_filter($errors))) {
        header("Location: loginPage.php");
    }
}

?>

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
            margin-left: auto;
            margin-right: auto;
        }

        form {
            max-width: 60%;
            margin: 20px auto;
            padding: 5%;
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
            <a href="#" class="band-logo brand-text">Leisure Inn</a>
        </div>
    </nav>

    <div class="container grey-text">
        <h4 class="center">Sign up here</h4>
        <form action="signup.php" class="white" method="POST">

            <label for="">Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name) ?>">
            <div class="red-text"><?php echo $errors['name'] ?></div>

            <label for="">Password</label>
            <input type="password" name="password" value="<?php echo htmlspecialchars($password) ?>">
            <div class="red-text"><?php echo $errors['password'] ?></div>

    </div>

    <div class="center">
        <input type="submit" name="submit" value="Sign UP" class="btn brand z-depth-0">
    </div>

        </form>
    </div>

    <footer class="section">
        <div class="center grey-text">&copy; Copyright 2021 VIT chennai</div>
    </footer>
</body>