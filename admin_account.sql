-- ============================================
-- MSSN Quiz - Create Admin Account
-- ============================================
-- Import this AFTER importing database_schema.sql
-- ============================================

-- ============================================
-- Default Admin Account
-- ============================================
-- Email: admin@mssnkac.com.ng
-- Password: Admin@2025
-- ============================================

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `school_id`, `created_at`, `updated_at`) VALUES
(1, 'MSSN Admin', 'admin@mssnkac.com.ng', NULL, '$2y$12$LQv3c1yduTi6xUrsDk.1OOYzHxNxLhCvC9gP8VOhhLTYqPqVm3FsW', NULL, 'admin', NULL, NOW(), NOW());

-- ============================================
-- Password: Admin@2025
-- Hashed using bcrypt
-- ============================================

-- You can login with:
-- Email: admin@mssnkac.com.ng
-- Password: Admin@2025
