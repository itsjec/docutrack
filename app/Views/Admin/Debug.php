<?php
// Retrieve and decode the form data
$data = unserialize(base64_decode($_GET['data']));

// Display the dumped data
var_dump($data);
