#!/usr/bin/python3
import sys
import json
import matplotlib.pyplot as plt
import os

if len(sys.argv) < 1:
    print("Usage: draw_motifs.py <job_id>")
    sys.exit(1)

job_id = sys.argv[1]
json_file = f"results/job_{job_id}_motifs.json"
job_dir = f"results/job_{job_id}"
os.makedirs(job_dir, exist_ok=True)

if not os.path.exists(json_file):
    print(f"JSON motif file not found: {json_file}")
    sys.exit(1)

with open(json_file, 'r') as f:
    motifs = json.load(f)

accession_groups = {}
for m in motifs:
    acc = m['accession_id']
    accession_groups.setdefault(acc, []).append(m)

for acc_id, hits in accession_groups.items():
    plt.figure(figsize=(10, 2))
    ax = plt.gca()

    max_end = max(hit['end_pos'] for hit in hits)
    ax.hlines(0, 0, max_end, color='gray', linewidth=2)

    for hit in hits:
        start = hit['start_pos']
        end = hit['end_pos']
        ax.broken_barh([(start, end - start)], (-0.2, 0.4), facecolors='orange')
        ax.text(start, 0.3, hit['motif_name'], fontsize=8, color='darkred')

    ax.set_ylim(-1, 1)
    ax.set_xlim(0, max_end + 10)
    ax.set_yticks([])
    ax.set_xlabel("Amino acid position")
    ax.set_title(f"Motif Map for {acc_id}")
    plt.tight_layout()

    output_path = os.path.join(job_dir, f"{acc_id}_motifmap.png")
    plt.savefig(output_path)
    plt.close()
    print(f"Saved: {output_path}")