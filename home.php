<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome - BioSeqAnalysis</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .hero {
      text-align: center;
      background-color: #ffffff;
      border-radius: 10px;
      border: 1px solid #d0d0d0;
      padding: 70px 20px;
      margin: 50px auto 30px;
      max-width: 900px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .hero h1 {
      font-size: 3.5rem;
      font-weight: bold;
      color: #212529;
    }
    .hero p {
      font-size: 1.25rem;
      color: #555;
      max-width: 800px;
      margin: 20px auto 0;
    }
    .card-container {
      display: flex;
      justify-content: center;
      gap: 50px;
      margin: 60px auto;
      flex-wrap: wrap;
    }
    .card {
      width: 330px;
      border: 1px solid #ddd;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
    }
    .card:hover {
      transform: scale(1.03);
    }
    .card-body {
      padding: 30px;
    }
    .card-title {
      font-size: 1.5rem;
      font-weight: 600;
    }
    .btn-primary {
      margin-top: 18px;
      padding: 10px 20px;
      font-size: 1rem;
    }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>
  <div class="hero">
    <h1>Welcome to BioSeqAnalysisWeb</h1>
    <p>This platform allows you to retrieve and analyze protein family sequences. You can perform tasks such as conservation evaluation, motif detection, 3D structure visualization, and BLAST analysis by providing a protein family name and taxonomic group.</p>
  </div>

  <div class="card-container">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Example & Help</h5>
        <p class="card-text">View an example task and learn how to use the tools provided by the website.</p>
        <a href="help.php" class="btn btn-primary">Explore</a>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Start Now</h5>
        <p class="card-text">Submit your own protein family task and run sequence analysis and visualization.</p>
        <a href="submit_job.php" class="btn btn-primary">Start Analysis</a>
      </div>
    </div>
  </div>

</body>
</html>