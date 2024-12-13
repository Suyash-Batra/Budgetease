<?php
include("connection.php");
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: signup.php");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
      integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    
    <link rel="icon" href="Images/x.png" />
    <title>BudgetEase | Homepage</title>
  </head>
  <body>
    <div id="inner_container">
      <div id="nav">
        <a href="index.php">
          <div id="brand">
            <div id="brand_name1">Budget</div>
            <div id="brand_name2">Ease</div>
          </div></a
        >
        <div id="nav_button">
          <a href="history.php"><div class="nav_buttons">History</div></a>
          <a href="about.php"><div class="nav_buttons">About</div></a>
          <form action="excel.php" method="post">
            <div class="nav_signup">
              <button id="signup">Download</button>
            </div></form>
        </div>
      </div>
      <div id="content">
        <div id="image">
          <img src="Images/b.png" height="400px" width="300px" />
        </div>
        <div id="main_header">
          Personal Budget Planner
          <div id="sub_head">
            "Financial freedom is not a matter of how much you earn, but how
            well you manage what you have."
            <center>
              <a href="dashboard.php"> <button id="start">Start</button></a>
            </center>
            <div id="icon">
              <div class="icons">
                <i class="fa-solid fa-money-bill-transfer"></i><br />
                <div class="icon_text">Tracking Expenses and Incomes</div>
              </div>
              <div class="icons">
                <i class="fa-solid fa-piggy-bank"></i><br />
                <div class="icon_text">Increase Savings and Investments</div>
              </div>
              <div class="icons">
                <i class="fa-solid fa-hand-holding-dollar"></i><br />
                <div class="icon_text">Budget and Bill Organising</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
