# Cromwell API Documentation

## Overview

Cromwell provides a RESTful JSON API for user management, authentication, and profile operations. All API endpoints return JSON responses with standardized success/error formats.

## Base URL

```
http://localhost/projects/cromwell/api
```

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "user": { /* user object */ },
  "users": [ /* array of user objects */ ]
}
```

### Error Response
```json
{
  "success": false,
  "errors": [
    "Error message 1",
    "Error message 2"
  ]
}
```

## HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Request successful |
| 201 | Created - User created successfully |
| 400 | Bad Request - Invalid JSON or format |
| 401 | Unauthorized - Invalid credentials |
| 404 | Not Found - User not found |
| 405 | Method Not Allowed - Incorrect HTTP method |
| 422 | Unprocessable Entity - Validation error |
| 500 | Internal Server Error - Database error |

---

## Endpoints

### 1. User Registration

**Endpoint:** `POST /api/user`

**Description:** Create a new user account

**Content-Type:** `application/json`

**Request Body:**
```json
{
  "title": "Mr",
  "forenames": "John",
  "surname": "Doe",
  "date_of_birth": "1990-01-15",
  "phone_mobile": "+1 (555) 123-4567",
  "phone_other": "+1 (555) 987-6543",
  "email": "john.doe@example.com",
  "password": "SecurePass123!@#",
  "password_confirmation": "SecurePass123!@#"
}
```

**Required Fields:**
- `title` (string) - Mr, Mrs, Miss, Ms, Dr
- `forenames` (string) - First name(s)
- `surname` (string) - Last name
- `date_of_birth` (string, YYYY-MM-DD) - Date of birth
- `phone_mobile` (string) - Mobile phone number
- `email` (string) - Valid email address
- `password` (string) - Minimum 8 characters
- `password_confirmation` (string) - Must match password

**Optional Fields:**
- `phone_other` (string) - Alternative phone number

**Password Requirements:**
- Minimum 8 characters
- At least one uppercase letter (A-Z)
- At least one lowercase letter (a-z)
- At least one number (0-9)
- At least one special character (!@#$%^&...)

**Example Request:**
```bash
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

**Success Response (201):**
```json
{
  "success": true,
  "message": "User created successfully.",
  "user": {
    "id": 1,
    "title": "Mr",
    "forenames": "John",
    "surname": "Doe",
    "date_of_birth": "1990-01-15",
    "phone_mobile": "+1 (555) 123-4567",
    "phone_other": "",
    "email": "john@example.com"
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "errors": [
    "Email already exists.",
    "Password must contain uppercase, lowercase, number and special character."
  ]
}
```

---

### 2. User Login

**Endpoint:** `POST /api/login`

**Description:** Authenticate a user with email and password

**Content-Type:** `application/json`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "SecurePass123!@#"
}
```

**Required Fields:**
- `email` (string) - Valid email address
- `password` (string) - User password

**Example Request:**
```bash
curl -X POST http://localhost/projects/cromwell/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "SecurePass123!@#"
  }'
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Login successful.",
  "user": {
    "id": 1,
    "title": "Mr",
    "forenames": "John",
    "surname": "Doe",
    "date_of_birth": "1990-01-15",
    "phone_mobile": "+1 (555) 123-4567",
    "phone_other": "",
    "email": "john@example.com",
    "created_at": "2026-07-21 10:30:00",
    "updated_at": "2026-07-21 10:30:00"
  }
}
```

**Error Response (401):**
```json
{
  "success": false,
  "errors": [
    "Invalid email or password."
  ]
}
```

---

### 3. Get All Users

**Endpoint:** `GET /api/user`

**Description:** Retrieve all registered users

**Example Request:**
```bash
curl -X GET http://localhost/projects/cromwell/api/user
```

**Success Response (200):**
```json
{
  "success": true,
  "users": [
    {
      "id": 1,
      "title": "Mr",
      "forenames": "John",
      "surname": "Doe",
      "date_of_birth": "1990-01-15",
      "phone_mobile": "+1 (555) 123-4567",
      "phone_other": "",
      "email": "john@example.com",
      "created_at": "2026-07-21 10:30:00",
      "updated_at": "2026-07-21 10:30:00"
    },
    {
      "id": 2,
      "title": "Ms",
      "forenames": "Jane",
      "surname": "Smith",
      "date_of_birth": "1992-05-20",
      "phone_mobile": "+1 (555) 987-6543",
      "phone_other": null,
      "email": "jane@example.com",
      "created_at": "2026-07-21 11:00:00",
      "updated_at": "2026-07-21 11:00:00"
    }
  ]
}
```

---

### 4. Get User by Email

**Endpoint:** `GET /api/user?email={email}`

**Description:** Retrieve a specific user by email address

**Query Parameters:**
- `email` (string, required) - User's email address

**Example Request:**
```bash
curl -X GET "http://localhost/projects/cromwell/api/user?email=john@example.com"
```

**Success Response (200):**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "title": "Mr",
    "forenames": "John",
    "surname": "Doe",
    "date_of_birth": "1990-01-15",
    "phone_mobile": "+1 (555) 123-4567",
    "phone_other": "",
    "email": "john@example.com",
    "created_at": "2026-07-21 10:30:00",
    "updated_at": "2026-07-21 10:30:00"
  }
}
```

