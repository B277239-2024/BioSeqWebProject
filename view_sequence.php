<?php
session_start();
require_once 'db_config.php';

if (!isset($_GET['job_id']) || !isset($_GET['acc'])) {
    echo "Missing parameters.";
    exit();
}

$job_id = (int) $_GET['job_id'];
$accession_id = $_GET['acc'];

$stmt = $pdo->prepare("SELECT * FROM Seq WHERE job_id = ? AND accession_id = ?");
$stmt->execute([$job_id, $accession_id]);
$seq = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$seq) {
    echo "Sequence not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sequence Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/ngl@latest/dist/ngl.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f9fc;
            padding: 20px;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .seq-box {
            font-family: monospace;
            font-size: 1.1em;
            background: #f1f1f1;
            padding: 15px;
            border-radius: 6px;
            white-space: pre-wrap;
            word-break: break-word;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f0f4f7;
        }
        #structureViewer {
            width: 100%;
            height: 500px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        #structureMeta {
            text-align: center;
            margin-bottom: 20px;
        }
        h2, h3 {
            margin-top: 25px;
            color: #333;
        }
        button {
            background-color: #1565f9;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0c49d3;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
    <h2>Sequence: <?php echo htmlspecialchars($accession_id); ?></h2>
    <p><strong>Species:</strong> <?php echo htmlspecialchars($seq['species_name']); ?></p>
    <p><strong>Job ID:</strong> <?php echo $job_id; ?> | <a href="view_result.php?job_id=<?php echo $job_id; ?>">Back to Job</a></p>

    <h3>Full Sequence</h3>
    <a href="results/blast_cache/<?php echo $accession_id; ?>.fasta" class="btn btn-sm btn-success mb-3" download>Download Sequence (FASTA)</a>
    <div class="seq-box"><?php echo wordwrap(htmlspecialchars($seq['sequence']), 60, "\n", true); ?></div>

    <h3>3D Protein Structure (via NGL Viewer)</h3>
    <div id="structureViewer"></div>
    <div id="structureMeta"></div>

    <h3>Motif Hits</h3>
    <a href="results/job_<?php echo $job_id; ?>_motifs.txt" class="btn btn-sm btn-secondary mb-2" download>Download Motif TXT</a>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM Motif_hits WHERE job_id = ? AND accession_id = ?");
    $stmt->execute([$job_id, $accession_id]);
    $motifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($motifs) === 0) {
        echo "<p>No motif hits found for this sequence.</p>";
    } else {
        echo "<table><tr><th>Motif Name</th><th>Start</th><th>End</th></tr>";
        foreach ($motifs as $m) {
            echo "<tr><td>" . htmlspecialchars($m['motif_name']) . "</td><td>" . $m['start_pos'] . "</td><td>" . $m['end_pos'] . "</td></tr>";
        }
        echo "</table>";
    }
    ?>

    <h3>Motif Map</h3>
    <a href="results/job_<?php echo $job_id; ?>/<?php echo $accession_id; ?>_motifmap.png" class="btn btn-sm btn-info mb-3" download>Download Motif Map</a>
    <?php
    $motif_img_path = "results/job_{$job_id}/{$accession_id}_motifmap.png";
    if (file_exists($motif_img_path)) {
        echo "<img src=\"$motif_img_path\" class=\"img-fluid\" style=\"max-width:100%; border-radius:5px;\">";
    } else {
        echo "<p>No motif image available.</p>";
    }
    ?>

    <h3>Run BLAST Analysis</h3>
    <button onclick="runBlast()">Run BLAST for this sequence</button>
    <div id="blastResult" style="margin-top:20px;"></div>
</div>

<script>
function runBlast() {
    if (!confirm('Running BLAST will take 15 minutes or more. However, if the sequence has been analyzed before, the results can be displayed immediately. Proceed?')) return;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'blast_single.php');
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
    document.getElementById('blastResult').innerHTML = `
        ${xhr.responseText}
        <br><a href="results/blast_cache/<?php echo $accession_id; ?>_blast.txt" 
        class="btn btn-sm btn-outline-dark mt-3" download>Download BLAST Top Hits</a>`;
};
    xhr.send('job_id=<?php echo $job_id; ?>&accession_id=<?php echo urlencode($accession_id); ?>');
    document.getElementById('blastResult').innerHTML = '<p style="color:gray;">Running BLAST... please wait.</p>';
}

window.onload = function () {
    const container = document.getElementById("structureViewer");
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "search_structure.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        try {
            const data = JSON.parse(xhr.responseText);
            if (data.pdb_id) {
                var stage = new NGL.Stage("structureViewer", { backgroundColor: "white" });
                stage.loadFile("rcsb://" + data.pdb_id).then(function (component) {
                    component.addRepresentation("cartoon");
                    component.autoView();
                });
                fetch(`https://data.rcsb.org/rest/v1/core/entry/${data.pdb_id}`)
                    .then(res => res.json())
                    .then(info => {
                        const title = info.struct.title;
                        const method = info.rcsb_entry_info.experimental_method;
                        const date = info.rcsb_accession_info.deposit_date;
                        const pdbUrl = `https://www.rcsb.org/structure/${data.pdb_id}`;
                        document.getElementById("structureMeta").innerHTML = `
                            <p><strong>PDB ID:</strong> <a href="${pdbUrl}" target="_blank">${data.pdb_id}</a><br>
                            <strong>Method:</strong> ${method}<br>
                            <strong>Structure:</strong> ${title}<br>
                            <strong>Deposit Date:</strong> ${date}</p>
                        `;
                    });
            } else {
                container.innerHTML = '<p style="color:gray;">No 3D structure found for this sequence.</p>';
            }
        } catch (e) {
            container.innerHTML = '<p style="color:gray;">3D structure retrieval failed.</p>';
        }
    };
    xhr.send("job_id=<?php echo $job_id; ?>&accession_id=<?php echo urlencode($accession_id); ?>");
};
</script>
</body>
</html>