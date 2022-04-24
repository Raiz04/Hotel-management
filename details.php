<?php

include('config/db_connect.php');
session_start();

$sql = "SELECT services,check_in_date,check_out_date FROM customer_choice WHERE id=" . $_SESSION['id'];
$result = mysqli_query($conn, $sql);
$roomDetails = mysqli_fetch_all($result, MYSQLI_ASSOC);

$checkInDate = explode('T', $roomDetails[0]['check_in_date']);
$checkOutDate = explode('T', $roomDetails[0]['check_out_date']);

$totalBill = $_SESSION['totalBill'];

if (isset($_POST['delete'])) {

	$id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);

	$sql = "UPDATE customer_choice SET availability = 1, services ='', email = '', created_at = '', cust_id = '', check_in_date='', check_out_date='' WHERE id = $id_to_delete ";

	if (mysqli_query($conn, $sql)) {
		header('Location: homePage.php');
	} else {
		echo 'query error: ' . mysqli_error($conn);
	}
}

if (isset($_GET['id'])) {

	// escape sql chars
	$id = mysqli_real_escape_string($conn, $_GET['id']);

	// make sql
	$sql = "SELECT * FROM customer_choice WHERE id =" . $_SESSION['id'];

	// get the query result
	$result = mysqli_query($conn, $sql);

	// fetch result in array format
	$room = mysqli_fetch_assoc($result);

	mysqli_free_result($result);
	mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html>

<?php include('templates/header.php'); ?>

<div class="container center">
	<?php if ($room) : ?>
		<h4><?php echo $room['room_number']; ?></h4>
		<p>Booked as <?php echo $room['email']; ?></p>
		<p><?php echo "Booked on " . date($room['created_at']); ?></p>



		<h5>Services ordered:</h5>
		<p><?php
			foreach (explode(',', $room['services']) as $ing) {
				echo $ing . '<br>';
			}
			?></p>

		<p>Check In: </p><?php echo $checkInDate[0] . " " . $checkInDate[1] ?><br>

		<p>Check Out: </p><?php echo $checkOutDate[0] . " " . $checkOutDate[1] ?><br>


		<?php
		echo '<br>' . "Total bill for your stay is: &#8377 " . $totalBill;
		?>
		<hr>
		<div style="display: grid; grid-template-columns: 50% 50%">
			<div>
				<?php echo "<br> Number of breakfasts :"; ?>
				<?php echo "<br> Number of lunchs :"; ?>
				<?php echo "<br> Number of dinners :"; ?>
				<?php echo "<br> Stay Duration :"; ?>
			</div>
			<div>
				<?php echo "<br>" . $_SESSION['NoOfBreakfast']; ?>
				<?php echo "<br>" . $_SESSION['NoOfLunch']; ?>
				<?php echo "<br>" . $_SESSION['NoOfDinner']; ?>
				<?php echo "<br>" . $_SESSION['stayDuration']; ?>
			</div>
		</div>
		<!-- DELETE FORM -->
		<form action="details.php" method="POST">
			<input type="hidden" name="id_to_delete" value="<?php echo $room['id']; ?>">
			<input type="submit" name="delete" value="Check-out" class="btn brand z-depth-0">
		</form>

	<?php else : ?>
		<h5>No such room exists.</h5>
	<?php endif ?>
</div>

<?php include('templates/footer.php'); ?>

</html>