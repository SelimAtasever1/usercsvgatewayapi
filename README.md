# User CSV Gateway API

**User CSV Gateway API** is a Laravel-based application that processes Excel (CSV) files and saves user data to the database in batches. It utilizes Laravel Jobs for queueing and background processing, and implements validation before saving records.

## Features

- **CSV Import**: Upload CSV files containing user data.
- **Batch Processing**: Handles large imports efficiently by saving data in batches.
- **Background Jobs**: Uses Laravel Jobs to queue and process the CSV upload in the background.
- **Data Validation**: Ensures data integrity by validating user information before saving.

## Requirements

- PHP 8.0+
- Composer
- MySQL (or another supported database)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/usercsvgatewayapi.git

2. Install dependencies:
   ```bash
   composer install
   
3. Set up the .env file by copying the example:
   ```bash
   cp .env.example .env
   
4. Run the database migrations: 
   ```bash
   php artisan migrate

5. Set up the job queue (optional for background processing):
 
   ```bash
   php artisan queue:work

## Usage

- Upload a CSV file containing user data.
- The data is validated and saved to the database in batches.
- If any validation errors occur, they are reported, and invalid data is not saved.

## Testing
 ```bash
   php artisan test

## License
This project is open-source and available under the MIT License.
