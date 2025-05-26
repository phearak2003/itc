<?php
include 'connection.php';

// Check if the blood_donation database exists
$dbCheck = $mysqli->query("SHOW DATABASES LIKE 'blood_donation'");
if ($dbCheck->num_rows === 0) {
    die("❌ Database 'blood_donation' does not exist.");
}

// Select the database
$mysqli->select_db("blood_donation");

// Get all tables from the database
$tables = [];
$result = $mysqli->query("SHOW TABLES");
if ($result) {
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
    $result->free();
} else {
    die("❌ Failed to fetch tables: " . $mysqli->error);
}

// Output file
$outputFile = 'export.sql';
$file = fopen($outputFile, 'w');

if (!$file) {
    die("❌ Failed to open file for writing.");
}

fwrite($file, "SET FOREIGN_KEY_CHECKS=0;\n\n");
foreach ($tables as $table) {

    fwrite($file, "-- Exporting table: $table\n");

    $result = $mysqli->query("SELECT * FROM `$table`");
    if (!$result) {
        fwrite($file, "-- Failed to fetch data from $table: " . $mysqli->error . "\n");
        continue;
    }

    while ($row = $result->fetch_assoc()) {
        $columns = array_keys($row);
        $values = array_map(function ($value) use ($mysqli) {
            if (is_null($value)) return "NULL";
            return "'" . $mysqli->real_escape_string($value) . "'";
        }, array_values($row));

        $insert = sprintf(
            "INSERT INTO `%s` (%s) VALUES (%s);",
            $table,
            implode(", ", array_map(fn($col) => "`$col`", $columns)),
            implode(", ", $values)
        );

        fwrite($file, $insert . "\n");
    }

    fwrite($file, "\n");
    $result->free();
}
fwrite($file, "SET FOREIGN_KEY_CHECKS=1;");

fclose($file);
$mysqli->close();

echo "✅ Export completed. Data written to $outputFile";
