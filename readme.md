# Grab Student App

A ride-hailing web application designed for students to book and manage rides easily.

---

## 🚀 Setup Guide

### Step 1 - Configure XAMPP
1. **Install XAMPP**: If you haven't installed XAMPP yet, download it from [Apache Friends](https://www.apachefriends.org/).
2. **Setup XAMPP**: Follow the installation instructions. The setup will automatically create a `xampp` folder on your PC.
3. **Move Project Files**: Navigate to `xampp/htdocs`, then copy and paste the project files inside the `htdocs` folder.
4. **Start Services**: Open the XAMPP control panel and press the **'Start'** button for **Apache** and **MySQL**.

### Step 2 - Import Database
1. Open your web browser (e.g., Chrome, Safari, Edge) and visit: `localhost/phpmyadmin`.
2. If you have set up a username and password before, log in using those credentials. Otherwise, you'll be directed to the dashboard automatically.
3. Create a new database named **`grabstudent`**.
4. Import the `grabstudent.sql` file into the database.

### Step 3 - Run the Application
1. After successfully importing the database, the application is ready to use.
2. If you have set up a custom **username** and **password** in phpMyAdmin, update the **`connect.php`** file accordingly.
3. Open your web browser and enter:
   ```
   localhost/GrabStudent/General/welcome.html
   ```

---

## 🔑 Default Login Credentials

| Role      | Username / Matrics No | Password     |
|-----------|----------------------|-------------|
| **Admin** | `AD1`                | `admin1`    |
| **Driver** | `DR1` or `DR2`      | `driver1` or `driver2` |
| **Passenger** | `shu`            | `passenger1` |

---

## 🛠 Technologies Used
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP, MySQL
- **Server:** XAMPP (Apache & MySQL)

---

## ❓ Troubleshooting
- **Apache or MySQL Not Starting?**
  - Ensure no other application (like Skype) is using port `80` or `3306`.
  - Try changing the ports in the XAMPP settings.

- **Database Import Fails?**
  - Make sure `grabstudent.sql` is correctly formatted.
  - Ensure you have created the `grabstudent` database before importing.

- **Login Issues?**
  - Double-check the default credentials.
  - Verify that `connect.php` has the correct database credentials.

---

## 📜 License
This project is open-source and available for personal and educational use.

---

**Happy Coding! 🚀**

