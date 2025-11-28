<?php

class TransactionManager {
    private $filepath;

    public function __construct($filepath) {
        $this->filepath = $filepath;
        $dir = dirname($this->filepath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        if (!file_exists($this->filepath)) {
            // Create file with headers if it doesn't exist
            $file = fopen($this->filepath, 'w');
            if ($file) {
                fputcsv($file, ['id', 'date', 'description', 'type', 'amount']);
                fclose($file);
            }
        }
    }

    public function getAll() {
        $transactions = [];
        if (($handle = fopen($this->filepath, "r")) !== FALSE) {
            $headers = fgetcsv($handle); // Skip header
            while (($data = fgetcsv($handle)) !== FALSE) {
                // Combine headers with data for easier access if needed, 
                // but for now relying on index order: 0:id, 1:date, 2:desc, 3:type, 4:amount
                if (count($data) >= 5) {
                    $transactions[] = [
                        'id' => $data[0],
                        'date' => $data[1],
                        'description' => $data[2],
                        'type' => $data[3],
                        'amount' => (float)$data[4]
                    ];
                }
            }
            fclose($handle);
        }
        // Return newest first
        return array_reverse($transactions);
    }

    public function add($date, $description, $type, $amount) {
        // Simple ID generation: timestamp + random
        $id = uniqid();
        $file = fopen($this->filepath, 'a');
        // Lock file for writing
        if (flock($file, LOCK_EX)) {
            fputcsv($file, [$id, $date, $description, $type, $amount]);
            flock($file, LOCK_UN);
        }
        fclose($file);
        return $id;
    }

    public function delete($id) {
        $tempFile = tempnam(sys_get_temp_dir(), 'csv');
        $input = fopen($this->filepath, 'r');
        $output = fopen($tempFile, 'w');
        
        $deleted = false;
        
        if ($input && $output) {
            while (($data = fgetcsv($input)) !== FALSE) {
                if ($data[0] !== $id) {
                    fputcsv($output, $data);
                } else {
                    $deleted = true;
                }
            }
            fclose($input);
            fclose($output);
            
            if ($deleted) {
                rename($tempFile, $this->filepath);
            } else {
                unlink($tempFile);
            }
        }
        return $deleted;
    }
}
