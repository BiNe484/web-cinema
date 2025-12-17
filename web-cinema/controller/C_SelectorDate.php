<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(isset($_POST['selectedDate'])) {
        try {
            $date = DateTime::createFromFormat('d/m', $_POST['selectedDate']);
            $_SESSION['selectDate'] = $date->format('Y-m-d');
        } catch (Exception $e) {
            // Handle invalid date format gracefully (optional)
            return null; // Or throw an exception if needed
        }
    }
?>