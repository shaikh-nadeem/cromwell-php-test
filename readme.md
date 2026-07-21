# Cromwell User Management System

[![PHP 8+](https://img.shields.io/badge/PHP-8%2B-blue)](https://www.php.net/)
[![MySQL/MariaDB](https://img.shields.io/badge/MySQL-5.7%2B-orange)](https://www.mysql.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

A modern, full-featured PHP user management system with authentication, registration, profile management, and a RESTful JSON API. Built with Bootstrap 5 for a professional, responsive UI.

## Features

✨ **User Management**
- User registration with comprehensive validation
- Secure login/logout with session management
- User profile editing and updates
- Dashboard with registered users list
- Auto-created database tables on first run

🔐 **Security**
- Password hashing using PHP's `password_hash()` with PASSWORD_DEFAULT
- Input validation and sanitization
- Prepared statements to prevent SQL injection
- Session-based authentication
- Password strength requirements (8+ chars, uppercase, lowercase, number, special char)
- CSRF protection ready

🎨 **Modern UI/UX**
- Bootstrap 5 responsive design
- Gradient backgrounds and smooth animations
- Bootstrap Icons integration
- Real-time password strength validation
- Password visibility toggle on all password fields
- Mobile-friendly interface
- Professional color scheme

📱 **Responsive Design**
- Desktop, tablet, and mobile optimized
- Flexible layout adapts to all screen sizes
- Touch-friendly buttons and forms

🔌 **RESTful API**
- JSON-based API endpoints
- User registration, login, retrieval, and updates
- Comprehensive error handling
- Standardized response format
- Full API documentation included

🗄️ **Database**
- Automatic table creation on first use
- Timestamps for created_at and updated_at
- Prepared statements for security
- MySQL/MariaDB support

---

## Quick Start

### Prerequisites

- **PHP** 8.0 or higher
- **MySQL** 5.7+ or **MariaDB** 10.2+
- **Web Server** (Apache with mod_rewrite, Nginx, etc.)
- **MySQLi Extension** for PHP

### Installation

1. **Clone or download the project**
   ```bash
   git clone https://github.com/yourusername/cromwell.git
   cd cromwell
   ```

2. **Configure environment variables**
   
   Copy `.env.example` to `.env` and update with your database credentials:
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env`:
   ```env
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_NAME=cromwell
   DB_USER=root
   DB_PASSWORD=
   APP_BASE_URL=http://localhost/projects/cromwell
   APP_NAME=Cromwell | PHP Test
   ```

3. **Place project in web root**
   ```bash
   # Example for XAMPP
   cp -r cromwell C:/xampp/htdocs/projects/
   
   # Example for Linux
   sudo cp -r cromwell /var/www/html/projects/
   ```

4. **Access the application**
   - Open `http://localhost/projects/cromwell/` in your browser
   - Database tables will be created automatically on first API request

---

## Usage

### Web Interface

**Registration**
- URL: `http://localhost/projects/cromwell/user/registration`
- Create a new account with email and password
- Real-time password strength validation
- Password must meet security requirements

**Login**
- URL: `http://localhost/projects/cromwell/user/login`
- Sign in with email and password
- Password visibility toggle for convenience

**Dashboard**
- URL: `http://localhost/projects/cromwell/user/home` (after login)
- View all registered users
- Quick access to profile editing and logout

**Profile Editing**
- URL: `http://localhost/projects/cromwell/user/edit` (after login)
- Update personal information
- Change password (optional)
- Real-time validation feedback

### API Endpoints

The application provides a complete RESTful JSON API for programmatic access.

**Base URL:** `http://localhost/projects/cromwell/api`

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/login` | Authenticate user |
| POST | `/user` | Register new user |
| GET | `/user` | Get all users |
| GET | `/user?email=...` | Get specific user by email |
| PUT | `/user` | Update user profile |

**Full API Documentation:** See [API.md](API.md)

**Example API Request:**
```bash
# Register a new user
curl -X POST http://localhost/projects/cromwell/api/user \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Mr",
    "forenames": "John",
    "surname": "Doe",
    "date_of_birth": "1990-01-15",
    "phone_mobile": "+1 (555) 123-4567",
    "email": "john@example.com",
    "password": "SecurePass123!@#",
    "password_confirmation": "SecurePass123!@#"
  }'
```

---

## Project Structure

```
cromwell/
├── api/                          # API endpoints
│   ├── handlers.php             # API validation and database functions
│   ├── login.php                # POST /api/login endpoint
│   └── user.php                 # GET/POST/PUT /api/user endpoints
├── user/                         # Web interface pages
│   ├── login.php                # User login form
│   ├── registration.php         # User registration form
│   ├── home.php                 # Dashboard (authenticated)
│   ├── edit.php                 # Profile editor (authenticated)
│   └── logout.php               # Session cleanup
├── config.php                    # Application configuration
├── env-loader.php               # Environment variables loader
├── index.php                     # Application entry point
├── .env                          # Environment variables (local, not committed)
├── .env.example                 # Environment variables template
├── .htaccess                     # Apache URL rewriting rules
├── API.md                        # Complete API documentation
├── readme.md                     # This file
└── sql/
    └── schema.sql               # Database schema (legacy, auto-created now)
```

---

## Configuration

### Environment Variables

Create a `.env` file in the project root:

```env
# Database Configuration
DB_HOST=127.0.0.1          # MySQL host
DB_PORT=3306               # MySQL port
DB_NAME=cromwell           # Database name
DB_USER=root               # Database user
DB_PASSWORD=               # Database password

# Application Configuration
APP_BASE_URL=http://localhost/projects/cromwell  # Base URL for links
APP_NAME=Cromwell | PHP Test                     # Application name
```

### File Permissions

Ensure the web server can write to the project directory:

```bash
# Linux/Mac
chmod 755 /path/to/cromwell
chmod 755 /path/to/cromwell/api
chmod 755 /path/to/cromwell/user

# Windows: Use file properties or through XAMPP control panel
```

---

## Password Requirements

Passwords must meet these security requirements:

✓ Minimum 8 characters  
✓ At least one uppercase letter (A-Z)  
✓ At least one lowercase letter (a-z)  
✓ At least one number (0-9)  
✓ At least one special character (!@#$%^&...)  

Examples of valid passwords:
- `SecurePass123!@#`
- `MyP@ssw0rd!`
- `Test1234$%`
- `Admin#2024Pass`

---

## Database

### Automatic Initialization

The database table `users` is automatically created on first API request with the following schema:

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(20) NOT NULL,
  forenames VARCHAR(100) NOT NULL,
  surname VARCHAR(100) NOT NULL,
  date_of_birth DATE NOT NULL,
  phone_mobile VARCHAR(20) NOT NULL,
  phone_other VARCHAR(20),
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Manual Schema Setup (Optional)

If you need to manually set up the database, run:

```bash
mysql -u root -p cromwell < sql/schema.sql
```

---

## Security Features

🔒 **Password Security**
- Passwords hashed with PHP's `password_hash()` using PASSWORD_DEFAULT
- Never stored in plain text
- Never returned in API responses

🛡️ **Input Validation**
- All inputs trimmed and validated
- Email format validation
- Phone number format validation
- Date format validation

🔐 **SQL Injection Prevention**
- Prepared statements for all database queries
- Parameter binding prevents SQL injection

🚀 **Session Management**
- Session-based authentication
- Session regeneration on login
- Secure session handling

📧 **Email Validation**
- Valid email format required
- Unique email constraint at database level
- Case-insensitive email handling

---

## API Response Format

All API responses follow a standardized JSON format:

**Success:**
```json
{
  "success": true,
  "message": "Operation successful",
  "user": { /* user data */ }
}
```

**Error:**
```json
{
  "success": false,
  "errors": ["Error message 1", "Error message 2"]
}
```

HTTP status codes indicate the result:
- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## Troubleshooting

### Database Connection Issues

**Error:** `Database connection failed`

**Solutions:**
- Check database credentials in `.env` file
- Ensure MySQL server is running
- Verify database user has correct permissions
- Check database name exists

### File/Permission Issues

**Error:** `Unable to create users table`

**Solutions:**
- Ensure web server process can write to project directory
- Check file permissions (should be 755)
- Run `chmod 755 /path/to/cromwell` on Linux/Mac

### Password Validation Issues

**Error:** `Password does not meet all requirements`

**Solution:**
- Ensure password contains:
  - 8+ characters
  - 1 uppercase letter
  - 1 lowercase letter
  - 1 number
  - 1 special character

### Session Issues

**Error:** `Redirected to login page unexpectedly`

**Solutions:**
- Clear browser cookies and cache
- Check PHP session settings in `php.ini`
- Ensure `session.save_path` is writable
- Check that sessions are enabled in PHP

---

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

---

## Performance

- Page load time: < 500ms (typical)
- API response time: < 100ms (typical)
- Database query optimization with indexed email field
- Prepared statements prevent query parsing delays

---

## Development

### Code Standards

- PSR-12 PHP coding standards
- Strict type declarations (`declare(strict_types=1)`)
- No external PHP dependencies (only built-in extensions)
- Bootstrap 5 for frontend styling

### Testing the API

Use cURL or Postman to test API endpoints:

```bash
# Test user registration
curl -X POST http://localhost/projects/cromwell/api/user \
  -H "Content-Type: application/json" \
  -d '{"title":"Mr","forenames":"Test","surname":"User","date_of_birth":"1990-01-01","phone_mobile":"5551234567","email":"test@example.com","password":"TestPass123!","password_confirmation":"TestPass123!"}'

# Test login
curl -X POST http://localhost/projects/cromwell/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"TestPass123!"}'

# Get all users
curl -X GET http://localhost/projects/cromwell/api/user

# Get specific user
curl -X GET "http://localhost/projects/cromwell/api/user?email=test@example.com"
```

---

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

### How to Contribute

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## Author

Created as a PHP learning project and user management system template.

---

## Support

For issues, questions, or suggestions:
- Open an issue on GitHub
- Check existing documentation in [API.md](API.md)
- Review the code comments for implementation details

---

## Roadmap

- [ ] Email verification on registration
- [ ] Password reset functionality
- [ ] Two-factor authentication
- [ ] User roles and permissions
- [ ] Audit logging
- [ ] Rate limiting
- [ ] API token authentication (JWT)
- [ ] Admin dashboard
- [ ] User search and filtering
- [ ] Batch user import

---

## Changelog

### v1.0.0 (2026-07-21)
- ✨ Initial release
- 🎨 Modern Bootstrap 5 UI with gradients and animations
- 🔐 Comprehensive password security validation
- 👁️ Password visibility toggle on all password fields
- 📱 Fully responsive mobile-friendly design
- 🔌 Complete RESTful JSON API
- 🗄️ Auto-creating database tables
- 📚 Full API documentation
- ✅ Form validation with real-time feedback

---

**Made with ❤️ in PHP**
