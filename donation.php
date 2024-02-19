<?php
require_once 'dbConnect.php'; // Make sure this points to your database connection script

class Donation {
    public $id;
    public $donorName;
    public $amount;
    public $charityId;
    public $date;
    public $time;

    public function __construct($id, $donorName, $amount, $charityId, $date, $time) {
        $this->id = $id;
        $this->donorName = $donorName;
        $this->amount = $amount;
        $this->charityId = $charityId;
        $this->date = $date;
        $this->time = $time;
    }

    public static function getAllDonations() {
        $conn = dbConnect();
        $stmt = $conn->query("SELECT * FROM donations");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $donations = [];
        foreach ($results as $row) {
            $donations[] = new Donation($row['id'], $row['donorName'], $row['amount'], $row['charityId'], $row['date'], $row['time']);
        }
        return $donations;
    }

    public static function addDonation($donation) {
        $conn = dbConnect();
        $stmt = $conn->prepare("INSERT INTO donations (id, donorName, amount, charityId, date, time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$donation->id, $donation->donorName, $donation->amount, $donation->charityId, $donation->date, $donation->time]);
    }

    public static function viewDonations() {
        $donations = self::getAllDonations();
        if (empty($donations)) {
            echo "There are no donations to display.\n";
            return;
        }

        foreach ($donations as $donation) {
            echo "ID: {$donation->id}, Donor Name: {$donation->donorName}, Amount: {$donation->amount}, Charity ID: {$donation->charityId}, Date: {$donation->date}, Time: {$donation->time}\n";
        }
    }
}
?>