<?php

require_once 'charity.php';
require_once 'donation.php';
require_once 'validation.php';
require_once 'importCSV.php';
require_once 'checkAndCreateTables.php';

$pdo = dbConnect();
checkAndCreateTables($pdo);

function displayMenu() {
    echo "\n";
    echo "1. View Charities\n";
    echo "2. Add Charity\n";
    echo "3. Edit Charity\n";
    echo "4. Delete Charity\n";
    echo "5. Add Donation\n";
    echo "6. View Donations\n";
    echo "7. Import Charities from CSV\n";
    echo "8. Exit\n";
    echo "Choose an option: ";
}

while (true) {
    displayMenu();
    $choice = trim(fgets(STDIN));

    switch ($choice) {
        case '1':
            $allCharities = Charity::getAllCharities();
            if (empty($allCharities)) {
                echo "There are no charities to display.\n";
            } else {
                Charity::viewCharities();
            }
            break;

            case '2':
                $id = null;
                $name = null;
                $email = null;
            
                while (true) {
                    if ($id === null) {
                        echo "Enter Charity ID (or type 'back' to return to the menu): ";
                        $input = trim(fgets(STDIN));
                        if (strtolower($input) === 'back') {
                            break;
                        }
                        $validationResult = validateCharityId($input, 'charities');
                        if ($validationResult !== true) {
                            echo $validationResult . "\n";
                        } else {
                            $id = $input;
                        }
                    }
            
                    if ($id !== null && $name === null) {
                        echo "Enter Charity Name (or type 'back' to return to the menu): ";
                        $input = trim(fgets(STDIN));
                        if (strtolower($input) === 'back') {
                            $id = null;
                            continue;
                        }
                        $validationResult = validateName($input);
                        if ($validationResult !== true) {
                            echo $validationResult . "\n";
                        } else {
                            $name = $input;
                        }
                    }

                    if ($id !== null && $name !== null && $email === null) {
                        echo "Enter Representative Email (or type 'back' to return to the menu): ";
                        $input = trim(fgets(STDIN));
                        if (strtolower($input) === 'back') {
                            $name = null;
                            continue;
                        }
                        $validationResult = validateEmailSyntax($input);
                        if ($validationResult !== true) {
                            echo $validationResult . "\n";
                        } else {
                            $email = $input;
                        }
                    }
            
                    if ($id !== null && $name !== null && $email !== null) {
                        Charity::addCharity(new Charity($id, $name, $email));
                        echo "Charity added successfully!\n";
                        break;
                    }
                }
                break;

                case '3':
                    $id = null;
                    $name = null;
                    $email = null;
                
                    while (true) {
                        if ($id === null) {
                            echo "Enter Charity ID to edit (or type 'back' to return to the menu): ";
                            $input = trim(fgets(STDIN));
                            if (strtolower($input) === 'back') {
                                break;
                            }
                            $validationResult = validateIdExists($input, 'charities');
                            if ($validationResult !== true) {
                                echo $validationResult . "\n";
                            } else {
                                $id = $input;
                            }
                        }
                
                        if ($id !== null && $name === null) {
                            echo "Enter new Charity Name (or type 'back' to return to the menu): ";
                            $input = trim(fgets(STDIN));
                            if (strtolower($input) === 'back') {
                                $id = null;
                                continue; 
                            }
                            $validationResult = validateName($input);
                            if ($validationResult !== true) {
                                echo $validationResult . "\n";
                            } else {
                                $name = $input;
                            }
                        }
                
                        if ($id !== null && $name !== null && $email === null) {
                            echo "Enter new Representative Email (or type 'back' to return to the menu): ";
                            $input = trim(fgets(STDIN));
                            if (strtolower($input) === 'back') {
                                $name = null;
                                continue;
                            }
                            $validationResult = validateEmailSyntax($input);
                            if ($validationResult !== true) {
                                echo $validationResult . "\n";
                            } else {
                                $email = $input;
                            }
                        }
                
                        if ($id !== null && $name !== null && $email !== null) {
                            Charity::editCharity($id, $name, $email);
                            echo "Charity edited successfully!\n";
                            break;
                        }
                    }
                    break;
            
        case '4':
            while (true) {
                echo "Enter Charity ID to delete (or type 'back' to return to the menu): ";
                $id = trim(fgets(STDIN));

                if (strtolower($id) === 'back') {
                    break;
                }

                $idValidation = validateIdExists($id, 'charities');
                if ($idValidation !== true) {
                    echo $idValidation . "\n";
                    continue;
                }

                echo "Are you sure you want to delete this charity? Type 'yes' to confirm: ";
                $confirmation = trim(fgets(STDIN));
                if (strtolower($confirmation) === 'yes') {
                    Charity::deleteCharity($id);
                    echo "Charity deleted successfully!\n";
                } else {
                    echo "Deletion cancelled.\n";
                }

                break;
            }
            break;

            case '5':
                $donationId = null;
                $donorName = null;
                $amount = null;
                $charityId = null;
                $date = null;
                $time = null;
                $currentField = 'donationId';
            
                while (true) {
                    if ($currentField === 'donationId') {
                        while (true) {
                            echo "Enter Donation ID (Auto-generated if left blank, type 'back' to return to the menu): ";
                            $input = trim(fgets(STDIN));
            
                            if (strtolower($input) === 'back') {
                                break 2;
                            }
            
                            if ($input !== '') {
                                $validationResult = validateDonationId($input);
                                if ($validationResult !== true) {
                                    echo $validationResult . "\n";
                                    continue;
                                }
                            }
            
                            $donationId = $input;
                            $currentField = 'donorName';
                            break;
                        }
                    }
            
                    if ($currentField === 'donorName') {
                        echo "Enter Donor Name (or type 'back' to return): ";
                        $input = trim(fgets(STDIN));
                        if (strtolower($input) === 'back') {
                            $donationId = null;
                            $currentField = 'donationId';
                            continue;
                        }
                        $validationResult = validateName($input);
                        if ($validationResult !== true) {
                            echo $validationResult . "\n";
                        } else {
                            $donorName = $input;
                            $currentField = 'amount';
                        }
                    }
            
                    if ($currentField === 'amount') {
                        echo "Enter Donation Amount (or type 'back'): ";
                        $input = trim(fgets(STDIN));
                        if (strtolower($input) === 'back') {
                            $donorName = null;
                            $currentField = 'donorName';
                            continue;
                        }
                        $validationResult = validateAmount($input);
                        if ($validationResult !== true) {
                            echo $validationResult . "\n";
                        } else {
                            $amount = $input;
                            $currentField = 'charityId';
                        }
                    }
            
                    if ($currentField === 'charityId') {
                        echo "Enter Charity ID for Donation (or type 'back'): ";
                        $input = trim(fgets(STDIN));
                        if (strtolower($input) === 'back') {
                            $amount = null; 
                            $currentField = 'amount';
                            continue; 
                        }
                        $validationResult = validateIdExists($input, 'charities');
                        if ($validationResult !== true) {
                            echo $validationResult . "\n";
                        } else {
                            $charityId = $input;
                            $currentField = 'date';
                        }
                    }
            
                    // Prompt for Date
                    if ($currentField === 'date') {
                        echo "Enter Date (YYYY-MM-DD) (or type 'back'): ";
                        $input = trim(fgets(STDIN));
                        if (strtolower($input) === 'back') {
                            $charityId = null;
                            $currentField = 'charityId';
                            continue;
                        }
                        $validationResult = validateDate($input);
                        if ($validationResult !== true) {
                            echo $validationResult . "\n";
                        } else {
                            $date = $input;
                            $currentField = 'time';
                        }
                    }
            
                    if ($currentField === 'time') {
                        echo "Enter Time (HH:MM) (or type 'back' to return): ";
                        $input = trim(fgets(STDIN));
                        if (strtolower($input) === 'back') {
                            $date = null;
                            $currentField = 'date';
                            continue;
                        }
                        $validationResult = validateTime($input);
                        if ($validationResult !== true) {
                            echo $validationResult . "\n";
                        } else {
                            $time = $input;
                            $donation = new Donation($donationId, $donorName, $amount, $charityId, $date, $time);
                            Donation::addDonation($donation);
                            echo "Donation added successfully!\n";
                            break;
                        }
                    }
                }
                break;

        case '6':
            Donation::viewDonations();
            break;

        case '7':
            echo "Enter the path to the CSV file (or type 'back' to return to the menu): ";
            $filePath = trim(fgets(STDIN));

            if (strtolower($filePath) === 'back') {
                break;
            }

            if (checkCSVStructure($filePath)) {
                importCharitiesFromCSV($filePath);
            } else {
                echo "CSV structure check failed. Import aborted.\n";
            }
            break;

        case '8':
            echo "Exiting program.\n";
            exit();

        default:
            echo "Invalid option. Please try again.\n";
    }
}
?>