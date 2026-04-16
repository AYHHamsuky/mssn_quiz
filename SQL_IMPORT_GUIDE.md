# 📊 SQL Import Guide for cPanel

This guide will help you set up the database using SQL files instead of migrations.

---

## 📦 Files Included

1. **database_schema.sql** - Creates all tables and structure
2. **admin_account.sql** - Creates default admin account

---

## ✅ Step-by-Step Import Process

### Step 1: Access phpMyAdmin

1. **Login to cPanel**: https://mssnkac.com.ng:2083

2. **Find phpMyAdmin** in the Databases section

3. **Click** phpMyAdmin icon

### Step 2: Select Database

1. On the left sidebar, click on your database: **`mssnkacc_quiz_app`**

2. If database doesn't exist:
   - Go back to cPanel → MySQL Databases
   - Create new database: `mssnkacc_quiz_app`
   - Create user: `mssnkacc_quiz_app` with password: `jWN-$Yzt[unJo*gZ`
   - Add user to database with ALL PRIVILEGES

### Step 3: Import Schema

1. Click **Import** tab at the top

2. Click **Choose File** button

3. Select **`database_schema.sql`** from your computer

4. Scroll down and click **Import** button

5. Wait for success message: ✅ "Import has been successfully finished"

### Step 4: Import Admin Account

1. Still in phpMyAdmin, click **Import** tab again

2. Click **Choose File** button

3. Select **`admin_account.sql`**

4. Click **Import** button

5. Wait for success message: ✅ "Import has been successfully finished"

### Step 5: Verify Tables Created

1. In the left sidebar, you should now see all tables:
   ```
   ✓ answers
   ✓ cache
   ✓ cache_locks
   ✓ failed_jobs
   ✓ job_batches
   ✓ jobs
   ✓ migrations
   ✓ password_reset_tokens
   ✓ questions
   ✓ quiz_participations
   ✓ quizzes
   ✓ schools
   ✓ sessions
   ✓ users
   ```

2. Click on **`users`** table

3. Click **Browse** tab

4. You should see 1 row with admin account:
   - **Name:** MSSN Admin
   - **Email:** admin@mssnkac.com.ng
   - **Role:** admin

---

## 🔑 Default Admin Login

After importing, you can login with:

```
URL: https://mssnkac.com.ng/mssn_quiz/login

Email: admin@mssnkac.com.ng
Password: Admin@2025
```

**IMPORTANT:** Change this password immediately after first login!

---

## 🎯 What This Method Does

### ✅ Advantages:
- No need for terminal access
- No need to run migrations
- No need for setup.php
- Works 100% through browser
- Faster setup process
- No PHP execution issues

### ✅ Tables Created:
All 14 tables with proper:
- Primary keys
- Foreign keys
- Indexes
- Constraints
- Default values

### ✅ Admin Account:
Ready-to-use admin account with:
- Secure bcrypt password
- Admin role
- Current timestamp

---

## 📋 Complete Setup Checklist

### Before Import:
```
[ ] Database created: mssnkacc_quiz_app
[ ] Database user created: mssnkacc_quiz_app
[ ] User has ALL PRIVILEGES
[ ] phpMyAdmin accessible
```

### Import Process:
```
[ ] Open phpMyAdmin
[ ] Select mssnkacc_quiz_app database
[ ] Import database_schema.sql
[ ] Wait for success message
[ ] Import admin_account.sql
[ ] Wait for success message
[ ] Verify 14 tables created
[ ] Verify 1 user in users table
```

### After Import:
```
[ ] Upload Laravel application files to cPanel
[ ] Configure .env file
[ ] Set storage/ permissions to 755
[ ] Set bootstrap/cache/ permissions to 755
[ ] Visit https://mssnkac.com.ng/mssn_quiz/login
[ ] Login with admin credentials
[ ] Change admin password
```

---

## 🔍 Verification Steps

