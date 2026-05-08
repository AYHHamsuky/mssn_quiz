<<<<<<< HEAD
# MSSN Quiz Application

A comprehensive quiz application built with Laravel 12 and Tailwind CSS for schools to participate in various types of quiz competitions.

## Features

### For Schools
- **Registration**: Schools can register with their details and receive a unique registration code
- **Quiz Participation**: 
  - View all available and live quizzes
  - Register for upcoming quizzes
  - Participate in live quizzes with real-time timer
  - Multiple quiz types supported:
    - General Subject (with multiple choice)
    - Debate
    - Impromptu Speech
    - Essay
    - Arabic Passage Reading
    - English Passage Reading
- **Performance Tracking**:
  - View quiz results
  - See leaderboard rankings
  - Track scores across all participations

### For Administrators
- **Quiz Management**:
  - Create and edit quizzes
  - Add questions with options, audio files, and passages
  - Set timer per question (30 seconds default)
  - Configure points per question (2 marks default)
  - Schedule quiz start and end times
- **Live Quiz Control**:
  - Start/stop quizzes live
  - Monitor real-time participation
- **Analytics**:
  - View overall leaderboard
  - Track school performance

## Installation & Setup

### 🏠 Local Development

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Build Assets**
   ```bash
   npm run build
   ```

3. **Start Development Server**
   ```bash
   php artisan serve
   npm run dev
   ```

4. **Access the Application**
   - URL: http://localhost:8000
   - Admin Email: admin@quiz.com
   - Admin Password: password

---

### 🌐 cPanel Production Deployment

**✅ RECOMMENDED METHOD: SQL Import (Easiest)**

1. **Upload Application Files**
   - Run `prepare_for_upload.bat`
   - Upload entire project to `public_html/quiz/`
   - Configure `.env` file

2. **Import Database**
   - Open cPanel → phpMyAdmin
   - Select your database
   - Import `database_schema.sql`
   - Import `admin_account.sql`
   - ✅ Done!

3. **Login**
   - URL: https://mssnkac.com.ng/mssn_quiz/login
   - Email: admin@mssnkac.com.ng
   - Password: Admin@2025

📖 **See `SQL_IMPORT_GUIDE.md` for detailed instructions**

---

**Alternative Methods:**
- `setup.php` - Browser-based setup script (requires full Laravel upload)
- `verify.php` - Check if files uploaded correctly
- See `CPANEL_UPLOAD_CHECKLIST.md` for complete guide

## Default Credentials

**Admin Account:**
- Email: admin@quiz.com
- Password: password

**Schools:** Register through the registration page

## Technical Stack

- Laravel 12
- Tailwind CSS 4
- SQLite Database
- Vite for assets

