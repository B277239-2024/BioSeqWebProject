<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Acknowledgements - BioSeqAnalysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 950px;
            margin: 60px auto;
            padding: 40px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 30px;
            font-weight: bold;
        }
        h4 {
            margin-top: 30px;
            color: #2c3e50;
        }
        p {
            font-size: 1.1rem;
            line-height: 1.7;
        }
        hr {
            border-top: 1px dashed #ccc;
            margin-top: 30px;
        }
        a {
            color: #1565f9;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
    <h2>Acknowledgements</h2>

    <h4>Code Sources</h4>
    <p>
        This web application was developed with the help of code guidance and debugging from <strong>ChatGPT (OpenAI)</strong>. 
        The assistant provided detailed suggestions and real-time corrections for PHP session handling, MySQL-PDO operations, asynchronous BLAST integration, and visualization features.
    </p>

    <hr>

    <h4>AI Tools Used</h4>
    <p>
        <strong>ChatGPT (OpenAI)</strong> was used extensively for:
        <ul>
            <li>Generating PHP, JavaScript, HTML and CSS code snippets for database connection, session control, and AJAX functionality</li>
            <li>Debugging and improving logical flow in user registration, result display, and dynamic download implementation</li>
            <li>Explaining external APIs and helping parse JSON from REST APIs</li>
        </ul>
    </p>

    <p>
    <strong>DeepSeek-Coder</strong> was primarily used to:
    <ul> 
    <li>Help understand the overall project requirements and scope.</li> 
    It assisted in decomposing the task into logical modules such as user system, protein sequence retrieval, analysis pipeline, visualization, and result storage. 
</p>

    <hr>

    <h4>External Databases and APIs</h4>
    <p>
        Protein structure visualization is powered by the <strong><a href="https://www.rcsb.org/" target="_blank">PDB Search API (RCSB)</a></strong> and integrated with <strong>NGL Viewer</strong> for 3D rendering.
    </p>
    <p>
        Protein sequence data and taxonomy-filtered searches are retrieved from <strong><a href="https://www.ncbi.nlm.nih.gov/" target="_blank">NCBI Protein Database</a></strong> using the Entrez Utilities (eSearch, eFetch) via shell scripts.
    </p>
    
    <hr>
    <h4>GitHub Repository</h4>
    <p>
      The full source code for this website is available at:  
      <a href="https://github.com/B277239-2024/BioSeqWebProject" target="_blank">
        https://github.com/B277239-2024/BioSeqWebProject
      </a>
</p>
</div>
</body>
</html>