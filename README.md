# PHP Business Accounting Cashbook

A simple, lightweight Business Accounting Cashbook application built with PHP. It uses a CSV file as a database to store transaction records, making it easy to deploy without a complex database setup. The UI is designed with HTML5, Bootstrap 5, and JavaScript, following HCI principles for a user-friendly experience.

## Features

- **Dashboard**: Real-time view of Total Income, Total Expenses, and Current Balance.
- **Transaction Management**: Easily add income and expense records.
- **Data Persistence**: Transactions are stored in a CSV file (`data/transactions.csv`).
- **Responsive Design**: Fully responsive layout using Bootstrap 5, suitable for desktop and mobile devices.
- **User Feedback**: Toast notifications for actions and clear visual cues for income/expenses.

## Technologies Used

- **Backend**: PHP
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework**: Bootstrap 5
- **Icons**: Bootstrap Icons
- **Data Storage**: CSV (Comma Separated Values)

## Installation & Setup

1.  **Prerequisites**:
    - A local web server with PHP support (e.g., XAMPP, WAMP, MAMP, or built-in PHP server).

2.  **Installation**:
    - Clone this repository or copy the files to your web server's root directory (e.g., `htdocs` in XAMPP).
    ```bash
    git clone <repository-url>
    ```

3.  **Permissions**:
    - Ensure the `data` directory is writable by the web server. The application will attempt to create the directory and `transactions.csv` file if they don't exist.
    - On Linux/Mac: `chmod -R 777 data` (or appropriate ownership).
    - On Windows: Ensure the user running the web server has write access to the folder.

4.  **Running the App**:
    - Open your web browser and navigate to the project folder (e.g., `http://localhost/demo/test2/`).

## Project Structure

```
├── assets/
│   ├── css/
│   │   └── style.css      # Custom styles
│   └── js/
│       └── app.js         # Frontend logic (Fetch API, DOM manipulation)
├── data/
│   └── transactions.csv   # Data storage (auto-created)
├── includes/
│   └── TransactionManager.php # Class for handling CSV operations
├── api.php                # Backend API endpoint
├── index.php              # Main application interface
└── README.md              # Project documentation
```

## License

This project is open-source and available for educational and personal use.
