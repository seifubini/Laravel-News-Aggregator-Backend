# 📰 Laravel News Aggregator Backend

A clean Laravel 12 backend that aggregates and exposes news articles from multiple public APIs.  
It fetches and stores articles from **NewsAPI.org**, **The Guardian**, and **The New York Times**, then provides a simple REST API with filtering, pagination, and detailed single-article views.

---

## 🚀 Features

✅ Fetches articles from **3 news sources**  
✅ Stores normalized articles locally  
✅ Filter and paginate articles via API  
✅ Retrieve single articles by `id` or `url`  
✅ Hourly automatic synchronization via Laravel Scheduler  
✅ Unit & Feature tests included  
✅ Clean, SOLID architecture

---

## 🧩 Technologies

- Laravel 12  
- PHP 8.2+  
- MySQL / PostgreSQL  
- Laravel HTTP Client (Guzzle)  
- PHPUnit for testing  

---

## ⚙️ Installation & Setup

### 1️⃣ Clone and Install
```bash
git clone https://github.com/seifubini/Laravel-News-Aggregator-Backend.git
cd news-aggregator
composer install
cp .env.example .env
php artisan key:generate
```

### 2️⃣ Configure Environment

Open `.env` and add your API keys:
```
NEWSAPI_KEY=your_newsapi_key_here
GUARDIAN_KEY=your_guardian_api_key_here
NYT_KEY=your_nyt_api_key_here
```

> 🔑 You can get free keys from  
> [https://newsapi.org](https://newsapi.org),  
> [https://open-platform.theguardian.com](https://open-platform.theguardian.com),  
> [https://developer.nytimes.com](https://developer.nytimes.com)

Also make sure your DB credentials are correct.

---

### 3️⃣ Add Services Config

📄 **File:** `config/services.php`
```php
'newsapi' => [
    'key' => env('NEWSAPI_KEY'),
],
'guardian' => [
    'key' => env('GUARDIAN_KEY'),
],
'nyt' => [
    'key' => env('NYT_KEY'),
],
```

---

### 4️⃣ Run Migrations

```bash
php artisan migrate
```

---

### 5️⃣ Fetch Initial Articles

```bash
php artisan news:fetch
```

---

### 6️⃣ Serve the App

```bash
php artisan serve
```

The API will be available at  
👉 `http://127.0.0.1:8000/api/articles`

---

## 🌐 API Endpoints

### 🔹 List Articles
**GET** `/api/articles`

| Parameter | Type | Description |
|------------|------|-------------|
| `q` | string | Keyword in title |
| `source` | string | e.g. "NewsAPI" |
| `category` | string | e.g. "Technology" |
| `author` | string | Filter by author |
| `date_from` | date | Start date |
| `date_to` | date | End date |

**Example**
```
GET /api/articles?q=AI&source=NewsAPI&date_from=2025-11-01&date_to=2025-11-08
```

**Response**
```json
{
  "status": "success",
  "message": "Articles retrieved successfully",
  "data": [
    {
      "title": "AI Revolution",
      "author": "John Doe",
      "source": "Newsapi",
      "category": "technology",
      "url": "https://example.com/ai",
      "image": "https://example.com/image.jpg",
      "published_at": "2025-11-08 09:30:00"
    }
  ]
}
```

---

### 🔹 Show Single Article

**By ID**
```
GET /api/articles/5
```

**By URL**
```
GET /api/articles?url=https://example.com/ai
```

**Response**
```json
{
  "status": "success",
  "message": "Article retrieved successfully",
  "data": {
    "title": "AI Revolution",
    "author": "John Doe",
    "source": "Newsapi",
    "url": "https://example.com/ai",
    "category": "technology",
    "image": "https://example.com/image.jpg",
    "published_at": "2025-11-08 09:30:00"
  }
}
```

If not found:
```json
{
  "status": "error",
  "message": "Article not found"
}
```

---

## 🕐 Automatic Scheduling (Laravel 12)

Laravel 12 moved scheduling definitions to `routes/console.php`.

📄 **File:** `routes/console.php`
```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('news:fetch')
    ->hourly()
    ->withoutOverlapping()
    ->onSuccess(fn() => \Log::info('Scheduled news sync ran successfully at ' . now()))
    ->onFailure(fn() => \Log::error('Scheduled news sync failed at ' . now()));
```

Then set up a system cron on your server:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

✅ This will run `news:fetch` automatically every hour.

---

## 🧰 Manual Command

You can always run it yourself:
```bash
php artisan news:fetch
```

---

## 📁 Folder Structure Overview

```
app/
 ├── Console/
 │    └── Commands/FetchNewsCommand.php
 ├── Helpers/ApiResponseHelper.php
 ├── Http/
 │    ├── Controllers/Api/ArticleController.php
 │    ├── Requests/ArticleFilterRequest.php
 │    └── Resources/ArticleResource.php
 ├── Models/Article.php
 ├── Providers/AppServiceProvider.php
 └── Services/
      ├── Interfaces/NewsSourceInterface.php
      ├── News/
      │     ├── AbstractNewsService.php
      │     ├── NewsApiService.php
      │     ├── GuardianService.php
      │     └── NYTService.php
      └── SyncNewsService.php
```

---

## 👨‍💻 Author

**Developed by:** *Biniam Shiferaw*  
**Email:** *seifubini@gmail.com*  
**LinkedIn:** [linkedin.com/in/yourprofile](https://www.linkedin.com/in/biniam-shiferaw-73099a119)

---

## ✅ Submission Checklist

- [x] 3 source integrations (NewsAPI, Guardian, NYT)  
- [x] `news:fetch` Artisan command  
- [x] Hourly scheduling via `routes/console.php`  
- [x] API endpoints with filtering & single article retrieval  
- [x] Centralized JSON responses (`ApiResponseHelper`)  
- [x] Feature + Unit tests passing  
- [x] Detailed README with setup and usage  

---

## 🧭 Notes

- To fix local SSL issues, download `cacert.pem` from [curl.se/ca](https://curl.se/ca/cacert.pem) and add it to your PHP config (`curl.cainfo`).  
- For dev only, you can temporarily use `Http::withoutVerifying()` in the `AbstractNewsService` if you face SSL errors.  
- The scheduler automatically prevents overlapping syncs.  
- Extendable: just add new sources implementing `NewsSourceInterface`.

