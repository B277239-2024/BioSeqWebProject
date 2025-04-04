<?php
header('Content-Type: application/json');
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method.']);
    exit();
}

$job_id = $_POST['job_id'] ?? null;
$accession_id = $_POST['accession_id'] ?? null;

if (!$job_id || !$accession_id) {
    echo json_encode(['error' => 'Missing parameters.']);
    exit();
}

// Fetch sequence
$stmt = $pdo->prepare("SELECT sequence FROM Seq WHERE job_id = ? AND accession_id = ?");
$stmt->execute([$job_id, $accession_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode(['error' => 'Sequence not found.']);
    exit();
}

$sequence = $row['sequence'];

// Construct GET URL for RCSB Search API
$query = [
    "query" => [
        "type" => "terminal",
        "service" => "sequence",
        "parameters" => [
            "evalue_cutoff" => 1,
            "sequence_type" => "protein",
            "value" => $sequence
        ]
    ],
    "request_options" => [
        "scoring_strategy" => "sequence" ],
    "return_type" => "polymer_entity"
];

$search_url = 'https://search.rcsb.org/rcsbsearch/v2/query?json=' . urlencode(json_encode($query));
$response = file_get_contents($search_url);

if ($response === false) {
    echo json_encode(['error' => 'Failed to contact RCSB search API.']);
    exit();
}

$data = json_decode($response, true);
if (!isset($data['result_set']) || count($data['result_set']) === 0) {
    echo json_encode(['error' => 'No matching PDB structure found.']);
    exit();
}

$pdb_entity = $data['result_set'][0]['identifier'];
[$pdb_id, $entity_id] = explode('_', $pdb_entity);

echo json_encode([
    'pdb_id' => $pdb_id,
    'entity_id' => $entity_id,
    'full_id' => $pdb_entity
]);
exit();