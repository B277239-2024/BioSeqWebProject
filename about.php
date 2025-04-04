<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About - BioSeqAnalysis</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f7fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      max-width: 900px;
      margin: 50px auto;
      padding: 40px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h2 {
      margin-bottom: 25px;
      font-weight: bold;
      color: #2c3e50;
    }
    p {
      font-size: 1.1rem;
      line-height: 1.75rem;
      margin-bottom: 18px;
    }
    hr {
      border-top: 1px dashed #aaa;
      margin: 35px 0;
    }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="container">
  <h2>About BioSeqAnalysis</h2>

  <p>
    According to the ICA task description, I planned to develop a database-driven web application that accepts user-defined parameters, retrieves relevant protein sequences, and performs various analyses. I started by defining the overall user experience flow, which I saw as:
  </p>

  <p>
    The user submits an analysis task -> parameters are collected via PHP and stored into a database -> a Python script fetches sequences and saves them in FASTA format -> PHP writes the sequences to the database -> Python scripts execute multiple analysis tools (Clustal Omega, plotcon, motif detection, BLAST) -> analysis results are stored in the database -> Finally, all results are visualized and made downloadable on the front end.
  </p>

  <hr>

  <p>
    Based on this logic, I first designed the database. I carefully considered the required tables and their relationships:
  </p>

  <ul>
    <li><strong>Users</strong>: Stores user credentials and login information.</li>
    <li><strong>Jobs</strong>: Stores user-submitted task parameters.</li>
    <li><strong>Seq</strong>: Stores the sequences retrieved based on user input.</li>
    <li><strong>Result</strong>: Stores analysis results and image paths.</li>
    <li><strong>Motif_hits</strong>: Stores motif scan results for each sequence.</li>
  </ul>

  <p>
    Meanwhile, I also envisioned the website's main functional pages: Homepage with navigation, job submission page, result display page, history tracking page, help page with examples, and an acknowledgements section.
  </p>

  <hr>

  <p>
    With the database ready, I first implemented login and registration functionalities linked to the <i>Users</i> table. I then developed the <i>submit_job.php</i> page to accept input parameters and record them in the <i>Jobs</i> table. 
  </p>

  <p>
    I created <i>run_analysis.sh</i> as the core script to fetch sequence data and perform Clustal Omega, motif analysis, and other tasks. I added corresponding logic to <i>submit_job.php</i> to execute the shell script and update job status accordingly.
  </p>

  <p>
    As the features expanded, I added new scripts like <i>parse_motifs.php</i> to insert motif hits, <i>parse_fasta.php</i> to write sequence data, <i>generate_motif_map.php</i> to convert motif hits into JSON, and <i>draw_motifs.py</i> to visualize motif locations.
  </p>

  <hr>

  <p>
    Later, I added BLAST functionality. Since BLAST can be time-consuming, I allowed users to optionally run it with a confirmation prompt, then retrieve results asynchronously using AJAX. I also added 3D structure display based on PDB ID using the NGL viewer.
  </p>

  <p>
    After implementing the core logic, I focused on UI/UX improvements, including a unified Bootstrap-based design, responsive navigation bar, download buttons, and consistent layouts across all pages.
  </p>

  <p>
    Finally, I prepared a help page that walks through the full analysis pipeline with a real example and included an acknowledgements page that credits data sources, AI assistance, and open APIs used.
  </p>

  <hr>

  <p>
    Through this project, I gained a comprehensive understanding of integrating biological data, database-driven backend logic, and modern front-end web development into a complete interactive analysis system.
  </p>
</div>

</body>
</html>
