<?php 
require_once 'dbConnect.php';
require_once 'validation.php'; 

function checkCSVStructure($filePath) {
    $requiredHeaders = ['charities_id', 'charities_name', 'charities_representativeEmail']; 
    $fileHandle = fopen($filePath, 'r');
    if ($fileHandle === false) {
        echo "Failed to open the file: $filePath\n";
        return false;
    }

    $errors = [];
    $headers = fgetcsv($fileHandle);
    if ($headers === false || array_diff($requiredHeaders, $headers)) {
        $errors[] = "CSV file does not have the required headers (charities_id, charities_name, charities_representativeEmail).";
    }

    $lineNumber = 1;
    while (($row = fgetcsv($fileHandle)) !== false) {
        $lineNumber++;
        if (count($row) != 3) {
            $errors[] = "Error on line $lineNumber: Each row must have exactly 3 columns.";
        }
    }

    fclose($fileHandle);

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "\n";
        }
        return false;
    } else {
        echo "CSV structure is correct.\n";
        return true;
    }
}

function importCharitiesFromCSV($filePath) {
    $conn = dbConnect();
    $fileHandle = fopen($filePath, 'r');
    if ($fileHandle === false) {
        echo "Failed to open the file: $filePath\n";
        return;
    }

    fgetcsv($fileHandle);

    $lineNumber = 1;
    $importCount = 0;

    while (($row = fgetcsv($fileHandle)) !== false) {
        $lineNumber++;
        
        if (count($row) < 3) {
            echo "Error on line $lineNumber: Not enough data.\n";
            continue;
        }

        [$id, $name, $email] = array_map('trim', $row);

        $idValidation = validateCharityId($id, 'charities');
        if ($idValidation !== true) {
            echo "Error on line $lineNumber: {$idValidation}\n";
            continue;
        }

        $nameValidation = validateName($name);
        if ($nameValidation !== true) {
            echo "Error on line $lineNumber: {$nameValidation}\n";
            continue;
        }

        $emailValidation = validateEmailSyntax($email);
        if ($emailValidation !== true) {
            echo "Error on line $lineNumber: {$emailValidation}\n";
            continue;
        }


        try {
            $stmt = $conn->prepare("INSERT INTO charities (id, name, representativeEmail) VALUES (?, ?, ?)");
            $stmt->execute([$id, $name, $email]);
            $importCount++;
        } catch (PDOException $e) {
            echo "Error on line $lineNumber: Failed to insert charity - " . $e->getMessage() . "\n";
            continue;
        }
    }

    fclose($fileHandle);

    echo $importCount > 0 ? "$importCount charities were successfully imported.\n" : "No charities were imported.\n";
}
?>