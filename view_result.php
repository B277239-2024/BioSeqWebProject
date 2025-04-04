<?php
session_start();
require_once 'db_config.php';

if (!isset($_GET['job_id'])) {
    echo "Job ID not specified.";
    exit();
}

$job_id = (int) $_GET['job_id'];

$stmt = $pdo->prepare("SELECT * FROM Jobs WHERE job_id = ?");
$stmt->execute([$job_id]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    echo "Job not found.";
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM Seq WHERE job_id = ?");
$stmt->execute([$job_id]);
$sequences = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM Result WHERE job_id = ?");
$stmt->execute([$job_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Result - <?php echo $job_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .card {
            margin-top: 40px;
        }
        .result-img {
            max-width: 100%;
            border: 1px solid #ddd;
            margin-top: 15px;
        }
        .table thead th {
            text-align: center;
        }
        .table td {
            text-align: center;
        }
    </style>
</head>

<body>
<?php include 'nav.php'; ?>
<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="card-title mb-4">Analysis Result: Job ID <?php echo $job_id; ?></h3>
            <p><strong>Protein Family:</strong> <?php echo htmlspecialchars($job['protein_family']); ?></p>
            <p><strong>Taxonomy Group:</strong> <?php echo htmlspecialchars($job['taxonomy_group']); ?></p>
            <p><strong>Status:</strong> <?php echo $job['job_status']; ?> | <a href="history.php">Back to History</a></p>

            <hr>

            <h5>Sequence List</h5>
            <a href="results/job_<?php echo $job_id; ?>.fasta" class="btn btn-sm btn-success mb-3" download>Download Multi-FASTA</a>
            <?php if (count($sequences) === 0): ?>
                <p>No sequences found for this job.</p>
            <?php else: ?>
                <table class="table table-bordered mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Accession ID</th>
                            <th>Species</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sequences as $index => $seq): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <a href="view_sequence.php?job_id=<?php echo $job_id; ?>&acc=<?php echo urlencode($seq['accession_id']); ?>">
                                    <?php echo htmlspecialchars($seq['accession_id']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($seq['species_name']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <hr>
            <h5>Conservation Plot</h5>
            <?php
            $hasPlot = false;
            foreach ($results as $res):
                if (strpos($res['image_path'], 'plot') !== false):
                    $hasPlot = true;
            ?>
                <p><?php echo htmlspecialchars($res['result_text']); ?></p>
                <img class="result-img" src="<?php echo $res['image_path']; ?>" alt="Conservation Plot">
                <a href="<?php echo $res['image_path']; ?>" class="btn btn-sm btn-primary mt-2" download>Download Plot Image</a>
            <?php endif; endforeach; ?>

            <?php if (!$hasPlot): ?>
                <p class="text-muted">No conservation plot image found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>