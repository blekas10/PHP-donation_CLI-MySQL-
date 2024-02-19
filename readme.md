# Charity and Donation Management System

This Charity and Donation Management System is a command-line application designed to help manage charities and their donations efficiently. With this system, you can add, edit, and delete charity information, add donations, view all charities and donations, and import charity data from a CSV file.

## Features

- **View Charities**: List all registered charities.
- **Add a New Charity**: Add a new charity with a unique ID, name, and representative email.
- **Edit Charity Information**: Update the details of an existing charity.
- **Delete a Charity**: Remove a charity from the system.
- **Add a Donation**: Record a new donation with its details, including the donor name, amount, charity ID, date, and time.
- **View Donations**: List all donations made to charities.
- **Import Charities from CSV**: Bulk import charity data from a CSV file, with structure validation.

## Getting Started

### Prerequisites

- PHP 7.4 or higher installed on your system.
- Git installed on your system.

### Installation

1. Open Your IDE
   
2. Create and Open a New Folder
   
3. Open Terminal

4. Clone the repository to your local machine:
    ```bash
    git clone https://github.com/blekas10/PHP-donation_CLI.git
   
5. Navigate to the application directory:
    ```bash
    cd PHP-donation_CLI

6. Run the application:
    ```bash
    php cli.php

### Usage

After starting the application, you will be presented with a menu of options:

1. View Charities
2. Add Charity
3. Edit Charity
4. Delete Charity
5. Add Donation
6. View Donations
7. Import Charities from CSV
8. Exit

Choose an option by entering the corresponding number and follow the on-screen instructions.

### Importing Charities from CSV

To import charities, your CSV file should have the following structure:

| charities_id | charities_name | charities_representativeEmail |
|--------------|----------------|-------------------------------|
| 1            | Charity   | email1@example.com            |
| 2            | Charity Name   | email2@example.com            |

Ensure the CSV file is correctly formatted to avoid import errors.

Example Data for Testing
An example CSV file named exampleData.csv is provided with the application for testing the import functionality. If you wish to test importing charities, you can use this file as a reference or to perform an actual import.

Using Your Own Data
If you prefer to use your own data:

When prompted to enter the path to the CSV file, either provide the relative path from the application to your CSV file or place your CSV file directly in the application folder and enter its name.