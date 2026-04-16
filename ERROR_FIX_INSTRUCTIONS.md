# 🚨 CRITICAL ERROR: Project Not Uploaded

## What Happened?

You uploaded **ONLY** `setup.php` to cPanel, but you need to upload the **ENTIRE Laravel project**.

### The Error Messages Mean:
```
Could not open input file: artisan
```
→ The Laravel project files are not uploaded

```
AppServiceProvider.php: No such file or directory
```
→ The app/ folder is missing

---

## ✅ What You Need to Do

### 1️⃣ Prepare Project Locally

Open PowerShell in the project folder:

```powershell
cd C:\Users\ITAPPS002\OneDrive\Documents\mssn_quiz

# Run preparation script
.\prepare_for_upload.bat

# Create ZIP file (this may take 5-10 minutes)
Compress-Archive -Path * -DestinationPath mssn_quiz.zip -Force
```

**Expected ZIP size:** 180-200 MB (if smaller, vendor/ is missing!)

---

### 2️⃣ Upload to cPanel

1. **Login to cPanel** → File Manager

2. **Navigate to** `public_html/quiz/`

3. **Delete everything** currently in the quiz folder (the incomplete upload)

4. **Upload** `mssn_quiz.zip` (wait 5-10 minutes for large file)

5. **Right-click** `mssn_quiz.zip` → **Extract**

6. **Move extracted files** from `mssn_quiz/` subfolder up to `quiz/` folder

7. **Final structure should be:**
   ```
   public_html/quiz/
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── public/
   ├── resources/
   ├── routes/
   ├── storage/
   ├── vendor/         ← 165 MB folder
   ├── artisan         ← File
   ├── composer.json
   ├── .env
   ├── setup.php
   └── verify.php
   ```

---

### 3️⃣ Configure .env File

In File Manager, edit `.env` file:

```env
APP_KEY=base64:YOUR_KEY_FROM_PREPARE_SCRIPT

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=mssnkacc_quiz_app
DB_USERNAME=mssnkacc_quiz_app
DB_PASSWORD=jWN-$Yzt[unJo*gZ
```

---

### 4️⃣ Set Permissions

Right-click each folder → Change Permissions → Set to **755**:
- `storage/` → 755
- `bootstrap/cache/` → 755

---

### 5️⃣ Verify Upload

Visit: **https://mssnkac.com.ng/quiz/verify.php**

This will check:
- ✅ vendor/ folder exists (~165 MB)
- ✅ artisan file exists
- ✅ app/ folder exists
- ✅ .env configured
- ✅ Database connection works
- ✅ Permissions correct

**You must see all green checkmarks!**

---

### 6️⃣ Run Setup

Visit: **https://mssnkac.com.ng/quiz/setup.php**

1. Enter password: `Setup@MSSN2025`
2. Fill in admin details
3. Click "Start Complete Setup"
4. Wait for success
5. Delete both verify.php and setup.php

---

## 📋 Quick Checklist

```
[ ] Run prepare_for_upload.bat
[ ] Create ZIP of entire project
[ ] Verify ZIP is 180-200 MB
[ ] Upload ZIP to cPanel
[ ] Extract to public_html/quiz/
[ ] Edit .env file
[ ] Set permissions (755)
[ ] Visit verify.php - all checks pass
[ ] Visit setup.php - complete setup
[ ] Delete verify.php and setup.php
[ ] Test login
```

---

## ❌ Common Mistakes

### Mistake 1: Uploading only setup.php
**Error:** "Could not open input file: artisan"
**Fix:** Upload entire project

### Mistake 2: Missing vendor/ folder
**Error:** "Class not found"
**Fix:** Run `composer install` locally, include vendor/ in ZIP

### Mistake 3: Wrong folder structure
**Error:** Files not found
**Fix:** Extract properly, move files to correct location

### Mistake 4: No APP_KEY in .env
**Error:** "No application encryption key"
**Fix:** Copy APP_KEY from prepare_for_upload.bat output

---

## 🎯 Expected Results

**After upload + verify.php:**
```
✅ vendor/ folder: EXISTS (165 MB)
✅ artisan file: EXISTS
✅ app/ folder: EXISTS
✅ .env file: EXISTS (APP_KEY configured)
✅ storage/ folder: EXISTS (Permissions: 755)
✅ bootstrap/cache/: EXISTS (Permissions: 755)
✅ public/ folder: EXISTS
✅ composer.json: EXISTS
✅ Database Connection: SUCCESS!

✅ ALL CHECKS PASSED!
```

**After setup.php:**
```
✅ Database migrations completed successfully!
✅ All directories ready
✅ Storage link created
✅ Admin account created successfully!
✅ Permissions set
✅ Application optimized

🎉 SETUP COMPLETED SUCCESSFULLY! 🎉
```

---

## 🆘 Need More Help?

1. **Read:** `CPANEL_UPLOAD_CHECKLIST.md` (complete guide)
2. **Use:** `verify.php` (check upload status)
3. **Then:** `setup.php` (run setup)

---

## 🔑 Key Points

1. **You MUST upload the ENTIRE project** (not just setup.php)
2. **vendor/ folder is CRITICAL** (~165 MB)
3. **artisan file is REQUIRED**
4. **Use verify.php BEFORE setup.php**
5. **Delete both scripts after success**

---

## 📦 Files Included

- `CPANEL_UPLOAD_CHECKLIST.md` - Complete upload guide
- `verify.php` - Check if upload is correct
- `setup.php` - Run migrations and create admin
- `SETUP_SCRIPT_GUIDE.md` - How to use setup.php
- `THIS_FILE.md` - Quick error fix

**Start with CPANEL_UPLOAD_CHECKLIST.md!**
