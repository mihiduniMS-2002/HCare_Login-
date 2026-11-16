<?php
session_start();

// login wela nathnam login page ekata yanna
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$name = htmlspecialchars($_SESSION["full_name"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>H-Care Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="dashboard">

    <!-- sidebar -->
    <aside class="sidebar">
        <h2 class="logo">✚ H-care</h2>

        <button class="register-btn">Register Patient +</button>

        <nav>
            <a href="#">♟ Patients</a>
            <a href="#" class="active">◉ Overview</a>
            <a href="#">📍 Map</a>
            <a href="#">⌂ Departments</a>
            <a href="#">♙ Doctors</a>
            <a href="#">▤ History</a>
            <a href="#">⚙ Settings</a>
        </nav>

        <div class="mobile-app">
            <p>Get mobile app</p>
            <span>▶ Google Play | App Store</span>
        </div>
    </aside>

    <!-- main -->
    <main class="main-content">

        <header class="topbar">
            <input type="text" placeholder="Search...">
            <div class="profile">
                🔔 &nbsp; 👤 <?= $name ?> &nbsp;|&nbsp;
                <a href="logout.php" class="logout-link">Logout</a>
            </div>
        </header>

        <h2>Dashboard Overview</h2>
        <p class="subtitle">Welcome back, <?= $name ?>!</p>

        <!-- cards -->
        <section class="cards">
            <div class="card">
                <div class="card-icon purple">▣</div>
                <div><h3>3,256</h3><p>Total Patients</p></div>
            </div>
            <div class="card">
                <div class="card-icon blue">♟</div>
                <div><h3>394</h3><p>Available Staff</p></div>
            </div>
            <div class="card">
                <div class="card-icon orange">▣</div>
                <div><h3>$2,536</h3><p>Avg Treat. Costs</p></div>
            </div>
            <div class="card">
                <div class="card-icon pink">🚑</div>
                <div><h3>38</h3><p>Available Cars</p></div>
            </div>
        </section>

        <!-- charts -->
        <section class="chart-grid">

            <div class="panel">
                <h3>Outpatients vs. Inpatients Trend</h3>
                <div class="bar-chart">
                    <div class="bar-group">
                        <span class="bar green" style="height:35%"></span>
                        <span class="bar purple-bar" style="height:75%"></span>
                        <small>Oct</small>
                    </div>
                    <div class="bar-group">
                        <span class="bar green" style="height:50%"></span>
                        <span class="bar purple-bar" style="height:85%"></span>
                        <small>Nov</small>
                    </div>
                    <div class="bar-group">
                        <span class="bar green" style="height:25%"></span>
                        <span class="bar purple-bar" style="height:100%"></span>
                        <small>Dec</small>
                    </div>
                    <div class="bar-group">
                        <span class="bar green" style="height:40%"></span>
                        <span class="bar purple-bar" style="height:72%"></span>
                        <small>Jan</small>
                    </div>
                    <div class="bar-group">
                        <span class="bar green" style="height:45%"></span>
                        <span class="bar purple-bar" style="height:95%"></span>
                        <small>Feb</small>
                    </div>
                </div>
                <div class="legend">
                    <span>🟣 Inpatients</span>
                    <span>🟢 Outpatients</span>
                </div>
            </div>

            <div class="panel">
                <h3>Patients by Gender</h3>
                <div class="donut"></div>
                <div class="legend">
                    <span>🟠 Female</span>
                    <span>🟣 Male</span>
                </div>
            </div>

            <div class="panel">
                <h3>Time Admitted</h3>
                <div class="line-chart">
                    <svg viewBox="0 0 400 140" width="100%">
                        <path d="M0 110 Q30 100 45 65 T90 90 T140 70 T190 35 T240 90 T300 30 T400 50"
                              fill="none" stroke="#ff9b70" stroke-width="3"/>
                    </svg>
                </div>
                <p class="chart-note">07 am &nbsp;&nbsp; 09 am &nbsp;&nbsp; 11 am &nbsp;&nbsp; 01 pm</p>
            </div>

            <div class="panel">
                <h3>Patients By Division</h3>
                <div class="division">
                    <p>♡ Cardiology <b>247</b></p>
                    <p>♧ Neurology <b>164</b></p>
                    <p>✣ Surgery <b>86</b></p>
                </div>
            </div>

            <div class="purple-card">
                <h2>3,240</h2>
                <p>Patients this month</p>
                <div class="wave">〰〰〰〰〰</div>
                <small>14 &nbsp; 15 &nbsp; 16 &nbsp; 17 &nbsp; 18 &nbsp; 19</small>
            </div>

        </section>

    </main>
</div>

</body>
</html>