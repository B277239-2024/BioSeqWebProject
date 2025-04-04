<?php
require_once 'db_config.php';
require_once 'generate_motif_map.php';

$job_id = 32; // Ìæ»»ÎªÄãÒª²âÊÔµÄ job ID

$json_path = writeMotifJSON($job_id, $pdo);

if ($json_path && file_exists($json_path)) {
    echo "? JSON file generated at: $json_path\n";
    echo "File preview:\n";
    echo file_get_contents($json_path);
} else {
    echo "? Failed to generate JSON file for job_id = $job_id\n";
}
?>