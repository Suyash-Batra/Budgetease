<?php
include("connection.php");
session_start();

// Check if budget is posted
if (isset($_POST['budget'])) {
    $budget = $_POST['budget'];
    $email = $_SESSION['email'];

    // Check if the user already has a record in the transaction table
    $query = "SELECT * FROM transaction WHERE Email = '$email'";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Error checking existing data: " . mysqli_error($con));
    }

    if (mysqli_num_rows($result) > 0) {
        // Update existing record
        $query = "UPDATE transaction SET Budget = ? WHERE Email = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'ds', $budget, $email);
    } else {
        // Insert new record
        $query = "INSERT INTO transaction (Email, Budget) VALUES (?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'sd', $email, $budget);
    }

    if (!mysqli_stmt_execute($stmt)) {
        die("Error executing query: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile | BudgetEase</title>
    <link rel="stylesheet" href="css/grid.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
      integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="icon" href="Images/x.png" />
    <style>
      #content_graph {
        margin-top: 0px;
        margin-bottom: 20px;
      }
      #content_intro {
        display: flex;
        justify-content: space-evenly;
        flex-direction: row;
      }
    </style>
  </head>
  <body>
    <div id="container">
      <div id="nav_column">
        <center>
          <div id="intro_icon"><img src="Images/x.png" height="90px" /></div>
          <a href="index.php" class="navitems">
            <div class="navitem"><i class="fa-solid fa-house"></i> Home</div></a
          >
          <a href="dashboard.php" class="navitems">
            <div class="navitem">
              <i class="fa-solid fa-chart-pie"></i> Dashboard
            </div></a
          >
          <a href="transaction.php" class="navitems">
            <div class="navitem">
              <i class="fa-solid fa-right-left"></i> Transaction
            </div></a
          ><a href="history.php" class="navitems">
            <div class="navitem">
              <i class="fa-solid fa-clock-rotate-left"></i> History
            </div></a
          >
          <a href="profile.php" class="navitems">
            <div class="navitem_active">
              <i class="fa-solid fa-circle-user"></i> Profile
            </div></a
          >
          <form method="post">
            <a href="#" class="navout">
              <button class="navlog" name="logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
              </button></a
            >
          </form>
        </center>
      </div>
      <div id="content_box">
        <div id="content_graph">
          <br /><br />
          <div class="unedit">
            <b>Email:</b><br /><?php echo $_SESSION['email']; ?>
            <i class="fa-solid fa-circle-check" style="color: #63e6be"></i>
          </div>
          <div class="unedit">
            <b>Username:</b><br /><?php echo $_SESSION['name']; ?>
            <i class="fa-solid fa-circle-check" style="color: #63e6be"></i>
          </div>
          <form method="post">
            <div class="details">
              <b>Budget:</b><br />
              <input
                type="number"
                class="edit"
                placeholder="Edit Budget"
                name="budget"
              /><button class="update">Update</button>
            </div>
          </form>
<form method="post">
            <div class="details">
              <b>Phone:</b><br />
              <input
                type="number"
                class="edit"
                placeholder="Edit Phone"
              /><button class="update">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
