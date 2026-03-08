# Jelajah Sejarah - Project Documentation

This document serves as the master guide and context blueprint for any AI assistant (like Gemini) working on the "Jelajah Sejarah" project in the future. It contains comprehensive details about the architecture, tech stack, database schema, API endpoints, and frontend implementation.

## 1. Project Overview
"Jelajah Sejarah" is an interactive, educational web application dedicated to Indonesian history. It features a rich, animated frontend UI and a robust backend API.

- **Root Directory:** `D:\xampp\htdocs\jelajah-sejarah-api`
- **Backend Environment:** Laravel 11.x (PHP 8.2+)
- **Frontend Environment:** React 18, TypeScript, Vite (located in `jelajah-sejarah/` subdirectory)
- **Database:** MySQL (Configured for XAMPP `127.0.0.1:3306`, DB name: `sejarah-kita`)

---

## 2. Backend Architecture (Laravel)
The backend follows a standard MVC architecture with an added **Service Layer** to keep controllers thin.

### Directory Structure:
- `app/Http/Controllers/`: Contains all controllers, divided into `Admin`, `Auth`, `PublicApi`, and `User` namespaces.
- `app/Models/`: Eloquent models with UUID primary keys.
- `app/Services/`: Business logic layer (e.g., `ArticleService.php`, `QuizService.php`).
- `database/migrations/`: Migration files.
- `database/seeders/DatabaseSeeder.php`: Master seeder containing comprehensive historical data.
- `routes/api.php`: All API routes are defined here.

### Database Schema (MySQL):
1. **users:** Standard Laravel users table + `role` ('admin', 'user').
2. **eras:** Represents historical periods (e.g., 'hindu-buddha', 'kesultanan', 'kolonial', 'pergerakan', 'kemerdekaan'). Fields: `name`, `slug`, `color_hue`, `badge_class`, `sort_order`.
3. **articles:** Main content. Fields: `era_id`, `title`, `slug`, `year`, `summary`, `hero_image`, `is_published`, `view_count`.
4. **article_sections:** Paragraphs within an article. Fields: `article_id`, `heading`, `paragraphs` (JSON array), `sort_order`.
5. **article_videos:** YouTube videos for articles. Fields: `article_id`, `youtube_id`, `title`, `channel`.
6. **quizzes & quiz_questions:** Interactive quizzes per article. `quiz_questions` fields: `question`, `options` (JSON), `correct_index` (integer), `explanation`.
7. **timeline_events:** Data for the Interactive Timeline. Fields: `era_id`, `year`, `title`, `description`, `significance` (JSON), `figures` (JSON), `article_slug`, `image`.
8. **map_locations:** Data for the Interactive Map. Fields: `era_id`, `article_id`, `name`, `latitude`, `longitude`, `year`, `description`, `article_slug`.
9. **topics:** Categories/Topics shown on the homepage. Fields: `era_id`, `title`, `description`, `icon_name` (must match `iconMap` in frontend).
10. **fun_facts:** Random historical facts shown in toasts.
11. **bookmarks:** User-bookmarked articles (Many-to-Many `users` and `articles`).
12. **quiz_attempts:** User quiz history. Fields: `user_id`, `article_id`, `score`, `total`, `time_seconds`.
13. **reading_progress:** Tracking how far a user read an article.
14. **discussions:** Nested comments on articles.

### API Routes Overview (`routes/api.php`):
- **Auth:** `POST /api/auth/register`, `POST /api/auth/login`, `POST /api/auth/logout`, `GET /api/auth/me`.
- **Public:**
  - `GET /api/eras`
  - `GET /api/articles` (Supports `?era={slug}` & `?search={query}`)
  - `GET /api/articles/{slug}`
  - `GET /api/articles/{slug}/quiz`
  - `GET /api/timeline`
  - `GET /api/map-locations`
  - `GET /api/topics`
  - `GET /api/stats` (Returns total articles, eras, etc. for homepage)
  - `GET /api/fun-facts/random`
- **User (Requires Auth):**
  - `GET /api/bookmarks`, `POST /api/bookmarks/{articleId}`
  - `POST /api/articles/{slug}/quiz-attempt`, `GET /api/quiz-attempts`
  - `GET /api/progress/stats`
- **Admin (Requires Auth & 'admin' role):**
  - `GET /api/admin/stats` (Dashboard stats)
  - `apiResource('articles', AdminArticleController)`
  - `apiResource('quizzes', AdminQuizController)`

---

## 3. Frontend Architecture (React + Vite)
Located in `D:\xampp\htdocs\jelajah-sejarah-api\jelajah-sejarah`.

### Tech Stack:
- **Framework:** React 18 with TypeScript.
- **Build Tool:** Vite.
- **Routing:** `react-router-dom`.
- **State Management & Data Fetching:** `@tanstack/react-query` & Axios.
- **Styling:** Tailwind CSS + `shadcn/ui` components.
- **Animations:** `framer-motion` (Heavily used for scroll reveals, parallax, and UI transitions).
- **Icons:** `lucide-react`.
- **Charts & Maps:** `recharts` (Admin dashboard), `leaflet` & `react-leaflet` (MapSection).