### 1. Check Tables Count
```sql
SHOW TABLES;
```
**Expected:** 14 tables

### 2. Check Migrations
```sql
SELECT * FROM migrations;
```
**Expected:** 10 rows

### 3. Check Admin User
```sql
SELECT * FROM users WHERE role = 'admin';
```
**Expected:** 1 row (MSSN Admin)

### 4. Check Table Structure
```sql
DESCRIBE users;
```
**Expected:** Shows all columns (id, name, email, password, role, etc.)

---

## 🆘 Troubleshooting

### Error: "Table already exists"

**Solution:** 
1. Drop all existing tables first
2. Or delete and recreate the database
3. Then import again

### Error: "Cannot add foreign key constraint"

**Solution:**
1. Make sure you're importing `database_schema.sql` first
2. Make sure database engine is InnoDB
3. Make sure charset is utf8mb4

### Error: "Access denied"

**Solution:**
1. Check database user has ALL PRIVILEGES
2. Use correct database credentials
3. Verify user is added to the database

### Import hangs or times out

**Solution:**
1. Files are small, this shouldn't happen
2. Try refreshing phpMyAdmin
3. Try different browser
4. Contact hosting support

---

## ⚡ Quick Import (Summary)

1. **cPanel** → **phpMyAdmin**
2. Select **mssnkacc_quiz_app** database
3. **Import** tab → Choose **database_schema.sql** → Import ✅
4. **Import** tab → Choose **admin_account.sql** → Import ✅
5. Verify 14 tables + 1 admin user
6. Done! 🎉

---

## 🔐 Security Notes

### After Import:

1. **Change admin password immediately**
   - Login to application
   - Go to profile/settings
   - Change password from Admin@2025

2. **Delete SQL files from server**
   - Do NOT leave these on public_html
   - Keep only on your local computer

3. **Backup database regularly**
   - Use phpMyAdmin Export
   - Download .sql backup file

---

## 📊 Expected Result

After successful import:

```
✅ Database: mssnkacc_quiz_app
✅ Tables: 14 (all with proper structure)
✅ Admin Account: 1 (ready to use)
✅ Foreign Keys: All properly set
✅ Indexes: All optimized
✅ Migrations: Recorded in migrations table

Ready to use! 🚀
```

---

## 🎊 Next Steps

After database is ready:

1. ✅ Upload Laravel application files (if not done yet)
2. ✅ Configure .env with database credentials
3. ✅ Set proper permissions
4. ✅ Visit the application URL
5. ✅ Login with admin credentials
6. ✅ Change admin password
7. ✅ Start using the quiz application!

---

## 💡 Pro Tips

### Tip 1: Export After Setup
After everything is configured perfectly:
- phpMyAdmin → Export → Quick export
- Save as backup

### Tip 2: Test Before Production
- Import to test database first
- Verify everything works
- Then import to production

### Tip 3: Document Changes
- If you modify tables later
- Export the new structure
- Keep versioned backups

---

## 🆚 This Method vs setup.php

| Method | SQL Import | setup.php |
|--------|-----------|-----------|
| Terminal needed | ❌ No | ❌ No |
| Composer needed | ❌ No | ✅ Yes |
| PHP execution | ❌ No | ✅ Yes |
| Laravel needed | ❌ No | ✅ Yes |
| Speed | ⚡ Fast | 🐢 Slower |
| Reliability | 🎯 100% | ⚠️ May fail |
| Browser only | ✅ Yes | ✅ Yes |

**Recommendation:** Use SQL Import method - it's simpler and more reliable!

---

## ✅ Success Checklist

```
[ ] database_schema.sql imported successfully
[ ] admin_account.sql imported successfully
[ ] 14 tables visible in phpMyAdmin
[ ] users table has 1 admin account
[ ] Can login at /login
[ ] Dashboard loads correctly
[ ] Can create schools
[ ] Can create quizzes

All checked? Congratulations! 🎉
```
