#!/bin/bash
export PATH=$PATH:/home/s2703447/edirect
export PATH=$PATH:/localdisk/home/ubuntu-software/blast216/ReleaseMT/bin

export EMAIL=zhouboyuanqte@gmail.com
export NCBI_API_KEY=480a12e251ed500643ea6a34cd28721f6108

jobid="$1"
query="$2"

logfile="results/job_${jobid}_debug.log"
exec > "$logfile" 2>&1

echo "===== Job $jobid started ====="
echo "Query: $query"
echo "PATH: $PATH"
which esearch
which efetch
which clustalo
which plotcon


outdir="results"
prefix="${outdir}/job_${jobid}"

echo "Processing: Job ID: $jobid"
echo "Query: ${query}"

esearch -db protein -query "${query}" | efetch -format fasta > "${prefix}.fasta"

if [[ ! -s "${prefix}.fasta" ]]; then
    echo "Job $jobid - No sequences were detected, terminating analysis! "
    exit 1
fi

echo "Capture successful! File saved as: ${prefix}.fasta"


echo "Performing multiple sequence alignment..."
clustalo -i "${prefix}.fasta" -o "${prefix}_aligned.fasta" --outfmt=fasta


echo "Generating conservation plot..."
plotcon -sequence "${prefix}_aligned.fasta" -goutfile "${prefix}_plot" -graph png -winsize 4

bash scan_motifs.sh "$jobid"

echo "Job ${jobid} Analysis complete!"
echo "- Original Sequence: ${prefix}.fasta"
echo "- Aligned Sequence: ${prefix}_aligned.fasta"
echo "- Image Output: ${prefix}_plot.png"
echo "- Motif text: $motif_output"
echo "- BLAST Output: ${prefix}_blast.txt"