### Folder Structure:
- `src/components/`: Reusable UI components.
  - `Navbar.tsx`: Main navigation with auth logic.
  - `HeroSection.tsx`: Homepage hero with real-time stats.
  - `TopicsSection.tsx`: Displays topics dynamically mapping DB `icon_name` to `lucide-react` icons.
  - `TimelineSection.tsx`: Interactive vertical timeline.
  - `MapSection.tsx`: Leaflet map displaying historical locations.
  - `ArticleReader.tsx`: Text-to-speech functionality.
  - `ArticleQuiz.tsx`: Interactive quiz UI per article.
- `src/pages/`: Page-level components.
  - `Index.tsx`: Homepage.
  - `AboutPage.tsx`: Complex page featuring custom SVG Wayang (shadow puppet) animations and Framer Motion parallax effects. **(DO NOT OVERWRITE OR REMOVE THE SVG PATHS WITHOUT PERMISSION)**.
  - `ArticleList.tsx`: Grid of all articles with search and era filtering.
  - `ArticleDetail.tsx`: Deep dive into an article, renders sections, YouTube embeds, and quizzes.
  - `admin/`: Admin dashboard pages (`AdminDashboard.tsx`, `AdminQuizzes.tsx`, etc.).
- `src/hooks/`: React Query hooks for API communication.
  - `use-home.ts`: `useTopics`, `useTimeline`, `useMapLocations`, `usePublicStats`.
  - `use-articles.ts`: `useArticles`, `useArticleDetail`, `useEras`.
  - `use-auth.ts`: Login, register, logout logic.
  - `use-admin.ts`: `useAdminStats`, `useAdminQuizzes`.
- `src/lib/`: Utilities (`api-client.ts` sets up Axios instance).

### Key Design Patterns & Guidelines for Future Edits:
1. **Framer Motion is Everywhere:** Almost every page uses `initial`, `animate`, `whileInView`, or `useScroll`. When adding new components, maintain this animation standard.
2. **Wayang SVG & Parallax:** The `AboutPage.tsx` contains hardcoded SVG paths for Javanese shadow puppets (`Gunungan`, `WayangFigure`). Treat this file with extreme care. If updating, only touch the text or data, do not touch the SVG `path` data unless specifically asked.
3. **API Integration:** The frontend is fully connected to the backend. Do not revert to dummy data (`src/data/*.ts`) unless specifically instructed. Always use the hooks in `src/hooks/`.
4. **Icon Mapping:** In `TopicsSection.tsx`, the DB returns `icon_name` (e.g., `temple`, `sword`). This is mapped to `lucide-react` components via an `iconMap`. If you add new topics to the seeder, ensure the `icon_name` exists in the frontend `iconMap`.
5. **CamelCase vs Snake_case:** The Laravel backend returns `snake_case` (e.g., `correct_index`, `hero_image`). Be careful in the frontend to map these correctly. E.g., `quiz.correct_index` instead of `quiz.correctIndex`.

---

## 4. How to Run & Reset the Project

### Start Backend:
```bash
cd D:\xampp\htdocs\jelajah-sejarah-api
php artisan serve
```

### Start Frontend:
```bash
cd D:\xampp\htdocs\jelajah-sejarah-api\jelajah-sejarah
npm run dev
```

### Reset Database & Seed Data:
The project uses MySQL via XAMPP. To reset everything and repopulate with the rich historical data (Kutai, Sriwijaya, Majapahit, etc.):
```bash
cd D:\xampp\htdocs\jelajah-sejarah-api
php artisan migrate:fresh --seed
```

### Default Accounts:
- **Admin:** `admin@sejarah.id` / `password123`
- **User:** `budi@gmail.com` / `password123`

---

## 6. Separating Frontend & Backend (Moving Directories)
Currently, the frontend is inside the backend directory (`jelajah-sejarah/`). To move it to a sibling position (e.g., both folders inside `htdocs/`), follow these steps:

1.  **Move the Folder:** Simply cut the `jelajah-sejarah` folder and paste it into `D:\xampp\htdocs\`.
2.  **CORS Configuration (Laravel):**
    If the frontend runs on a different port or domain, ensure the backend allows it. In Laravel 11, check `config/cors.php` (if exists) or `bootstrap/app.php`. 
    Ensure `allowed_origins` includes your frontend URL (e.g., `http://localhost:5173`).
3.  **Environment Variables (Vite):**
    Open `jelajah-sejarah/.env` and ensure `VITE_API_URL` points to the absolute path of your Laravel API:
    ```env
    VITE_API_URL=http://localhost:8000/api
    ```
4.  **Terminal Paths:**
    You will need two separate terminal windows:
    - Terminal 1 (Backend): `cd D:\xampp\htdocs\jelajah-sejarah-api && php artisan serve`
    - Terminal 2 (Frontend): `cd D:\xampp\htdocs\jelajah-sejarah && npm run dev`

---

## 7. Note for AI Assistants
When asked to "fix the UI" or "add a feature":
1. Check this `GEMINI.md` file first to understand the context.
2. **DO NOT** rewrite large components from scratch unless asked. Use `replace` to target specific lines.
3. Respect the existing Tailwind color variables (`bg-primary`, `bg-card`, `text-muted-foreground`, etc.).
4. Ensure any new backend data is exposed via a dedicated Controller, managed in a Service, and consumed via a React Query hook in the frontend.
5. Prioritize maintaining the aesthetic integrity of the application, especially the complex Framer Motion transitions.