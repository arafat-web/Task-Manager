<h1 align="center">Task Manager v2.0</h1>

<p align="center">
  <img src="https://img.shields.io/badge/version-2.0-6366f1?style=for-the-badge" alt="Version 2.0">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/github/stars/arafat-web/Task-Manager?style=for-the-badge" alt="Stars">
  <img src="https://img.shields.io/github/issues/arafat-web/Task-Manager?style=for-the-badge" alt="Issues">
  <a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-green?style=for-the-badge" alt="MIT License"></a>
</p>

## Introduction

Task Manager **v2.0** is an open-source Laravel 12 application for managing projects, tasks, notes, reminders, routines, and files — all in one place. Version 2.0 ships with a completely redesigned UI inspired by ClickUp, built from the ground up with a custom design system (`cu-*` component pattern) featuring a consistent indigo/violet gradient palette, two-panel layouts, and smooth interactions across every page.

## What's New in v2.0

- **Complete UI redesign** — consistent design system across all modules: dashboard, projects, tasks, notes, reminders, routines, files, and profile
- **Rich project management** — project cards with progress rings, task breakdowns, team members, budget tracking, and smart status derivation
- **ClickUp-style task board** — Kanban board with drag-and-drop across To Do / In Progress / Completed columns, priority badges, checklist items per task, and estimated hours
- **Enhanced notes** — categories, tag chips, favourites, word count, rich multi-line content, and grid/list toggle
- **Powerful reminders** — four priority levels (low/medium/high/urgent), categories, recurring reminders (daily/weekly/monthly/yearly), snooze, overdue detection, and completion tracking
- **Routines** — daily, weekly, and monthly routine views with day/week/month selectors
- **File manager** — file upload with type detection, preview (image/PDF/generic), and a dedicated file detail page
- **Full profile module** — avatar upload with live preview, bio, phone, location, website, password change with strength meter and live requirement checklist
- **Redesigned login page** — clean card layout with password show/hide toggle, no external CSS framework dependency
- **Rich test data seeder** — 4 projects, 31 tasks, 8 notes, 9 reminders, 4 routines, 6 files, and a fully populated user profile out of the box

## Features

| Module | Capabilities |
|---|---|
| **Dashboard** | Activity stats, productivity chart (last 14 days), upcoming reminders, recent tasks |
| **Projects** | CRUD, slug-based routing, team members, budget, progress tracking, status filter |
| **Tasks** | Kanban board, priority/status, checklist sub-items, estimated hours, due dates |
| **Notes** | Categories, tags, favourites, search, grid/list view |
| **Reminders** | Priority, category, recurrence, snooze, overdue/due-soon detection |
| **Routines** | Daily / weekly / monthly frequency views |
| **Files** | Upload, type badges, image & PDF preview, detail view |
| **Profile** | Avatar, bio, contact info, password with strength meter |

### Prerequisites

- PHP 8.2 or higher
- Composer
- Laravel 12
- MySQL 8+ or any supported database
- Node.js & npm (for Vite asset compilation)

## Setup Instructions

### Step 1: Clone the Repository

```bash
git clone https://github.com/arafat-web/Task-Manager.git
cd Task-Manager
```

### Step 2: Install Dependencies

```bash
composer install
npm install
```

### Step 3: Configure Environment Variables

```bash
cp .env.example .env
```

Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4: Generate Application Key

```bash
php artisan key:generate
```

### Step 5: Run Migrations and Seed Database

```bash
php artisan migrate --seed
```

This will run the `TestDataSeeder` and populate the app with realistic demo data (4 projects, 31 tasks, 8 notes, 9 reminders, 4 routines, and 6 files).

### Step 6: Build Frontend Assets

```bash
npm run build
```

> For local development with hot reload: `npm run dev`

### Step 7: Create Storage Symlink

```bash
php artisan storage:link
```

### Step 8: Serve the Application

