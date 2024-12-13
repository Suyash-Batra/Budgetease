<?php
include("connection.php");
session_start();

if (!isset($_SESSION['email'])) {
    die("User not logged in.");
}

$email = $_SESSION['email'];

// Query to fetch Budget, Date, Category, and Amount
$sql = "SELECT Budget, Date, Category, Amount FROM transaction WHERE Email = '$email'";
$result = mysqli_query($con, $sql);

if (!$result) {
    die("Error fetching data: " . mysqli_error($con));
}

$budget = 0;
$totalExpenses = 0;
$totalIncome = 0;

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $budget = $row['Budget'] ?? 0;
    $amounts = json_decode($row['Amount'], true) ?? [];

    foreach ($amounts as $amount) {
        if ($amount < 0) {
            $totalExpenses += abs($amount);
        } else {
            $totalIncome += $amount;
        }
    }

    $balance = $budget + $totalIncome - $totalExpenses;
} else {
    $balance = 0;
}
$budget = max(0, $budget);
$totalIncome = max(0, $totalIncome);
$totalExpenses = max(0, $totalExpenses);
$balance = max(0, $budget + $totalIncome - $totalExpenses);

$imagePath = 'C:/xampp/htdocs/budgetease/pie_chart.png';
if (file_exists($imagePath)) {
    unlink($imagePath);
}

$command = escapeshellcmd("python C:/xampp/htdocs/budgetease/generate_pie_chart.py $totalIncome $totalExpenses $balance");
$output = shell_exec($command);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | BudgetEase</title>
    <link rel="stylesheet" href="css/grid.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" href="Images/x.png">
    <style>
        #content_graph {
            display: flex;
            justify-content: space-evenly;
        }

        #chart img {
            margin-top: 10%;
            height: 400px;
            width: 500px;
        }
    </style>
</head>
<body>
    <div id="container">
        <div id="nav_column">
            <center>
                <div id="intro_icon"><img src="Images/x.png" height="90px" /></div>
                <a href="index.php" class="navitems"><div class="navitem"><i class="fa-solid fa-house"></i> Home</div></a>
                <a href="dashboard.php" class="navitems"><div class="navitem_active"><i class="fa-solid fa-chart-pie"></i> Dashboard</div></a>
                <a href="transaction.php" class="navitems"><div class="navitem"><i class="fa-solid fa-right-left"></i> Transaction</div></a>
                <a href="history.php" class="navitems"><div class="navitem"><i class="fa-solid fa-clock-rotate-left"></i> History</div></a>
                <a href="profile.php" class="navitems"><div class="navitem"><i class="fa-solid fa-circle-user"></i> Profile</div></a>
                <form method="post"><a href="signup.php" class="navout"><button class="navlog" name="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</button></a></form>
            </center>
        </div>
        <div id="content_box">
            <div id="content_intro">
                <div id="welcome">Welcome <?php echo $_SESSION['name']; ?>!!!</div>
            </div>
            <div id="content_graph">
                <div id="graph">
                    <div class="stats" style="color:#B06647;">
                        <div class="bar"><img src="Images/budget.png" height="50px" width="50px" /></div>
                        <div class="info">
                            <div class="stats_name">Budget</div>
                            <div class="money"><?php echo number_format($budget, 2); ?> Rs.</div>
                        </div>
                    </div>
                    <div class="stats" style="color:red;">
                        <div class="bar"><img src="Images/expense.png" height="50px" width="50px" /></div>
                        <div class="info">
                            <div class="stats_name">Expense</div>
                            <div class="money"><?php echo number_format($totalExpenses, 2); ?> Rs.</div>
                        </div>
                    </div>
                    <div class="stats" style="color:green;">
                        <div class="bar"><img src="Images/income.png" height="50px" width="50px" /></div>
                        <div class="info">
                            <div class="stats_name">Income</div>
                            <div class="money"><?php echo number_format($totalIncome, 2); ?> Rs.</div>
                        </div>
                    </div>
                    <div class="stats" style="color:#FFB125;">
                        <div class="bar"><img src="Images/savings.png" height="50px" width="50px" /></div>
                        <div class="info">
                            <div class="stats_name">Balance</div>
                            <div class="money"><?php echo number_format($balance, 2); ?> Rs.</div>
                        </div>
                    </div>  
                </div>
                <div id="chart">
                    <img src="pie_chart.png" alt="Financial Overview Pie Chart" />
                </div>
            </div>
        </div>
    </div>
</body>
</html>
        