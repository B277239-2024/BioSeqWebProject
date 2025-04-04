<?php
function parseFastaToSeqTable($job_id, $pdo) {
    $fasta_file = "results/job_{$job_id}.fasta";
    if (!file_exists($fasta_file)) {
        return;
    }

    $handle = fopen($fasta_file, "r");
    $accession = '';
    $species = '';
    $sequence = '';

    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        if ($line === '') continue;

        if ($line[0] === '>') {
            if ($accession && $sequence) {
                $stmt = $pdo->prepare("INSERT INTO Seq (job_id, accession_id, species_name, sequence) VALUES (?, ?, ?, ?)");
                $stmt->execute([$job_id, $accession, $species, $sequence]);
            }

            $sequence = '';
            preg_match('/^>(\S+).*?\[(.+?)\]$/', $line, $matches);
            $accession = $matches[1];                
            $species = $matches[2] ?? 'Unknown';      
        } else {
            $sequence .= $line;
        }
    }

    if ($accession && $sequence) {
        $stmt = $pdo->prepare("INSERT INTO Seq (job_id, accession_id, species_name, sequence) VALUES (?, ?, ?, ?)");
        $stmt->execute([$job_id, $accession, $species, $sequence]);
    }

    fclose($handle);
}
?>
