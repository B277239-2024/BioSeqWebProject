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
</head>

<body>
	<h2>Submit a New Protein Analysis Task</h2>
	<form method="POST" action="submit_job.php">
		<label for="protein_family">Protein_family: </label>
		<input type="text" id="protein_family" name="protein_family" required><br><br>

		<label for="taxonomy_group">Taxonomy_group: </label>
		<input type="text" id="taxonomy_group" name="taxonomy_group" required><br><br>
		
		<input type="submit" name="submit" value="Submit!">
	</form>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$protein_family = $_POST['protein_family'];
	$taxonomy_group = $_POST['taxonomy_group'];
	$user_id = $_SESSION['user_id'];
	
	require_once 'db_config.php';
	try{
	$stmt = $pdo->prepare("INSERT INTO Jobs (user_id, protein_family, taxonomy_group, job_status) VALUES (?, ?, ?, 'submitted')");
	$stmt->execute([$user_id, $protein_family, $taxonomy_group]);
	echo "<p style='color:green;'>The task has been successfully submitted!!!</p>";
	} catch (PDOException $e) {
	echo "Database Error: " . $e->getMessage();
}
}
?>
</body>
</html>


