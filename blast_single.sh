#!/bin/bash

# accession_id
acc=$1
fasta_file="results/blast_cache/${acc}.fasta"
output_file="results/blast_cache/${acc}_blast.txt"

export PATH=$PATH:/localdisk/home/ubuntu-software/blast216/ReleaseMT/bin

# run blastp
blastp -query "$fasta_file" \
  -db "/localdisk/home/ubuntu-software/blast216/ReleaseMT/ncbidb/nr" \
  -out "$output_file" \
  -evalue 1e-5 -outfmt 6 -max_target_seqs 10 -num_threads 8