```bash
php artisan serve
```

Open `http://localhost:8000` in your browser.

## Demo Login

```
Email:    admin@example.com
Password: secret
```

> Credentials are created by the seeder. Run `php artisan migrate:fresh --seed` to reset.

## How to Use

### Projects
Create and manage multiple projects with budgets, start/end dates, and team members. Each project card shows a progress ring, task breakdown (To Do / In Progress / Done), and live status. Tasks are organized in a ClickUp-style Kanban board within each project.

### Tasks
Add tasks with priority, due date, and estimated hours. Break tasks down further with checklist sub-items. Drag and drop cards between **To Do**, **In Progress**, and **Completed** columns.

### Notes
Create notes with a category, comma-separated tags, and mark any note as a favourite. Switch between grid and list views. Full-text search across title, content, and category.

### Reminders
Set one-off or recurring reminders (daily / weekly / monthly / yearly) with four priority levels and optional location. The dashboard highlights overdue and due-soon reminders automatically.

### Routines
Define habits or recurring work blocks with daily, weekly, or monthly frequencies. View your schedule filtered by day, week, or month.

### Files
Upload any file — images and PDFs get a built-in preview on the detail page. Files are tagged by type and can be downloaded or replaced at any time.

### Profile
Update your name, email, phone, location, website, and bio. Upload a profile avatar with a live preview before saving. Change your password with a real-time strength meter that checks length, uppercase, lowercase, numbers, and special characters.

## Demo
<img width="3024" height="4104" alt="image" src="https://github.com/user-attachments/assets/ec9d3085-810a-4ef1-beaa-67cf7f53e53d" />
<img width="3012" height="1488" alt="image" src="https://github.com/user-attachments/assets/7aa06534-30b9-41ff-a73c-394dc2760776" />
<img width="3010" height="1500" alt="image" src="https://github.com/user-attachments/assets/f01ac561-e3b1-4d20-aaf8-d3edc728cb5e" />
<img width="3020" height="1714" alt="image" src="https://github.com/user-attachments/assets/b1601c35-cb54-4a43-a005-7f02d127f77a" />
<img width="3024" height="1716" alt="image" src="https://github.com/user-attachments/assets/7a4e84b5-0897-4f2e-8363-65b20fa7bffa" />
<img width="3024" height="1718" alt="image" src="https://github.com/user-attachments/assets/ab512203-06b4-4fce-a5db-7f0f4360b21d" />
<img width="3024" height="1712" alt="image" src="https://github.com/user-attachments/assets/c918ac9d-2631-496c-8a90-08ed38f9dc6b" />


## Tech Stack

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Blade templates, custom `cu-*` CSS design system, Bootstrap Icons, Vite
- **Database:** MySQL (compatible with PostgreSQL / SQLite)
- **Storage:** Laravel filesystem (public disk) for avatar and file uploads

## License

This project is open-source and available under the [MIT License](LICENSE).

## Contributing

For any issues or inquiries, please open an issue on the [Issues](https://github.com/arafat-web/Task-Manager/issues) page.<br/>
Contributions are always welcome — please open a [Pull Request](https://github.com/arafat-web/Task-Manager/pulls).<br/>
🎉 **Thanks for reading!** 🌟

## Contact

[![Email](https://img.shields.io/badge/Gmail-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:arafat.122260@gmail.com)
[![Facebook](https://img.shields.io/badge/Facebook-1877F2?style=for-the-badge&logo=facebook&logoColor=white)](https://www.facebook.com/arafathossain000)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/arafat-hossain-ar-a174b51a6/)
[![Sololearn](https://img.shields.io/badge/-Sololearn-3a464b?style=for-the-badge&logo=Sololearn&logoColor=white)](https://www.sololearn.com/profile/4703319)
[![Website](https://img.shields.io/badge/website-000000?style=for-the-badge&logo=About.me&logoColor=white)](https://arafatdev.com)
