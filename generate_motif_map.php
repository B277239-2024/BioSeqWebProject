<?php
require_once 'db_config.php';

function writeMotifJSON($job_id, $pdo) {
    $stmt = $pdo->prepare("SELECT accession_id, motif_name, start_pos, end_pos FROM Motif_hits WHERE job_id = ?");
    $stmt->execute([$job_id]);
    $motifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($motifs)) {
        error_log("No motifs found for job $job_id.");
        return false;
    }

    $json_path = "results/job_{$job_id}_motifs.json";
    file_put_contents($json_path, json_encode($motifs, JSON_PRETTY_PRINT));
    return $json_path;
}
?>