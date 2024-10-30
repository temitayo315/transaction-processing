<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Transaction Processing System

<p>A simple backend transaction processing API developed in Laravel to handle concurrent transactions, maintain data integrity, and ensure basic security. This project allows users to create deposit and withdrawal transactions, retrieve their balance, and prevent issues like double-spending through concurrency control.</p>

## Features
<p>User Registration & Authentication: Users can register and authenticate to access endpoints.
Transaction Processing: Supports deposit and withdrawal transactions with concurrency control.
Balance Retrieval: Users can retrieve their current balance.
Concurrency Safety: Prevents double-spending with database locking.
Unit & Feature Tests: Includes automated tests for key functionalities.</p>

## Prerequisites
Ensure you have the following installed:
<ul>
<li>PHP (>= 8.0)</li>
<li>Composer</li>
<li>MySQL or SQLite (or any other supported database)</li>
<li>Laravel CLI (optional, but recommended)</li>
<li>Getting Started</li>
<li>Follow these steps to set up the project on your local machine.</li>
</ul>

1. Clone the Repository
bash
Copy code
git clone https://github.com/temitayo315/transaction-processing.git
cd transaction-processing
2. Install Dependencies
bash
Copy code
`composer install`
3. Set Up Environment Configuration
Copy the example environment file and configure it for your local setup:
bash
Copy code
cp .env.example .env
Open .env and update the following environment variables to match your database setup:
dotenv
Copy code
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=transaction-processing
DB_USERNAME=your_username
DB_PASSWORD=your_password
4. Generate Application Key
bash
Copy code
`php artisan key:generate`
5. Run Migrations
This will create the necessary tables in the database.

bash
Copy code
`php artisan migrate`

## Running the Application
To start the development server:

bash
Copy code
`php artisan serve`
The application should now be running at http://127.0.0.1:8000.

API Endpoints
1. User Registration
Endpoint: POST /api/user-registration
Description: Registers a new user.
Request Body:
json
Copy code
{
  "name": "John Doe",
  "email": "johndoe@example.com",
  "password": "password",
  "password_confirmation": "password"
}
Response: Returns the newly created user details.
2. User Login
Endpoint: POST /api/user-token
Description: Logs in the user and returns an access token.
Request Body:
json
Response:
json
{
  "token": "access_token_string"
}
3. Create a Transaction
Endpoint: POST /api/transaction
Description: Allows users to create a deposit or withdrawal transaction.
Headers: Authorization: Bearer {access_token}
Request Body:
json
Copy code
{
  "user_id": 1,
  "amount": 100.00,
  "type": "deposit" // or "withdrawal"
}
Response: Returns transaction details if successful.
4. Retrieve Balance
Endpoint: GET /api/balance
Description: Returns the user’s current balance.
Headers: Authorization: Bearer {access_token}
Response:
json
Copy code
{
  "balance": 100.00
}

## Running Tests
This project includes feature tests to verify the functionality and correctness of the transaction system.

1. Configure Test Database
Edit the .env.testing file to set up a test database (for SQLite, you can use an in-memory database):

dotenv
Copy code
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
2. Run Tests
Run the following command to execute all tests:

`php artisan test`
Key Test Cases
User Registration: Verifies that users can register and log in.
Authentication: Ensures only authenticated users can access transaction and balance endpoints.
Transaction Processing: Tests both deposit and withdrawal functionality, ensuring correct balance updates.
Balance Retrieval: Ensures that the correct balance is returned for each user.

## Scaling and Production Considerations
For a production setup and scalability improvements, consider the following:

Database Scaling: Use partitioning or sharding strategies as user count grows.
Queueing System: Implement job queues (e.g., with Redis) for handling high-frequency transactions asynchronously.
Caching: Cache balance data using Redis to reduce database load on high-read operations.
Load Balancing: Use load balancers to distribute requests across multiple instances of the application.
Containerization: Deploy the application in containers (e.g., Docker) for easier scaling and environment management.

## Troubleshooting
Common issues and fixes:

Database Connection Errors: Ensure your database configuration in .env is correct and that the database server is running.
Migration Errors: If you encounter migration issues, try running php artisan migrate:fresh to reset the database and apply migrations again.
Authentication Issues: Ensure that the access token is sent in the Authorization header when accessing protected endpoints.