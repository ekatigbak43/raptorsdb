<?php
require('connect.php');
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toronto Raptors Player Stats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('nav.php'); ?>

    <div class="container my-5">
        <h1 class="text-center">Toronto Raptors Player Stats</h1>
        <form id="search-form" class="row g-3 mb-4">
            <div class="col-md-6">
                <input type="text" id="player-name" class="form-control" placeholder="Search by Player Name (ex. Scottie, Carter, Chris Bosh)">
            </div>
            <div class="col-md-4">
                <input type="number" id="season" class="form-control" placeholder="Search by Season (from 1996 to 2023)">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>
        <div id="results"></div>
    </div>
    <script src="script.js"></script>

    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>        
</body>
</html>