**Error Response (404):**
```json
{
  "success": false,
  "errors": [
    "User not found."
  ]
}
```

---

### 5. Update User Profile

**Endpoint:** `PUT /api/user`

**Description:** Update an existing user's profile information

**Content-Type:** `application/json`

**Request Body:**
```json
{
  "email": "john@example.com",
  "title": "Dr",
  "forenames": "John",
  "surname": "Doe",
  "date_of_birth": "1990-01-15",
  "phone_mobile": "+1 (555) 123-4567",
  "phone_other": "+1 (555) 111-2222",
  "password": "NewSecurePass456!@#"
}
```

**Required Fields:**
- `email` (string) - User email (used to identify which user to update)

**Optional Fields:**
- `title` (string) - Mr, Mrs, Miss, Ms, Dr
- `forenames` (string) - First name(s)
- `surname` (string) - Last name
- `date_of_birth` (string, YYYY-MM-DD) - Date of birth
- `phone_mobile` (string) - Mobile phone number
- `phone_other` (string) - Alternative phone number
- `password` (string) - New password (only updated if provided and not empty)

**Notes:**
- Email field is required but won't be changed
- If password field is empty or not provided, current password is unchanged
- New password must meet the same requirements as registration password
- Only provided fields will be updated

**Example Request:**
```bash
curl -X PUT http://localhost/projects/cromwell/api/user \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "phone_mobile": "+1 (555) 555-5555",
    "password": "NewSecurePass456!@#"
  }'
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "User updated successfully.",
  "user": {
    "id": 1,
    "title": "Mr",
    "forenames": "John",
    "surname": "Doe",
    "date_of_birth": "1990-01-15",
    "phone_mobile": "+1 (555) 555-5555",
    "phone_other": "",
    "email": "john@example.com",
    "created_at": "2026-07-21 10:30:00",
    "updated_at": "2026-07-21 12:45:00"
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "errors": [
    "Phone mobile number must contain only digits, spaces, parentheses, hyphens or a leading +."
  ]
}
```

---

## Validation Rules

### Email
- Must be a valid email format
- Must be unique (no two users can have the same email)

### Phone Numbers
- Contain 7-20 characters
- Can include digits, spaces, parentheses, hyphens, or leading `+`
- Examples: `+1 (555) 123-4567`, `555-123-4567`, `5551234567`

### Date of Birth
- Format: `YYYY-MM-DD`
- Must be a valid date
- Example: `1990-01-15`

### Password
- Minimum 8 characters
- Must contain at least one uppercase letter (A-Z)
- Must contain at least one lowercase letter (a-z)
- Must contain at least one number (0-9)
- Must contain at least one special character: `!@#$%^&*()_+-=[]{}';:",./<>?\|` `
- Example: `SecurePass123!@#`

### Title
- Valid values: `Mr`, `Mrs`, `Miss`, `Ms`, `Dr`

---

## Error Handling

All errors are returned with appropriate HTTP status codes and error messages in the `errors` array.

**Common Error Messages:**

| Error | Cause | Solution |
|-------|-------|----------|
| Email is required | Email field missing in request | Include `email` field |
| Email address must be valid | Invalid email format | Use valid email format (name@domain.com) |
| A user with that email already exists | Email already in use | Use different email address |
| Password must be at least 8 characters long | Password too short | Use password with 8+ characters |
| Passwords do not match | password_confirmation doesn't match password | Ensure passwords match |
| Invalid email or password | Wrong login credentials | Verify email and password |
| User not found | User doesn't exist | Check email address |
| Only POST requests are supported | Wrong HTTP method for login | Use POST request |
| Database error | Server-side database issue | Contact administrator |

---

## Environment Variables

Configure the API using the `.env` file:

```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=cromwell
DB_USER=root
DB_PASSWORD=
APP_BASE_URL=http://localhost/projects/cromwell
APP_NAME=Cromwell | PHP Test
```

---

## Database Schema

### Users Table

| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| title | VARCHAR(20) | NOT NULL |
| forenames | VARCHAR(100) | NOT NULL |
| surname | VARCHAR(100) | NOT NULL |
| date_of_birth | DATE | NOT NULL |
| phone_mobile | VARCHAR(20) | NOT NULL |
| phone_other | VARCHAR(20) | NULL |
| email | VARCHAR(255) | NOT NULL, UNIQUE |
| password_hash | VARCHAR(255) | NOT NULL |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |

---

## Security Considerations

- Passwords are hashed using PHP's `password_hash()` with `PASSWORD_DEFAULT` algorithm
- Password hashes are never returned in API responses
- Email addresses are stored in lowercase for case-insensitive lookups
- All input is trimmed and validated before database operations
- Prepared statements prevent SQL injection
- Content-Type validation ensures JSON requests
- CORS headers can be configured as needed
