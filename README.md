# Employee Management Portal

## PHP System Test
This project was developed and submitted as part of a PHP/Laravel technical system test. It demonstrates proficiency in building modern web applications, handling file uploads, implementing data validation, and integrating third-party packages for specialized tasks.

## Project Overview
The Employee Management Portal is a comprehensive Laravel-based web application designed for HR administrators to manage employee records. The system provides a clean, user-friendly interface to perform full CRUD (Create, Read, Update, Delete) operations on employee data, manage document attachments (photos and resumes), and bulk import/export data using Excel files.

## Technology Stack
The application is built using the following technologies:
- **Framework:** Laravel 11.x
- **Language:** PHP 8.3
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, Bootstrap 5
- **Excel Processing:** Maatwebsite/Laravel-Excel

## Completed Requirements

| Requirement | Status |
|-------------|--------|
| Employee List | ✅ Completed |
| Add Employee | ✅ Completed |
| Edit Employee | ✅ Completed |
| View Employee | ✅ Completed |
| Delete Employee | ✅ Completed |
| Employee ID | ✅ Completed |
| Employee personal details | ✅ Completed |
| Photo upload | ✅ Completed |
| Resume upload | ✅ Completed |
| Search | ✅ Completed |
| Filtering | ✅ Completed |
| Sorting | ✅ Completed |
| Pagination | ✅ Completed |
| Excel Export | ✅ Completed |
| Excel Import | ✅ Completed |
| Clean UI | ✅ Completed |

## Employee Fields
The application captures and manages the following data fields for each employee:
- `employee_id` (Unique identifier)
- `firstname`
- `lastname`
- `date_of_birth`
- `education_qualification`
- `address`
- `email` (Unique)
- `phone`
- `photo` (Image file)
- `resume` (PDF/Word document)

## Features
- **CRUD Operations:** Administrators can seamlessly create new employees, view detailed profiles, update information, and delete records.
- **Search:** A dynamic search bar allows searching for employees by ID, name, email, or phone number.
- **Filter:** Users can filter the employee roster based on their Education Qualification.
- **Sort:** The employee table can be sorted dynamically by Employee ID, Name, Email, or Date Added in Ascending (ASC) or Descending (DESC) order.
- **Pagination:** The employee list is paginated (10 records per page) to ensure fast load times and clean UI navigation.
- **Photo/Resume Upload:** Supports uploading profile photos and document resumes, including the ability to selectively remove existing files during the editing process.
- **Excel Export:** Generates and downloads a complete `.xlsx` roster of all employees currently in the system.
- **Excel Import:** Allows bulk creation and updating of employees via Excel upload. It includes robust handling using `updateOrCreate` to prevent duplicate `employee_id` entries.

## Database
The application relies on a **MySQL** database. The employee data structure is defined by Laravel migrations, ensuring strict schema enforcement. 

The `employees` table includes standard data columns alongside constraints to maintain data integrity (e.g., unique constraints on `employee_id` and `email`).

## Installation
To run this project locally, follow these steps:

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd employee-management-portal
   ```

2. **Install Composer dependencies:**
   ```bash
   composer install
   ```

3. **Configure Environment:**
   ```bash
   cp .env.example .env
   ```
   *Open `.env` and configure your `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` for MySQL.*

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

6. **Create Storage Link (Required for file uploads):**
   ```bash
   php artisan storage:link
   ```

7. **Start the Development Server:**
   ```bash
   php artisan serve
   ```

## Excel Import / Export
Excel functionality is powered by the `maatwebsite/excel` package. 
- **Exporting:** Uses the `FromCollection`, `WithHeadings`, and `WithMapping` concerns to generate a cleanly formatted spreadsheet of all employees.
- **Importing:** Uses the `ToModel` and `WithHeadingRow` concerns. The importer automatically detects column headers, formats Excel-specific date formats into standard database dates, and gracefully handles duplicate entries or empty rows.

## File Uploads
Files are handled using Laravel's local `public` storage disk. 
- **Photos:** Validated to accept only standard image formats (`jpeg`, `png`, `jpg`) up to 2MB. They are stored in `storage/app/public/employees/photos`.
- **Resumes:** Validated to accept document formats (`pdf`, `doc`, `docx`) up to 2MB. They are stored in `storage/app/public/employees/resumes`.
- When an employee is updated or deleted, the system automatically checks for existing files and deletes them from the server to prevent orphaned files and save disk space.

## Deployment
This application has been containerized using Docker and is actively deployed on **Railway**.

**Live URL:** https://employee-management-portal-production.up.railway.app

## Test Submission Details
- **Output URL:** https://employee-management-portal-production.up.railway.app
- **Username:** Not applicable / Not provided
- **Password:** Not applicable / Not provided
- **Framework:** Laravel 11
- **Database:** MySQL
- **Source Code:** GitHub repository

## Task Duration
As per the technical test requirement.

## Notes
This README explicitly documents the implementation submitted for the PHP System Test. No boilerplate documentation is included.
