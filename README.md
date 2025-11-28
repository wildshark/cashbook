# PHP Business Accounting Cashbook

A comprehensive Business Accounting Cashbook application built with PHP. It uses CSV files as a database to store transaction records, making it easy to deploy without a complex database setup. The UI is designed with HTML5, Bootstrap 5, and JavaScript, following HCI principles for a user-friendly experience.

## Features

### Dashboard
- **Real-time Statistics**: View Total Income, Total Expenses, Petty Cash Total, and Current Balance
- **Color-coded Cards**: Visual distinction between different financial metrics
- **Responsive Layout**: Optimized for desktop and mobile devices

### Transaction Management
- **General Transactions**: Add income and expense records
- **Petty Cash Vouchers**: Specialized voucher system with payee and authorization tracking
- **Separate Storage**: Transactions and petty cash stored in separate CSV files
- **View Voucher Details**: Dedicated modal to view complete voucher information

### Data Operations
- **Export Functionality**: Download Cashbook or Petty Cash data as CSV files
- **Delete All**: Clear all data with double confirmation protection
- **Real-time Updates**: Instant UI updates after any data operation

### User Experience
- **Toast Notifications**: Clear feedback for all actions
- **Visual Indicators**: Badges and icons for transaction types
- **Confirmation Dialogs**: Protection against accidental deletions
- **Modern Design**: Custom color scheme with Material Design-inspired palette

## Technologies Used

- **Backend**: PHP 7.4+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework**: Bootstrap 5
- **Icons**: Bootstrap Icons
- **Data Storage**: CSV (Comma Separated Values)

## Installation & Setup

1.  **Prerequisites**:
    - A local web server with PHP support (e.g., XAMPP, WAMP, MAMP, or built-in PHP server)
    - PHP 7.4 or higher recommended

2.  **Installation**:
    - Clone this repository or copy the files to your web server's root directory (e.g., `htdocs` in XAMPP)
    ```bash
    git clone https://github.com/wildshark/cashbook.git
    cd cashbook
    ```

3.  **Permissions**:
    - Ensure the `data` directory is writable by the web server. The application will automatically create:
        - `data/transactions.csv` (general transactions)
        - `data/petty_cash.csv` (petty cash vouchers)
    - On Linux/Mac: `chmod -R 755 data` (or appropriate ownership)
    - On Windows: Ensure the user running the web server has write access to the folder

4.  **Running the App**:
    - Open your web browser and navigate to the project folder (e.g., `http://localhost/demo/test2/`)

## Project Structure

```
├── assets/
│   ├── css/
│   │   └── style.css           # Custom styles with CSS variables
│   └── js/
│       └── app.js              # Frontend logic (Fetch API, DOM manipulation)
├── data/
│   ├── transactions.csv        # General transactions storage (auto-created)
│   └── petty_cash.csv          # Petty cash vouchers storage (auto-created)
├── includes/
│   └── TransactionManager.php  # Class for handling CSV operations
├── api.php                     # Backend API endpoint (list, add, delete, export)
├── index.php                   # Main application interface
└── README.md                   # Project documentation
```

## API Endpoints

The application uses `api.php` with the following actions:

- `?action=list` - Retrieve all transactions (merged from both CSV files)
- `?action=add` - Add a new transaction or voucher (POST)
- `?action=delete` - Delete a specific transaction (POST)
- `?action=export_cashbook` - Download general transactions CSV
- `?action=export_petty` - Download petty cash CSV
- `?action=delete_all` - Clear all data (POST, requires confirmation)

## Usage

### Adding a General Transaction
1. Click "Add New" button
2. Fill in Date, Description, Type (Income/Expense), and Amount
3. Click "Save Transaction"

### Creating a Petty Cash Voucher
1. Click "Petty Cash Voucher" button
2. Fill in Date, Payee, Description, Amount, and Authorized By
3. Click "Create Voucher"
4. View voucher details by clicking the eye icon in the transaction list

### Exporting Data
- Click "Export Cashbook" to download general transactions
- Click "Export Petty Cash" to download petty cash vouchers

### Deleting Data
- Individual transactions: Click the trash icon next to each transaction
- All data: Click "Delete All CSV" (requires double confirmation)

## Color Scheme

The application uses a custom Material Design-inspired color palette:

- **Primary**: Indigo (#3F51B5, #757de8)
- **Accent**: Blue (#2196F3, #003f8f)
- **Text**: Dark Gray (#333333, #5c5c5c)
- **Background**: White/Light Gray (#FFFFFF, #f5f5f5)

## License

This project is open-source and available for educational and personal use.

## Credits

Developed by WildShark
© 2025 Cashbook Pro. All rights reserved.
