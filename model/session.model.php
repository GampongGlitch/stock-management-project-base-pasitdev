<?php

if (!isset($_SESSION)) { session_start(); }
$_SESSION['userid'] = $_POST['id'];
$_SESSION['username'] = $_POST['username'];
echo json_encode(['message' => 'success']);