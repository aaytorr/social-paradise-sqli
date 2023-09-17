<h1 align="center">🌴 Social Paradise SQLi 🌴</h1>

<p align="center">
  A modernized version of the original "Social Paradise" project. This repository aims to bring the project up to 2023 standards by introducing several improvements and fixes.
</p>

---

## 🚀 Key Improvements

- **🔁 Migration to MySQLi**: Transitioned from the deprecated MySQL extension to the more robust and secure MySQLi.
- **🔒 Enhanced Security**: Addressed and fixed multiple security vulnerabilities to ensure a safer user experience.
- **🐞 Bug Fixes**: Squashed numerous bugs that were present in the original codebase.
- **📂 Directory Structure**: Refined and organized directory names for better clarity and maintainability.
- **🔐 Password Hashing**: Updated the password hashing mechanism to utilize a more secure method.
  
## 📊 Progress

The project is approximately 60% complete. While significant progress has been made, there are still areas that require attention and refinement.

## 📝 Note

🌞 This was a summer project, and I won't be making further updates or changes. Feel free to fork and continue the development if you're interested! 🌞

## 🤝 Contributions

Contributions are welcome! If you'd like to help improve the project, please create a pull request or open an issue to discuss potential changes or fixes.

## 🙏 Acknowledgments

This project is based on the original "Social Paradise" source code. A big thank you to the original authors and contributors for laying the groundwork.

## 🛠 How to Setup

Follow these steps to get "Social Paradise SQLi" up and running:

### 📋 Prerequisites

- PHP 8.0 or higher
- MySQL Database
- Necessary dependencies to run PHP and MySQL

### 🚀 Installation

1. **Upload Files**: Transfer all the project files to your server.
2. **Database Setup**: Access your MySQL database (e.g., using phpMyAdmin) and import the `database.sql` file.
3. **Configuration**: Navigate to `Global.php` and update the following variables with your database details:
   - `$host`
   - `$username`
   - `$password`
   - `$database`

### 📌 Notes

- **Maintenance Page**: If you encounter a maintenance page, the password is "speaknow13". (A nod to Taylor Swift's album "Speak Now". Don't forget to stream "Speak Now (Taylor's Version)"! 🎶)
- **Admin Account**: The default admin account is "Isaac". If you're unable to access it, simply create a new account and copy its password hash to the "Isaac" account using phpMyAdmin or your preferred database management tool. This is a workaround due to the project's incomplete state, especially regarding the permission system which originally used usernames for certain permissions.



