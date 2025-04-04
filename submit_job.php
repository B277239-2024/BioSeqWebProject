<?php
session_start();

if(!isset($_SESSION['user_id'])){
	header("Location: login.php");
	exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Submission of Analysis Task</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 60px auto;
        padding: 30px 40px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .container h2 {
        margin-bottom: 30px;
        font-size: 26px;
    }

    .form-group {
        margin-bottom: 25px;
        text-align: left;
    }

    .form-group label {
        display: block;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-group input[type="text"] {
        width: 100%;
        padding: 10px 12px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button[type="submit"] {
        background-color: #1565f9;
        color: white;
        padding: 10px 25px;
        font-size: 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #0c49d3;
    }
    
    #messageArea {
    text-align: center;
    font-family: 'Segoe UI', sans-serif;
    margin-top: 25px;
}
</style>

<script>
function showHint(id) {
    document.getElementById(id).style.display = 'block';
}
function hideHint(id) {
    document.getElementById(id).style.display = 'none';
}
</script>
</head>

<body>
<?php include 'nav.php'; ?>
<div class="container">
    <h2>Submit a New Protein Analysis Task</h2>
    <p id="proteinHint" style="color: gray; font-size: 16px; display:none;">e.g. Glucose-6-phosphatase</p>
    <p id="taxHint" style="color: gray; font-size: 16px; display:none;">e.g. Aves</p>
    <form method="POST" action="submit_job.php">
        <div class="form-group">
          <label for="protein_family">Protein family:</label>
          <input type="text" id="protein_family" name="protein_family" required
                 onfocus="showHint('proteinHint')" onblur="hideHint('proteinHint')">
        </div>

        <div class="form-group">
          <label for="taxonomy_group">Taxonomy Group:</label>
          <input type="text" id="taxonomy_group" name="taxonomy_group" required
                 onfocus="showHint('taxHint')" onblur="hideHint('taxHint')">
        </div>
        <button type="submit" name="submit">Submit Task</button>
    </form>
    <div id="messageArea"></div>
</div>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$protein_family = $_POST['protein_family'];
	$taxonomy_group = $_POST['taxonomy_group'];
	$user_id = $_SESSION['user_id'];
	
	require_once 'db_config.php';
	try{
       $check_stmt = $pdo->prepare("SELECT job_id FROM Jobs WHERE user_id = ? AND protein_family = ? AND taxonomy_group = ? AND job_status = 'done'");
       $check_stmt->execute([$user_id, $protein_family, $taxonomy_group]);
       $existing_job = $check_stmt->fetch(PDO::FETCH_ASSOC);
       
       if ($existing_job) {
         $existing_id = $existing_job['job_id'];
         echo "<script>
document.getElementById('messageArea').innerHTML = `
  <p style=\"color: orange; font-size: 18px;\">You've already submitted an identical task before (Job ID: $existing_id). Redirecting to results...</p>
`;
setTimeout(function(){ window.location.href = 'view_result.php?job_id=$existing_id'; }, 3000);
</script>";
         exit();
       }
       
	$stmt = $pdo->prepare("INSERT INTO Jobs (user_id, protein_family, taxonomy_group, job_status) VALUES (?, ?, ?, 'submitted')");
	$stmt->execute([$user_id, $protein_family, $taxonomy_group]);
 
  $job_id = $pdo->lastInsertId();
  echo "<script>
    document.getElementById('messageArea').innerHTML = `
        <p style=\"color: green; font-size: 18px;\">The task has been successfully submitted (Job ID: $job_id)!</p>`;
</script>";
  
  $query = "{$protein_family}[Protein name] AND {$taxonomy_group}[Organism]";
  $escaped_query = escapeshellarg($query);
  $cmd = "bash run_analysis.sh $job_id $escaped_query";
  
  $output = shell_exec($cmd . " 2>&1");
  echo "<pre>$output</pre>"; 
  
  $plot_file = "results/job_{$job_id}_plot.1.png";
  $motif_file = "results/job_{$job_id}_motifs.txt";
        if (file_exists($plot_file) && file_exists($motif_file)) {
            $new_status = 'done';
        } else {
            $new_status = 'failed';
        }
  
  $update = $pdo->prepare("UPDATE Jobs SET job_status = ? WHERE job_id = ?");
  $update->execute([$new_status, $job_id]);
  
  if ($new_status === 'done') {
    require_once 'parse_motifs.php'; 
    parseMotifFile($job_id, $pdo);
    
    require_once 'parse_fasta.php';
    parseFastaToSeqTable($job_id, $pdo); 
  }
  echo "<script>
    document.getElementById('messageArea').innerHTML = `
        <p style=\"font-size: 16px;\">Job Status: <strong>$new_status</strong></p>
    `;
</script>";
  
  if ($new_status === 'done') {
    $image_path = $plot_file;
    $result_text = "Conservation plot generated using plotcon.";
    $analysis_time = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare("INSERT INTO Result (job_id, result_text, image_path, analysis_time) VALUES (?, ?, ?, ?)");
    $stmt->execute([$job_id, $result_text, $image_path, $analysis_time]);
  }
  
  echo "<script>
    document.getElementById('messageArea').innerHTML += `
        <br><a class=\"btn btn-success mt-3\" href=\"view_result.php?job_id=$job_id\">View Result Page</a>
    `;
</script>";
  
  require_once 'generate_motif_map.php';
  $json_path = writeMotifJSON($job_id, $pdo);

  if ($json_path && file_exists($json_path)) {
    shell_exec("python3 draw_motifs.py $job_id");
  
    $motif_img = "results/job_{$job_id}_motifmap.png";
    if (file_exists($motif_img)) {
      $stmt = $pdo->prepare("INSERT INTO Result (job_id, result_text, image_path, analysis_time) VALUES (?, ?, ?, ?)");
      $stmt->execute([$job_id, "Motif map generated using patmatmotifs.", $motif_img, date('Y-m-d H:i:s')]);
    }
  }

	} catch (PDOException $e) {
	echo "Database Error: " . $e->getMessage();
}
}
?>
</body>
</html>


