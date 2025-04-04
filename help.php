<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Help - BioSeqAnalysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }
        p {
            font-size: 1.15rem;
            line-height: 1.8;
            color: #333;
            margin-bottom: 20px;
        }
        h2 {
            margin-top: 30px;
            margin-bottom: 15px;
        }
        img {
            max-width: 100%;
            border-radius: 10px;
            margin: 15px 0 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        hr {
            margin: 40px 0;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
    <h1 class="text-center mb-4">Help & Example: Glucose-6-phosphatase in Aves</h1>

    <h2>1. User Login and Registration</h2>
    <p>On the login page, you can enter your username and password to log in. If you don't have an account, click the link below to create one.</p>
    <img src="help/1.png" alt="Login page">
    <p>Fill out the registration form and click "Register" to create your account.</p>
    <img src="help/2.png" alt="Register page">

    <hr>

    <h2>2. Home Page</h2>
    <p>Once logged in, your username will appear in the top-right corner. The homepage includes a navigation bar, welcome message, and two shortcut cards to access help or start a new analysis.</p>
    <img src="help/3.png" alt="Homepage">

    <hr>

    <h2>3. Submitting an Analysis Task</h2>
    <p>To start an analysis, enter a protein family and taxonomy group (e.g., <code>glucose-6-phosphatase</code> and <code>Aves</code>) and click "Submit Task."</p>
    <img src="help/4.png" alt="Submit form">
    <p>When the analysis completes successfully, the job status will be shown as <strong>done</strong>. You can click the green button to view the results.</p>
    <img src="help/5.png" alt="Job done">

    <hr>

    <h2>4. Viewing Job Results</h2>
    <p>The result page displays the job ID, protein family, and taxonomy group.</p>
    <img src="help/6.png" alt="Job info">

    <h4>Sequence List</h4>
    <p>This section shows all retrieved sequences with accession IDs and species names. You can download the multi-FASTA file using the button above the table.</p>
    <img src="help/7.png" alt="Sequence table">

    <h4>Conservation Plot</h4>
    <p>
The conservation plot is generated using <code>plotcon</code>, a tool that analyzes multiple sequence alignments to determine the degree of similarity at each position. 
The X-axis represents the relative position along the aligned amino acid sequences, while the Y-axis shows the similarity score.
Peaks in the graph indicate regions of high conservation, meaning those amino acids are highly similar or identical across all sequences.
Conversely, valleys suggest variable regions, possibly reflecting structural flexibility or evolutionary divergence.
Such conserved regions are often functionally or structurally important domains.
</p>
    <img src="help/8.png" alt="Conservation plot">

    <hr>

    <h2>5. Sequence Detail Page</h2>
    <p>Clicking on any accession ID brings you to its detailed page. The top section shows sequence-specific information such as species and job ID.</p>
    <img src="help/9.png" alt="Sequence header">

    <h4>Full Sequence</h4>
    <p>The complete amino acid sequence is displayed here. You can download the FASTA file using the green button.</p>
    <img src="help/10.png" alt="Single FASTA">

    <h4>3D Protein Structure</h4>
    <p>The structure viewer displays the best-matched protein 3D model from the PDB database. You can interact with the model and view structure metadata including method and deposit date.</p>
    <img src="help/11.png" alt="3D structure">

    <h4>Motif Detection</h4>
    <p>Motif hits are shown in a table with start/end positions and names. The motif map visually highlights conserved sequence motifs detected in the protein sequence.
Each motif is represented as a colored box placed at its corresponding location on the amino acid sequence (X-axis).
The length of each box reflects the size of the motif, while the label (e.g., AMIDATION) identifies the motif type.
This helps users quickly identify functional domains or binding sites that may play critical roles in protein activity.
The visualization is especially useful for comparing motif distribution across multiple sequences.</p>
    <img src="help/12.png" alt="Motif hits and map">

    <hr>

    <h2>6. Running a BLAST Analysis</h2>
    <p>You can run BLAST by clicking the button. A confirmation dialog will appear, indicating that it might take 15+ minutes unless results already exist in cache.</p>
    <img src="help/13.png" alt="BLAST button">
    <img src="help/14.png" alt="BLAST confirm">

    <p>If cached results exist, they will be shown immediately. The top 10 hits include identity, alignment, and bit score, and you can download the results as a text file.</p>
    <img src="help/15.png" alt="BLAST result">

    <hr>
    
    <h2>7. View Past Submissions</h2>
<p>
    After submitting and completing tasks, you can view a record of all previously submitted jobs by navigating to the <strong>History</strong> page from the top navigation bar.  
    <br><br>
    This page lists all past jobs, including their job ID, protein family, taxonomy group, status (e.g., done or failed), and submission time.  
    <br><br>
    For tasks that were successfully completed (<span style="color:green;">done</span>), you can click the blue <strong>View</strong> button on the right to directly access the full result page, including sequence data, conservation plot, motif information, and BLAST hits.
</p>
<img src="help/16.png" alt="History page" style="max-width:100%; border:1px solid #ccc; margin: 15px 0;">
    <h2 class="text-center">You're All Set!</h2>
    <p class="text-center">You have now learned how to use BioSeqAnalysisWeb from login to full analysis using a real-world example.</p>
</div>
</body>
</html>