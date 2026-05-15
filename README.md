# POS System Project Instructions

This project is a Point of Sale (POS) system for a chicken rice shop ("ข้าวมันไก่น้องนัน"), built with PHP and MySQL.

## 🛠 Technology Stack

- **Backend:** PHP 8.2 (Apache)
- **Database:** MySQL 8.0
- **Frontend:** Bootstrap 4, AdminLTE 3, jQuery
- **Libraries:**
  - SweetAlert2 (for notifications)
  - FPDF (for PDF generation)
  - FontAwesome 5 (for icons)
- **Containerization:** Docker & Docker Compose

## 📁 Directory Structure

- `backend/`: Core logic and database operations.
  - `auth/`: Authentication logic (login, logout, register).
  - `config/`: Configuration files (database connection, global helpers).
- `frontend/`: User interface components.
  - `admin/`: Admin dashboard and management pages.
  - `user/`: Regular user/customer pages (cart, order history).
  - `layout/`: Shared header, footer, and configuration scripts.
- `plugins/`: Third-party CSS and JS libraries.
- `uploads/`: Directory for uploaded product images.
- `fpdf/`: Library for generating PDF reports.

## 🚀 Development Workflow

### Docker Environment
The project is containerized for easy setup.
- **App Service:** Runs on PHP 8.2 Apache, mapped to port `8080`.
- **Database Service:** Runs MySQL 8.0 on port `3306`.
- **Auto-Initialization:** The database is initialized using `./pos_system_new.sql`.

To start the project:
```bash
docker-compose up -d
```

### Database Connection
Configuration is managed via environment variables in `docker-compose.yml` and loaded in `backend/config/condb.php`.
- **Host:** `db` (inside Docker) or `localhost`
- **User:** `root`
- **Password:** `rootpassword`
- **Database:** `pos_system`

## ⚖️ Coding Conventions

### PHP Standards
- **Global Helpers:** Use the helper functions defined in `backend/config/condb.php`:
    - `e($string)`: For HTML escaping (XSS prevention).
    - `csrf_field()`: To include CSRF tokens in forms.
    - `csrf_verify()`: To verify POST requests.
    - `redirect_with_swal($icon, $title, $text, $url)`: For redirects with SweetAlert2 notifications.
- **Database Access:** Use `mysqli` extension as per existing patterns.
- **Session Management:** Sessions are started automatically in `condb.php`.

### Frontend
- **UI Framework:** AdminLTE 3 / Bootstrap 4.
- **JavaScript:** Use jQuery for DOM manipulation and AJAX if needed.
- **Notifications:** Prefer SweetAlert2 over standard browser alerts.

## 📋 Architecture & Data Flow

1.  **Request Handling:** Most UI pages are in `frontend/` and include `backend/config/condb.php`.
2.  **Form Submissions:** Forms typically POST to scripts in `backend/` which perform DB operations and redirect back with a notification.
3.  **PDF Generation:** Handled by `backend/generate_pdf.php` using the FPDF library.
4.  **Image Uploads:** Product images are stored in the `uploads/` directory with unique filenames.
