# 🚀 Quick Fix for Database Error

## The Problem
You're getting this error:
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'mssnkacc_website.schools' doesn't exist
```

**OR**

```
Could not open input file: artisan
```

**Cause:** Either migrations haven't been run, or you didn't upload the full Laravel project.

---

## ⚠️ CRITICAL: Upload ENTIRE Project First!

**You CANNOT just upload setup.php alone!**

You must upload the complete Laravel application including:
- ✅ vendor/ folder (~165 MB)
- ✅ app/ folder
- ✅ artisan file
- ✅ All other Laravel files

**See CPANEL_UPLOAD_CHECKLIST.md for detailed upload instructions.**

---

## ✅ Solution: Use setup.php

After uploading the complete project, setup.php will:
- ✅ Run all database migrations
- ✅ Create all tables
- ✅ Create admin account
- ✅ Set up storage links
- ✅ Optimize the application

---

## 🎯 How to Use

### Step 0: UPLOAD ENTIRE PROJECT FIRST!

**Read CPANEL_UPLOAD_CHECKLIST.md** for complete instructions.

Quick checklist:
1. ✅ Run `prepare_for_upload.bat` locally
2. ✅ Create ZIP of entire project (including vendor/)
3. ✅ Upload ZIP to cPanel
4. ✅ Extract to `public_html/quiz/`
5. ✅ Configure .env file
6. ✅ Set permissions (755 on storage/ and bootstrap/cache/)
7. ✅ Point document root to `public_html/quiz/public`

**ONLY THEN** proceed to Step 1 below.

---

### Step 1: Verify Upload

Make sure these exist on cPanel:
- `public_html/quiz/vendor/` (folder, ~165 MB)
- `public_html/quiz/artisan` (file)
- `public_html/quiz/app/` (folder)
- `public_html/quiz/.env` (file with database credentials)

### Step 2: Visit the Setup Page

Open your browser and go to:
```
https://mssnkac.com.ng/mssn_quiz/setup.php
```

**OR**

```
https://mssnkac.com.ng/quiz/setup.php
```

(Depends on where you uploaded it)

### Step 3: Enter Password

- Default password: `Setup@MSSN2025`
- (Change on line 18 of setup.php if needed)

### Step 4: Fill in Admin Details

The form will ask for:
- **Admin Name:** e.g., "MSSN Admin"
- **Admin Email:** e.g., "admin@mssnkac.com.ng"
- **Password:** Min 8 characters
- **Confirm Password:** Re-enter same password

### Step 5: Click "Start Complete Setup"

The script will:
1. ✅ Create all database tables (migrations)
2. ✅ Create your admin account
3. ✅ Set up storage links
4. ✅ Optimize the application
5. ✅ Set proper permissions

You'll see green text showing progress!

### Step 6: Delete setup.php

**IMPORTANT:** After successful setup, click the "Delete This File" button or manually delete `setup.php` via File Manager for security.

### Step 7: Login

Visit: `https://mssnkac.com.ng/mssn_quiz/login`

Login with your admin credentials!

---

## 📋 Quick Checklist

```
[ ] Upload setup.php to public_html/mssn_quiz/
[ ] Visit: https://mssnkac.com.ng/mssn_quiz/setup.php
[ ] Enter password: Setup@MSSN2025
[ ] Fill in admin details
[ ] Click "Start Complete Setup"
[ ] Wait for success message
[ ] Delete setup.php file
[ ] Login at /login
```

---

## 🆘 If You Get Errors

### "Database connection failed"
1. Check `.env` file has correct database credentials:
   ```
   DB_DATABASE=mssnkacc_quiz_app
   DB_USERNAME=mssnkacc_quiz_app
   DB_PASSWORD=jWN-$Yzt[unJo*gZ
   ```
2. Verify database exists in cPanel → MySQL Databases
3. Check user has ALL PRIVILEGES

### "Admin already exists"
- An admin with that email already exists
- Either use different email OR skip admin creation
- You can login with existing admin account

### "Permission denied"
- Set permissions via File Manager:
  - `storage/` → 755
  - `bootstrap/cache/` → 755

---

## ⚡ Super Quick Commands

**If you have file manager access:**

1. Upload `setup.php`
2. Set `.env` with database credentials
3. Visit: `https://mssnkac.com.ng/mssn_quiz/setup.php`
4. Password: `Setup@MSSN2025`
5. Fill form → Submit
6. Delete `setup.php`
7. Done! 🎉

---

## 🎊 That's It!

This one script fixes everything:
- ✅ Creates all database tables
- ✅ Creates admin account
- ✅ Sets up the application
- ✅ Ready to use!

**Your app will be live at:**
https://mssnkac.com.ng/mssn_quiz
