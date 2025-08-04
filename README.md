
## 🚀 Installation Guide

Follow the steps below to set up the project on your local development environment.

### ⚙️ Prerequisites

Ensure the following are installed on your machine:

- PHP >= 8.1
- Composer
- Node.js (v16+)
- NPM
- MySQL or PostgreSQL
- Git (for cloning)

---

### 🛠 Step-by-Step Setup

1. **Clone the repository**

   ```bash
   git clone https://github.com/arvinolivas13/ota-coding-challenge.git
   cd ota-coding-challenge
   ```

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Install Node.js dependencies**

   ```bash
   npm install
   ```

4. **Build frontend assets**

   ```bash
   npm run build
   ```

5. **Copy and configure `.env` file**

   ```bash
   cp .env.example .env
   ```

   Open `.env` in a text editor and set your:

   - Database name
   - DB username and password
   - App name and URL

6. **Generate application key**

   ```bash
   php artisan key:generate
   ```

7. **Run database migrations**

   ```bash
   php artisan migrate
   ```

8. **Seed initial data (optional)**

   ```bash
   php artisan db:seed --class=DatabaseSeeder
   ```

9. **Start the development server**

   ```bash
   php artisan serve
   ```

   Visit: [http://localhost:8000](http://localhost:8000)

---

## 🧪 Development Tips

- Run asset compiler in watch mode:

  ```bash
  npm run dev
  ```

- Clear Laravel cache if you encounter issues:

  ```bash
  php artisan optimize:clear
  ```

---

## 🧭 Project Structure

```
├── app/             # Laravel core logic (Models, Controllers, etc.)
├── bootstrap/       # App bootstrapping
├── config/          # Configuration files
├── database/
│   ├── migrations/  # DB structure
│   └── seeders/     # Initial data
├── public/          # Web root (entry point)
├── resources/
│   ├── views/       # Blade templates
│   └── js/ & css/   # Frontend assets
├── routes/          # Web and API route files
├── storage/         # Logs, compiled views, uploads
├── tests/           # Unit and Feature tests
├── .env             # Environment-specific config
└── vite.config.js   # Vite config for frontend
```

---
