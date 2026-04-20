# XAMPP Stuff

## Setup Instructions

### 1. Install XAMPP
1. Download XAMPP
2. Install it (default path is fine)
3. Open the XAMPP Control Panel

---

### 2. Start Services
In the XAMPP Control Panel:
- Start Apache
- Start MySQL (Now used so it is required)

---

### 3. Place Project Files

Move your project folder into:

C:\xampp\htdocs\ (Keep the files in a folder! Do not just paste the separate files into htdocs)

---

### 4. Setup Database

Go to http://localhost/phpmyadmin/

1. At the top, go to the SQL tab
2. Put in the SQL code from DB.sql (You can either drag and drop the file into the text box or you can just copy and paste the code from the file)
3. Press Go at the bottom of the screen

---

### 5. Open in Browser

Go to:

http://localhost/XAMPP_Stuff/ (If you change the name of the folder the files are in then change the URL as well)

---

Notes:

I hard coded an admin account into the database setup for testing. The password is hashed and so it won't be readable, even if you check the database directly.

User: admin
Pass: admin123
