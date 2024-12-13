<?php
session_start();
include("connection.php");

// Function to generate HTML for each record
function component($date, $category, $amount, $index) {
    $amount = (float)$amount;
    $amountClass = $amount < 0 ? 'num_loss' : 'num_profit';
    $buttonClass = $amount < 0 ? 'delete-btn' : 'delete-btn_green';
    $templateId = $amount < 0 ? 'template' : 'template_profit';

    return "
    <div id='$templateId'>
        <div class='record'>" . date('d/m/Y', strtotime($date)) . "</div>
        <div class='type'>" . htmlspecialchars($category) . "</div>
        <div class='$amountClass'>" . htmlspecialchars($amount) . "Rs</div>
        <div class='del'>
            <form method='post' style='display:inline;'>
                <input type='hidden' name='delete_id' value='$index'>
                <button type='submit' name='delete' class='$buttonClass'>
                    <i class='fa-solid fa-trash-can'></i>
                </button>
            </form>
        </div>
    </div>";
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $deleteId = $_POST['delete_id'];
    $email = $_SESSION['email'];

    $sql = "SELECT Date, Category, Amount FROM transaction WHERE Email = '$email'";
    $result = mysqli_query($con, $sql);

    if (!$result) die("Query failed: " . mysqli_error($con));

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $dates = json_decode($row['Date'], true) ?? [];
        $categories = json_decode($row['Category'], true) ?? [];
        $amounts = json_decode($row['Amount'], true) ?? [];

        // Remove the transaction with the specific ID
        unset($dates[$deleteId], $categories[$deleteId], $amounts[$deleteId]);

        // Reindex arrays
        $dates = array_values($dates);
        $categories = array_values($categories);
        $amounts = array_values($amounts);

        // Update database
        $sql = "UPDATE transaction SET 
                Date = '" . mysqli_real_escape_string($con, json_encode($dates)) . "', 
                Category = '" . mysqli_real_escape_string($con, json_encode($categories)) . "', 
                Amount = '" . mysqli_real_escape_string($con, json_encode($amounts)) . "' 
                WHERE Email = '$email'";

        if (!mysqli_query($con, $sql)) die("Error updating data: " . mysqli_error($con));
    }
}

// Fetch transaction data again to display updated results
$email = $_SESSION['email'];
$sql = "SELECT Date, Category, Amount FROM transaction WHERE Email = '$email'";
$result = mysqli_query($con, $sql);

$transactions = '';
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $dates = json_decode($row['Date'], true) ?? [];
    $categories = json_decode($row['Category'], true) ?? [];
    $amounts = json_decode($row['Amount'], true) ?? [];

    // Combine data into a single array
    $combined = [];
    foreach ($dates as $index => $date) {
        $combined[] = [
            'date' => $date,
            'category' => $categories[$index] ?? '',
            'amount' => $amounts[$index] ?? '',
            'index' => $index
        ];
    }

    // Sort by date in descending order
    usort($combined, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    // Generate HTML for each sorted record
    foreach ($combined as $record) {
        $transactions .= component($record['date'], $record['category'], $record['amount'], $record['index']);
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>History | BudgetEase</title>
    <link rel="stylesheet" href="css/grid.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="icon" href="Images/x.png" />
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    <style>
      #content_graph {
        width: 1000px;
        padding-bottom: 20px;
        height: auto;
      }
      #container {
        display: flex;
        justify-content: center;
      }
      #back {
        font-size: 40px;
        margin: 20px 20px 0 0;
        color: #d75c37;
        height: 50px;
        width: 50px;
        text-align: center;
        padding-top: 5px;
        border-radius: 50%;
        background-color: #f7e6c9;
        border: 0;
      }
      #back:hover {
        background-color: #ebdbc0;
        transition: 0.5s;
      }
      .record, .type, .num_loss, .num_profit, .del {
        padding: 25px 50px 20px 50px;
      }
      .record {
        font-size: 25px;
        width: 250px;
      }
      #title {
        width: 1000px;
      }
    </style>
</head>
<body>
    <div id="container">
      <button id="back" onclick="goBack()"><i class="fa-solid fa-angle-left"></i></button>
      <center>
        <div id="content_graph">
          <div id="title">
            <div class="titles">Date</div>
            <div class="titles">Category</div>
            <div class="titles">Amount</div>
            <div class="titles">Delete</div>
          </div>
          <br />
          <hr />
          <?php echo $transactions; ?>
        </div>
      </center>
    </div>
</body>
</html>
