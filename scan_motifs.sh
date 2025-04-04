#!/bin/bash

jobid="$1"
fasta_file="results/job_${jobid}.fasta"
motif_output="results/job_${jobid}_motifs.txt"

echo "Running PROSITE motif scan with patmatmotifs for job $jobid..."
> "$motif_output"

csplit -s -z -f "results/job_${jobid}_seq" "$fasta_file" '/^>/' '{*}'
i=0
for seqfile in results/job_${jobid}_seq*; do
    temp_out="results/job_${jobid}_motif${i}.txt"
    patmatmotifs -sequence "$seqfile" -outfile "$temp_out" -auto
    cat "$temp_out" >> "$motif_output"
    rm "$temp_out"
    ((i++))
done

rm results/job_${jobid}_seq*
echo "Motif scan for job $jobid complete."