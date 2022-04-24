<?php
include("./Config/db_connect.php");

session_start();
$cust_id = $_SESSION['cust_id'];

$email = '';
$room_number = '';
$services = '';
$errors = array('email' => '', 'room_number' => '', 'services' => '', 'Dates' => '');

$sql = "SELECT Room_number FROM customer_choice WHERE availability=1 ORDER BY room_number";
$result = mysqli_query($conn, $sql);
$available_rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql = "SELECT * FROM services_list";
$result = mysqli_query($conn, $sql);
$services = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql = "SELECT * FROM room_service";
$result = mysqli_query($conn, $sql);
$room_service = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (isset($_POST['submit'])) {
    if (empty($_POST['email'])) {
        $errors['email'] = 'An email is required';
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'email must be valid email address';
        }
    }

    if (empty($_POST['room_number'])) {
        $errors['room_number'] = 'A Room number is required';
    } else {
        $room_number = $_POST['room_number'];
        if (!preg_match('/^[0-9]{3,3}/', $room_number)) {
            $errors['room_number'] = 'Room number must be 3 numbers only';
        }
    }

    if (empty($_POST['services'])) {
        $errors['services'] = '';
    } else {
        $services = $_POST['services'];
        if (!preg_match('/^([a-zzA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/', $services)) {
            $errors['services'] = 'Services must be comma seperated list';
        }
    }

    if (empty($_POST['checkInDate']) || empty($_POST['checkOutDate'])) {
        $errors['Dates'] = "Check in and check out dates are required";
    }

    if (array_filter($errors)) {
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $room_number = mysqli_real_escape_string($conn, $_POST['room_number']);

        $ChosenServices = $_POST['service'];
        $services = "";
        foreach ($ChosenServices as $service) {
            $services = $services . "," . $service;
        }
        $sql = "UPDATE customer_choice SET availability = 0, services = \" $services \" , email = \" $email \", cust_id = \" $cust_id \",check_in_date = \" $_POST[checkInDate] \",check_out_date = \"$_POST[checkOutDate]\"WHERE room_number = $room_number";

        if (mysqli_query($conn, $sql)) {
            // Updating data;
        } else {
            echo "Query error: " . mysqli_error($conn);
        }

        $team_id = ($room_service[0]['Assigned_room'] % 4) + 1;

        $sql = "UPDATE room_service SET Assigned_room = \"$room_number\",Assigned_time = \"$_POST[checkInDate]\" WHERE Team_id = \"$team_id\"";

        if (mysqli_query($conn, $sql)) {
            // Updating data;
        } else {
            echo "Query error: " . mysqli_error($conn);
        }

        $team_id = ($room_service[0]['Assigned_room'] % 4) + 2;

        $sql = "UPDATE room_service SET Assigned_room = \"$room_number\",Assigned_time = \"$_POST[checkOutDate]\" WHERE Team_id = \"$team_id\"";

        if (mysqli_query($conn, $sql)) {
            // Updating data;
        } else {
            echo "Query error: " . mysqli_error($conn);
        }

        $sql = "UPDATE room_service SET Assigned_room = \"$team_id\" WHERE Team_id = 0";

        if (mysqli_query($conn, $sql)) {
            // Updating data;
        } else {
            echo "Query error: " . mysqli_error($conn);
        }

        header('Location: homePage.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include('templates/header.php'); ?>

<div class="container grey-text">
    <h4 class="center">Add a room</h4>
    <form action="add.php" class="white" method="POST">
        <label for="">Your email</label>
        <input type="text" name="email" value="<?php echo htmlspecialchars($email) ?>">
        <div class="red-text"><?php echo $errors['email'] ?></div>

        <label for="">Room number</label>

        <br>

        <div style="margin:10%; height: 100px; overflow:scroll; overflow-x: hidden;">

            <?php if (sizeof($available_rooms) == 0) { ?>
                <p class="container center red-text">Sorry, no rooms available</p>

            <?php } else { ?>
                <?php foreach ($available_rooms as $room) { ?>

                    <div class="container">
                        <label>
                            <input type="radio" name="room_number" value=<?php echo $room['Room_number']; ?>>
                            <span><?php echo $room['Room_number']; ?></span>
                        </label>
                    </div>

                <?php } ?>
            <?php } ?>

        </div>

        <div class="red-text"><?php echo $errors['room_number'] ?></div>

        <label for="">Services:</label>
        <div style="display: grid; grid-template-columns: 70% 30%">
            <div style="margin: 5%;">
                <?php foreach ($services as $service) { ?>

                    <div class="container">
                        <label>
                            <input type="checkbox" name="service[]" value=<?php echo $service['service_name']; ?>>
                            <span><?php echo $service['service_name']; ?></span>
                        </label>
                    </div>

                <?php } ?>
            </div>
            <div style="margin: 5%;">
                <?php foreach ($services as $service) { ?>

                    <div class="container">
                        <label>
                            <p>
                                <span><?php echo "&#8377" . ' ' . $service['service_price']; ?></span>
                        </label>
                    </div>
                    </p>

                <?php } ?>
            </div>
        </div>
        <label>Check-in Date</label>
        <input type="datetime-local" name='checkInDate'>
        <br>
        <label>Check-out Date</label>
        <input type="datetime-local" name='checkOutDate'>
        <div class="red-text"><?php echo $errors['Dates'] ?></div>

        <div class="center">
            <input type="submit" name="submit" value="submit" class="btn brand z-depth-0">
        </div>
    </form>
</div>

<?php include('templates/footer.php'); ?>

</html>