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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            text-align: center;
        }

        .footer-links {
            margin-top: 25px;
            text-align: center;
        }

        .footer-links a {
            margin: 0 12px;
            text-decoration: none;
            color: #0d6efd;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
    <h2>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    <h3>Your Submitted Jobs</h3>
    
    <?php if (count($jobs) === 0): ?>
    <p class="text-center mt-3">You have not submitted any jobs yet.</p>
<?php else: ?>
    <table class="table table-bordered table-hover mt-4">
        <thead class="table-light">
            <tr>
                <th>Job ID</th>
                <th>Protein Family</th>
                <th>Taxonomy Group</th>
                <th>Status</th>
                <th>Submitted At</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($jobs as $job): ?>
            <tr>
                <td><?= $job['job_id']; ?></td>
                <td><?= htmlspecialchars($job['protein_family']); ?></td>
                <td><?= htmlspecialchars($job['taxonomy_group']); ?></td>
                <td>
                    <?php if ($job['job_status'] === 'done'): ?>
                        <span class="badge bg-success">Done</span>
                    <?php elseif ($job['job_status'] === 'failed'): ?>
                        <span class="badge bg-danger">Failed</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">Submitted</span>
                    <?php endif; ?>
                </td>
                <td><?= $job['submitted_at']; ?></td>
                <td><a href="view_result.php?job_id=<?= $job['job_id']; ?>" class="btn btn-sm btn-primary">View</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

    <br>
    <div class="footer-links">
      <a href="submit_job.php">Submit New Job</a> |
      <a href="logout.php">Logout</a>
    </div>
</div> <!-- container -->
</body>
</html>