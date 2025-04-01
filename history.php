<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Login Required</title>
</head>
<body>
    <h2>Access Denied</h2>
    <p>You need to <strong>log in</strong> to access your analysis history.</p>
    <a href="login.php">Click here to login</a>
</body>
</html>
HTML;
    exit();
}

require_once 'db_config.php';
$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM Jobs WHERE user_id = ? ORDER BY submitted_at DESC");
    $stmt->execute([$user_id]);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Analysis Jobs</title>
</head>
<body>
    <h2>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    <h3>Your Submitted Jobs</h3>

    <?php if (count($jobs) === 0): ?>
        <p>You have not submitted any jobs yet.</p>
    <?php else: ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Job ID</th>
                <th>Protein Family</th>
                <th>Taxonomy Group</th>
                <th>Status</th>
                <th>Submitted At</th>
                <th>View</th>
            </tr>
            <?php foreach ($jobs as $job): ?>
                <tr>
                    <td><?php echo $job['job_id']; ?></td>
                    <td><?php echo htmlspecialchars($job['protein_family']); ?></td>
                    <td><?php echo htmlspecialchars($job['taxonomy_group']); ?></td>
                    <td><?php echo $job['job_status']; ?></td>
                    <td><?php echo $job['submitted_at']; ?></td>
                    <td><a href="view_result.php?job_id=<?php echo $job['job_id']; ?>">View</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <br>
    <a href="submit_job.php">Submit New Job</a> | <a href="logout.php">Logout</a>
</body>
</html>