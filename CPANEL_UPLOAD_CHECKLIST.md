# 📦 cPanel Upload Checklist

## ⚠️ IMPORTANT: You Must Upload the ENTIRE Project First!

The error shows you only uploaded `setup.php` but **not the Laravel application**.

---

## ✅ Step-by-Step Upload Process

### Step 1: Prepare the Project Locally

1. **Run the preparation script**:
   ```powershell
   .\prepare_for_upload.bat
   ```
   
   This will:
   - Install all dependencies
   - Build assets
   - Generate APP_KEY
   - Create optimized production files

2. **Verify these folders exist**:
   ```
   ✓ app/
   ✓ bootstrap/
   ✓ config/
   ✓ database/
   ✓ public/
   ✓ resources/
   ✓ routes/
   ✓ storage/
   ✓ vendor/          ← CRITICAL! Must be included
   ✓ artisan          ← Must be present
   ✓ .env             ← Rename from .env.production
   ```

---

### Step 2: Create ZIP File

**Option A: Using Windows Explorer**
1. Select ALL files and folders (Ctrl+A)
2. Right-click → Send to → Compressed (zipped) folder
3. Name it: `mssn_quiz.zip`

**Option B: Using PowerShell**
```powershell
Compress-Archive -Path * -DestinationPath mssn_quiz.zip
```

**Critical Files to Include:**
```
✓ vendor/          (165 MB - Laravel core)
✓ app/             (Your application code)
✓ public/          (Assets and index.php)
✓ artisan          (Command-line tool)
✓ composer.json
✓ .env
✓ setup.php        (Setup script)
```

---

### Step 3: Upload to cPanel

1. **Login to cPanel**: https://mssnkac.com.ng:2083

2. **Go to File Manager**

3. **Navigate to**: `public_html/`

4. **Create/verify quiz folder exists**: `public_html/quiz/`

5. **Upload the ZIP file**:
   - Click "Upload" button
   - Select `mssn_quiz.zip`
   - Wait for upload to complete (may take 5-10 minutes)

6. **Extract the ZIP**:
   - Right-click on `mssn_quiz.zip`
   - Click "Extract"
   - Extract to: `public_html/quiz/`
   - Wait for extraction to complete

7. **Verify files extracted**:
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
   ├── vendor/          ← MUST be present!
   ├── artisan          ← MUST be present!
   ├── .env
   └── setup.php
   ```

---

### Step 4: Configure .env File

1. **Locate the .env file** in `public_html/quiz/`

2. **Edit it** (right-click → Edit)

3. **Update these lines**:
   ```env
   APP_NAME="MSSN Quiz"
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:YOUR_KEY_HERE    ← From prepare_for_upload.bat output
   APP_URL=https://mssnkac.com.ng/mssn_quiz

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=mssnkacc_quiz_app
   DB_USERNAME=mssnkacc_quiz_app
   DB_PASSWORD=jWN-$Yzt[unJo*gZ

   SESSION_DRIVER=file
   SESSION_LIFETIME=120
   SESSION_PATH=/mssn_quiz
   SESSION_DOMAIN=mssnkac.com.ng
   ```

4. **Save the file**

---

### Step 5: Set Permissions

Using File Manager, set these permissions:

1. **storage/** → Right-click → Change Permissions → 755
2. **bootstrap/cache/** → Right-click → Change Permissions → 755

---

### Step 6: Configure Document Root

**CRITICAL:** Point your domain/subdomain to the `public` folder

1. In cPanel, go to **Domains** or **Addon Domains**

2. Set document root to: `public_html/quiz/public`
   
   **NOT** `public_html/quiz/` ← Wrong!
   
   **BUT** `public_html/quiz/public` ← Correct!

---

### Step 7: Run Setup Script

**NOW** you can run setup.php:

1. Visit: https://mssnkac.com.ng/mssn_quiz/setup.php

2. Enter password: `Setup@MSSN2025`

3. Fill in admin details

4. Click "Start Complete Setup"

5. Wait for success ✅

6. Delete setup.php

---

## 🎯 Quick Verification Checklist

Before running setup.php, verify:

```
[ ] Uploaded entire project (not just setup.php)
[ ] vendor/ folder exists and is 165+ MB
[ ] artisan file exists in root
[ ] .env file configured with correct database credentials
[ ] storage/ permissions set to 755
[ ] bootstrap/cache/ permissions set to 755
[ ] Document root points to public/ folder
```

---

## 🔍 Common Upload Mistakes

### ❌ Wrong: Only uploading setup.php
**Error:** "Could not open input file: artisan"

**Solution:** Upload the ENTIRE project

---

### ❌ Wrong: Uploading without vendor/ folder
**Error:** "Class not found" errors

**Solution:** 
1. Run `composer install` locally
2. Include vendor/ folder in ZIP
3. Re-upload

---

### ❌ Wrong: Document root pointing to wrong folder
**Error:** 404 or Laravel files exposed

**Solution:** Point to `public_html/quiz/public`

---

### ❌ Wrong: Missing .env file
**Error:** "No application encryption key"

**Solution:** 
1. Rename `.env.production` to `.env`
2. Run `php artisan key:generate` locally
3. Copy APP_KEY to .env

---

## 📊 Expected File Sizes

After extraction, you should see:

```
vendor/         ~165 MB   (Laravel + dependencies)
public/         ~15 MB    (Compiled assets)
app/            ~1 MB     (Your code)
storage/        ~1 MB     (Logs, cache)
Total:          ~185 MB
```

**If your upload is < 50 MB**, you're missing vendor/ folder!

---

## 🚀 Quick Upload Commands

### Prepare Locally:
```powershell
cd C:\Users\ITAPPS002\OneDrive\Documents\mssn_quiz
.\prepare_for_upload.bat
Compress-Archive -Path * -DestinationPath mssn_quiz.zip
```

### Upload to cPanel:
1. File Manager → Upload mssn_quiz.zip
2. Extract to public_html/quiz/
3. Edit .env
4. Set permissions (755)
5. Visit setup.php

---

## ✅ Success Indicators

You'll know it worked when:

1. ✅ setup.php shows the form (not errors)
2. ✅ Migrations run successfully
3. ✅ Admin account created
4. ✅ Can login at /login
5. ✅ Dashboard loads correctly

---

## 🆘 Still Getting Errors?

### "Could not open input file: artisan"
→ You didn't upload the Laravel project. Upload everything!

### "vendor/autoload.php not found"
→ vendor/ folder missing. Run composer install locally and re-upload.

### "AppServiceProvider not found"
→ app/ folder missing. Upload entire project.

### "Database connection failed"
→ Check .env database credentials match cPanel MySQL database.

---

## 📞 Need Help?

Check these in order:
1. ✅ Entire project uploaded (not just setup.php)
2. ✅ vendor/ folder present (~165 MB)
3. ✅ artisan file present
4. ✅ .env configured correctly
5. ✅ Permissions set to 755
6. ✅ Document root points to public/

If all above are ✅, then run setup.php!
