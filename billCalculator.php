<?php
session_start();
include("./config/db_connect.php");
    $sql = "SELECT services,check_in_date,check_out_date FROM customer_choice WHERE id=".$_GET['id'];
    $result = mysqli_query($conn,$sql);
    $roomDetails=mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    $checkInDate = explode('T',$roomDetails[0]['check_in_date']);
    $checkOutDate = explode('T',$roomDetails[0]['check_out_date']);

    $stayDuration = (strtotime($checkOutDate[0].$checkOutDate[1])-strtotime($checkInDate[0].$checkInDate[1]))/86400;

    $totalBill=0;

    foreach (explode(',', $roomDetails[0]['services']) as $ing){
        
        $sql = "SELECT service_price FROM services_list WHERE service_name="."\"$ing\"";
        $result = mysqli_query($conn,$sql);
        $amount=mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        if (empty($amount)) {
            continue;
        }

        $totalBill+=$amount[0]['service_price'];
    }

    $totalBill*=$stayDuration;
    $NoOfBreakfast=$stayDuration;
    $NoOfLunch=$stayDuration;
    $NoOfDinner=$stayDuration;

    foreach (explode(',', $roomDetails[0]['services']) as $ing){
        
        $sql = "SELECT service_price FROM services_list WHERE service_name="."\"$ing\"";
        $result = mysqli_query($conn,$sql);
        $amount=mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        if (empty($amount)) {
            continue;
        }

        if ($ing=="breakfast") {
            if (strtotime("1-1-1970".$checkInDate[1])>32400) {
                $totalBill-=$amount[0]['service_price'];
                --$NoOfBreakfast;
            } else {
                $totalBill+=$amount[0]['service_price'];
                --$NoOfBreakfast;
            }

            if ($stayDuration>0 || strtotime("1-1-1970".$checkInDate[1])>32400) {
                if (strtotime("1-1-1970".$checkOutDate[1])<32400) {
                    $totalBill-=$amount[0]['service_price'];
                    --$NoOfBreakfast;
                } else {
                    $totalBill+=$amount[0]['service_price'];
                    ++$NoOfBreakfast;
                }
            }
        }

        if($ing=="lunch"){
            if (strtotime("1-1-1970".$checkInDate[1])>54000) {
                $totalBill-=$amount[0]['service_price'];
                --$NoOfLunch;
            } else {
                $totalBill+=$amount[0]['service_price'];
                ++$NoOfLunch;
            }
            
            if ($stayDuration>0 || strtotime("1-1-1970".$checkInDate[1])>54000) {
                if (strtotime("1-1-1970".$checkOutDate[1])<50400) {
                    $totalBill-=$amount[0]['service_price'];
                    --$NoOfLunch;
                } else {
                    $totalBill+=$amount[0]['service_price'];
                    ++$NoOfLunch;
                }
            }
        }

        if($ing=="dinner"){
            if (strtotime("1-1-1970".$checkInDate[1])>79200) {
                $totalBill-=$amount[0]['service_price'];
                --$NoOfDinner;
            } else {
                $totalBill+=$amount[0]['service_price'];
                ++$NoOfDinner;
            }

            if ($stayDuration>0 || strtotime("1-1-1970".$checkInDate[1])>79200) {
                if (strtotime("1-1-1970".$checkOutDate[1])<75600) {
                    $totalBill-=$amount[0]['service_price'];
                    --$NoOfDinner;
                } else {
                    $totalBill+=$amount[0]['service_price'];
                    ++$NoOfDinner;
                }
            }
        }
    }

    if ($stayDuration==0) {
        $stayDuration=1;
    }
    $totalBill+=50*$stayDuration;

$_SESSION['totalBill']=round($totalBill);
$_SESSION['NoOfBreakfast']=round($NoOfBreakfast);
$_SESSION['NoOfLunch']=round($NoOfLunch);
$_SESSION['NoOfDinner']=round($NoOfDinner);
$_SESSION['stayDuration']=round($stayDuration);
$_SESSION['id']=$_GET['id'];

header('Location: details.php?id='.$id);
