<?php
function parseMotifFile($job_id, $pdo) {
  $motif_file = "results/job_{$job_id}_motifs.txt";
  if (!file_exists($motif_file)) {
    return;
  }

  $motif_lines = file($motif_file);
  $current_sequence = null;
  $start = null;
  $end = null;
  
  for ($i = 0; $i < count($motif_lines); $i++) {
    $line = trim($motif_lines[$i]);
  
    if (strpos($line, '# Sequence:') === 0) {
      preg_match('/# Sequence: (\S+)/', $line, $matches);
      $current_sequence = $matches[1] ?? null;
      $start = null;
      $end = null;
    }
    
    if (strpos($line, 'Start =') === 0) {
      preg_match('/position (\d+)/', $line, $matches);
      $start = isset($matches[1]) ? (int)$matches[1] : null;
    }
    
    if (strpos($line, 'End =') === 0) {
      preg_match('/position (\d+)/', $line, $matches);
      $end = isset($matches[1]) ? (int)$matches[1] : null;
    }
    
    if (strpos($line, 'Motif =') === 0 && $current_sequence !== null && $start !== null && $end !== null) {
      $motif_name = trim(substr($line, strlen('Motif =')));

      $stmt = $pdo->prepare("INSERT INTO Motif_hits (job_id, accession_id, motif_name, start_pos, end_pos) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$job_id, $current_sequence, $motif_name, $start, $end]);
      
      $start = null;
      $end = null;
    }
  }
}
