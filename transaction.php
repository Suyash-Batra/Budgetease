<?php
include("connection.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['email'];
    
    if (isset($_POST['delete'])) {
        $deleteId = intval($_POST['delete_id']);

        // Fetch existing records
        $query = "SELECT Date, Category, Amount FROM transaction WHERE Email = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result) die("Query failed: " . mysqli_error($con));

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $dates = json_decode($row['Date'], true) ?? [];
            $categories = json_decode($row['Category'], true) ?? [];
            $amounts = json_decode($row['Amount'], true) ?? [];

            // Remove the transaction with the specific ID
            if (isset($dates[$deleteId])) {
                unset($dates[$deleteId]);
                unset($categories[$deleteId]);
                unset($amounts[$deleteId]);

                // Reindex arrays
                $dates = array_values($dates);
                $categories = array_values($categories);
                $amounts = array_values($amounts);

                // Update database
                $dateJson = json_encode($dates);
                $categoryJson = json_encode($categories);
                $amountJson = json_encode($amounts);

                $query = "UPDATE transaction SET Date = ?, Category = ?, Amount = ? WHERE Email = ?";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt, 'ssss', $dateJson, $categoryJson, $amountJson, $email);

                if (!mysqli_stmt_execute($stmt)) die("Error updating data: " . mysqli_stmt_error($stmt));
            }
        }
    } else {
        $newDates = $_POST['date'];
        $newCategories = (array) $_POST['category'];
        $newAmounts = (array) $_POST['amount'];

        // Format dates
        $formattedDates = is_array($newDates) ? array_map(fn($date) => date('Y-m-d', strtotime($date)), $newDates) : [date('Y-m-d', strtotime($newDates))];
        $formattedDates = array_filter($formattedDates, fn($date) => $date !== '1970-01-01');

        $query = "SELECT Date, Category, Amount FROM transaction WHERE Email = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result) die("Query failed: " . mysqli_error($con));

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $dates = array_merge(json_decode($row['Date'], true) ?? [], $formattedDates);
            $categories = array_merge(json_decode($row['Category'], true) ?? [], $newCategories);
            $amounts = array_merge(json_decode($row['Amount'], true) ?? [], $newAmounts);
        } else {
            $dates = $formattedDates;
            $categories = $newCategories;
            $amounts = $newAmounts;
        }

        $dateJson = json_encode($dates);
        $categoryJson = json_encode($categories);
        $amountJson = json_encode($amounts);

        $query = "INSERT INTO transaction (Email, Date, Category, Amount) VALUES (?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE Date = VALUES(Date), Category = VALUES(Category), Amount = VALUES(Amount)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'ssss', $email, $dateJson, $categoryJson, $amountJson);

        if (!mysqli_stmt_execute($stmt)) die("Error inserting data: " . mysqli_stmt_error($stmt));
    }

    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch the last 3 transactions
$query = "SELECT Date, Category, Amount FROM transaction WHERE Email = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 's', $_SESSION['email']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) die("Error fetching data: " . mysqli_error($con));

$recentTransactions = '';
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $dates = json_decode($row['Date'], true) ?? [];
    $categories = json_decode($row['Category'], true) ?? [];
    $amounts = json_decode($row['Amount'], true) ?? [];

    $totalTransactions = count($dates);
    $startIndex = max(0, $totalTransactions - 3);

    for ($i = $startIndex; $i < $totalTransactions; $i++) {
        $amountClass = $amounts[$i] < 0 ? 'num_loss' : 'num_profit';
        $templateId = $amounts[$i] < 0 ? 'template' : 'template_profit';
        $deleteClass = $amounts[$i] < 0 ? 'delete-btn' : 'delete-btn_green';

        $recentTransactions .= "
        <div id='$templateId'>
            <div class='record'>" . date('d/m/Y', strtotime($dates[$i])) . "</div>
            <div class='type'>" . htmlspecialchars($categories[$i]) . "</div>
            <div class='$amountClass'>" . htmlspecialchars($amounts[$i]) . "Rs</div>
            <div class='del'>
                <form method='post' style='display:inline;'>
                    <input type='hidden' name='delete_id' value='$i'>
                    <button type='submit' name='delete' class='$deleteClass'>
                        <i class='fa-solid fa-trash-can'></i>
                    </button>
                </form>
            </div>
        </div>";
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transaction | BudgetEase</title>
    <link rel="stylesheet" href="css/grid.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="icon" href="Images/x.png" />
    <style>
      #content_graph { margin: 0px 0px 20px 0px }
      #content_intro { display: flex; justify-content: space-evenly; }
      #template, #template_profit { display: flex; justify-content: space-between; padding: 10px; }
      .record, .type, .num_loss, .num_profit { margin: 0 10px; }
      .del i { cursor: pointer; }
    </style>
</head>
<body>
    <div id="container">
        <div id="nav_column">
            <center>
                <div id="intro_icon"><img src="Images/x.png" height="90px" /></div>
                <a href="index.php" class="navitems"><div class="navitem"><i class="fa-solid fa-house"></i> Home</div></a>
                <a href="dashboard.php" class="navitems"><div class="navitem"><i class="fa-solid fa-chart-pie"></i> Dashboard</div></a>
                <a href="#" class="navitems"><div class="navitem_active"><i class="fa-solid fa-right-left"></i> Transaction</div></a>
                <a href="history.php" class="navitems"><div class="navitem"><i class="fa-solid fa-clock-rotate-left"></i> History</div></a>
                <a href="profile.php" class="navitems"><div class="navitem"><i class="fa-solid fa-circle-user"></i> Profile</div></a>
                <form method="post">
                    <a href="#" class="navout"><button class="navlog" name="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</button></a>
                </form>
            </center>
        </div>
        <div id="content_box">
            <div id="content_graph">
                <div id="title">
                    <div class="titles">Date</div>
                    <div class="titles">Category</div>
                    <div class="titles">Amount</div>
                    <div class="titles">Delete</div>
                </div>
                <br />
                <hr />
                <center>
                    <?php echo $recentTransactions; ?>
                    <a href="history.php"><div id="view"><button id="view_all">View All</button></div></a>
                </center>
            </div>
            <form method="post">
                <div id="content_intro">
                    <div id="date"><input type="date" id="amounts" name="date"></div>
                    <div id="categorys"><input type="text" id="amounts" placeholder="Category" name="category"></div>
                    <div id="amount"><input type="number" placeholder="Amount(+/-)" id="amounts" name="amount"></div>
                    <div id="submit"><input type="submit" value="Add" id="add" /></div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
