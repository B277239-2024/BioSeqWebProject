<?php
require_once 'db_config.php';    // 数据库连接
require_once 'parse_motifs.php'; // 包含你刚刚写好的函数

$job_id = 32; // 替换成你想测试的 job_id
parseMotifFile($job_id, $pdo);

echo "Motif parsing completed for job_id = $job_id\n";
?>
