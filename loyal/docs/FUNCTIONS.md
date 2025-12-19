# Project Documentation

## Overview
This markdown file provides documentation for the main function modules included in the ceremony2/loyal dashboard system. Each module is described with its purpose and key functions.

---

## Included Function Modules

### 1. sql.php
Handles all database operations:
- CRUD operations (select, insert, update, delete)
- Logging of database actions
- Escaping strings for security

### 2. notification.php
Manages notifications:
- Sending notifications via external APIs
- Email and admin notifications for orders
- WhatsApp notifications and link shortener

### 3. payment.php
Handles payment logic:
- Cart price calculations
- Voucher and discount logic
- Payment API integration and status checking
- International shipping calculation

### 4. general.php
General utility functions:
- File search, string manipulation
- Directional text (EN/AR)
- Random string and order ID generation
- Encryption/decryption and QR code generation

### 5. cart.php
Manages cart operations:
- Cart ID and item management
- Price and quantity calculations
- Loading cart items for display

### 6. svg.php
SVG and icon rendering functions:
- Returns SVG markup for cart, close, and info icons

### 7. system.php
System-level utilities:
- User login status and profile actions
- Category and item management
- Image upload and PDF generation
- Manifest file generation

### 8. vouchers.php
Voucher and discount management:
- Voucher application to items and cart
- Double discount logic

### 9. products.php
Product-related calculations:
- Product discount calculation
- Extras price calculation for orders

### 10. currency.php
Currency and exchange rate handling:
- Fetches exchange rates
- Currency selection and conversion
- Currency dropdown rendering

---

## Usage
Each module is included via the main `functions.php` file in `dashboard/includes/`. Functions are available globally and are used throughout the dashboard for e-commerce, notification, and system management features.

---

## Notes
- All external API calls use cURL.
- Security: SQL queries use prepared statements and string escaping.
- The system supports multi-language and multi-currency features.
- Cart and voucher logic is tightly integrated for flexible discounting.

---

_Last updated: December 19, 2025_
