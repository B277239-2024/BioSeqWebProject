<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
<style>
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(to right, #004080, #0066cc); 
    padding: 16px 25px;
    font-family: 'Segoe UI', sans-serif;
    font-size: 16px;
    color: white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.navbar-left {
    font-size: 30px;
    font-weight: bold;
    font-family: 'Lobster', serif;
}

.navbar-right {
    display: flex;
    align-items: center;
}

.navbar-right a,
.navbar-right .user-dropdown {
    margin-left: 40px;
    color: white;
    text-decoration: none;
    position: relative;
    cursor: pointer;
    font-size: 20px;
}

.navbar-right .user-dropdown {
    position: relative;
    margin-left: 40px;
    font-size: 20px;
    color: white;
    cursor: pointer;
    padding: 10px 0;  
}

.navbar-right .user-dropdown:hover .dropdown-content {
    display: block;
}

.navbar-right .dropdown-content {
    display: none;
    position: absolute;
    top: 100%
    background-color: white;
    color: black;
    top: 30px;
    right: 0;
    min-width: 110px;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    z-index: 1000;
}

.navbar-right .dropdown-content a {
    color: black;
    padding: 10px 15px;
    display: block;
    text-decoration: none;
}

.navbar-right .dropdown-content a:hover {
    background-color: #f0f0f0;
}
</style>

<div class="navbar">
    <div class="navbar-left">BioSeqAnalysis</div>
    <div class="navbar-right">
        <a href="home.php">Home</a>
        <a href="submit_job.php">Search</a>
        <a href="history.php">History</a>
        <a href="help.php">Help</a>
        <a class="nav-link" href="acknowledgement.php">Acknowledgements</a>
        <a class="nav-link" href="about.php">About</a>

        <?php if (isset($_SESSION['username'])): ?>
            <div class="user-dropdown">
                Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>
                <div class="dropdown-content">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</div>