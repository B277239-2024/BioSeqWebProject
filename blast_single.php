<?php
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method.";
    exit();
}

$job_id = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;
$accession_id = isset($_POST['accession_id']) ? $_POST['accession_id'] : '';

if (!$job_id || !$accession_id) {
    echo "Missing parameters.";
    exit();
}

$used_cache = false;

// Step 1: Check cache table
$stmt = $pdo->prepare("SELECT blast_path FROM Blast_Cache WHERE accession_id = ?");
$stmt->execute([$accession_id]);
$cached = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cached && file_exists($cached['blast_path'])) {
    $output_path = $cached['blast_path'];
    $used_cache = true;
} else {
    // Step 2: Get sequence
    $stmt = $pdo->prepare("SELECT sequence FROM Seq WHERE job_id = ? AND accession_id = ?");
    $stmt->execute([$job_id, $accession_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo "Sequence not found.";
        exit();
    }

    $sequence = $row['sequence'];
    $blast_dir = "results/blast_cache";
    $fasta_path = "$blast_dir/{$accession_id}.fasta";
    $output_path = "$blast_dir/{$accession_id}_blast.txt";

    // Make sure directory exists
    if (!file_exists($blast_dir)) {
        mkdir($blast_dir, 0775, true);
    }

    // Step 3: Write fasta file
    file_put_contents($fasta_path, ">{$accession_id}\n" . chunk_split($sequence, 60));

    // Step 4: Run BLAST via shell script
    $blast_cmd = "bash blast_single.sh \"$accession_id\"";
    $start_time = microtime(true);
    exec($blast_cmd . ' 2>&1', $output_lines, $exit_code);
    $end_time = microtime(true);

    $duration = $end_time - $start_time;
    $minutes = floor($duration / 60);
    $seconds = round(fmod($duration, 60));

    echo "<p><strong>BLAST analysis completed in:</strong> {$minutes} minutes {$seconds} seconds</p>";

    // Step 5: Check output and cache
    if (file_exists($output_path)) {
        $stmt = $pdo->prepare("REPLACE INTO Blast_Cache (accession_id, blast_path, analysis_time) VALUES (?, ?, ?)");
        $stmt->execute([$accession_id, $output_path, date('Y-m-d H:i:s')]);
    } else {
        echo "<p style='color:red;'>BLAST failed or no output produced.</p>";
        exit();
    }
}

// Step 6: Display result table
if ($used_cache) {
    echo "<p style='color:green;'><strong>Cached Result:</strong> This BLAST result was retrieved from previous analyses.</p>";
} else {
    echo "<p style='color:blue;'><strong>New Analysis:</strong> This result was freshly generated just now.</p>";
}

echo "<h4>Top 10 BLAST Hits for $accession_id</h4>";
echo "<table border='1' cellpadding='5'><tr>
        <th>Query ID</th><th>Subject ID</th><th>% Identity</th>
        <th>Alignment Length</th><th>Mismatches</th>
        <th>Gap Opens</th><th>Q. Start</th><th>Q. End</th>
        <th>S. Start</th><th>S. End</th><th>E-value</th><th>Bit Score</th></tr>";

$lines = file($output_path);
foreach ($lines as $line) {
    $cols = explode("\t", trim($line));
    if (count($cols) >= 12) {
        echo "<tr>";
        foreach (array_slice($cols, 0, 12) as $val) {
            echo "<td>" . htmlspecialchars($val) . "</td>";
        }
        echo "</tr>";
    }
}
echo "</table>";
?>