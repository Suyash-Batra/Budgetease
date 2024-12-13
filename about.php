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
    <style>#content {
        display: block;
        height: fit-content;
    
    }

    #content b {
        font-size: 25px;
    }

    #main_header,#sub_head {
        margin-left: 20px;
    }

    #inner_container {
        height: fit-content;
    }
       
    </style>
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
       <div id="main_header">About us</div>
       <div id="sub_head"><b>
Welcome to BudgetEase!</b>
<br><br>
At BudgetEase, our mission is to empower you to take control of your finances with ease and confidence. We understand that managing your budget can be a challenging task, and that’s why we’ve designed our platform to simplify the process and provide you with powerful insights.
<br><br>
<b>What We Do</b>
<br><br>
BudgetEase is an innovative prototype designed to help you track and manage your budget effectively. Our platform allows you to:
<br>
- <b>Track Expenses and Income:</b> Easily monitor your spending and earnings to stay on top of your financial health.<br><br>
- <b>Visualize Your Data:</b> Gain valuable insights through dynamic visualizations like pie charts, making it easy to understand your financial patterns at a glance.<br><br>
- <b>Store and Manage Transactions:</b> Our robust database securely stores all your transactions, providing you with a comprehensive record.<br><br>
- <b>Generate Detailed Reports:</b> Export your transaction data into Excel for further analysis and record-keeping, helping you make informed financial decisions.<br><br>

<b>Our Vision</b>
<br><br>
We believe that everyone deserves a simple and effective way to manage their finances. BudgetEase is designed with user experience in mind, offering an intuitive interface that makes financial management accessible to all.
<br><br>
<b>Our Team</b>
<br><br>
We are a dedicated team of developers and financial enthusiasts committed to creating tools that make budgeting easier and more transparent. Our goal is to continuously improve BudgetEase based on user feedback and evolving financial needs.
<br><br>
Thank you for choosing BudgetEase. We’re excited to support you on your journey to financial wellness!
<br><br>
For any questions or feedback, feel free to reach out to us at [contact@budgetease.com](mailto:contact@budgetease.com).
<br><br>

Feel free to adjust any details or add any additional information that reflects your team and vision!</div>
    </div>
  </body>
</html>
