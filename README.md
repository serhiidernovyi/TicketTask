# Smart Ticket Triage & Dashboard

A production-style single-page application that helps help-desk teams manage support tickets with AI-powered classification and analytics.

## Features

- **Ticket Management**: Create, view, update, and filter support tickets
- **AI Classification**: Automatic ticket categorization using OpenAI GPT
- **Analytics Dashboard**: Real-time statistics and charts
- **Manual Override**: Override AI classifications and add internal notes
- **Rate Limiting**: API protection against spam
- **Bulk Operations**: Console commands for mass classification

## Tech Stack

### Backend
- **Laravel 11** with kernel-less structure
- **PHP 8.2** with strict types
- **MySQL** database
- **OpenAI API** for AI classification
- **Domain-Driven Design (DDD)** architecture

### Frontend (Coming Soon)
- **Vue 3** with Options API (planned)
- **Vite** for building (planned)
- **BEM CSS** methodology (planned)
- **Chart.js** for analytics (planned)

## System Requirements

### For Docker Setup (Recommended)
- **Docker** 20.10+
- **Docker Compose** 2.0+
- **Git** 2.0+
- **4GB RAM** minimum
- **2GB free disk space**

### For Local Development
- **PHP** 8.2+
- **Composer** 2.0+
- **MySQL** 8.0+
- **4GB RAM** minimum

## Quick Setup

### Option 1: Docker (Recommended for beginners)

**🚀 One-Command Setup:**
```bash
git clone <repository-url> && cd MyTasks/_entry/_docker && docker-compose up -d
```

**Then follow these steps:**

1. **Enter the container**
   ```bash
   docker exec -it task_app bash
   ```

2. **Quick setup script**
   ```bash
   # Inside the container - run all setup commands
   composer install && cp .env.example .env && php artisan key:generate && php artisan migrate --seed
   ```

3. **Access the application**
   - **API**: http://localhost:8000/api
   - **Database**: localhost:3306 (user: mytasks, password: secret)

**That's it! 🎉**

### Option 2: Docker (Step-by-step)

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd MyTasks
   ```

2. **Start Docker containers**
   ```bash
   cd _entry/_docker
   docker-compose up -d
   ```

3. **Install dependencies inside container**
   ```bash
   # Enter the PHP container
   docker exec -it task_app bash
   
   # Install PHP dependencies
   composer install
   ```

4. **Environment setup**
   ```bash
   # Still inside the container
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   # Still inside the container
   php artisan migrate --seed
   ```

6. **Access the application**
   - **API**: http://localhost:8000/api
   - **Database**: localhost:3306 (user: mytasks, password: secret)

### Option 2: Local Development

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd MyTasks
   ```

2. **Install PHP dependencies**
   ```bash
   cd _entry
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate --seed
   ```

5. **Access the application**
   - **API**: http://localhost:8000/api

## Verify Installation

### Check if everything works:

1. **Test API endpoints**
   ```bash
   # Test stats endpoint
   curl http://localhost:8000/api/stats
   
   # Test tickets endpoint
   curl http://localhost:8000/api/tickets
   ```

2. **Run tests**
   ```bash
   # Inside Docker container
   docker exec -it task_app php artisan test
   
   # Or locally
   php artisan test
   ```

3. **Check database**
   ```bash
   # Connect to MySQL
   docker exec -it mysql mysql -u mytasks -p
   # Password: secret
   
   # Check tables
   USE mytasks;
   SHOW TABLES;
   SELECT COUNT(*) FROM tickets;
   ```

4. **Test AI classification**
   ```bash
   # Inside container
   docker exec -it task_app php artisan tickets:bulk-classify --unclassified
   ```

### Expected Results:
- ✅ API returns JSON responses
- ✅ All tests pass (26 tests)
- ✅ Database has tickets table with data
- ✅ Classification command runs without errors

## API Endpoints

### Tickets
- `POST /api/tickets` - Create ticket
- `GET /api/tickets` - List tickets (with filters, search, pagination)
- `GET /api/tickets/{id}` - Get ticket details
- `PATCH /api/tickets/{id}` - Update ticket
- `POST /api/tickets/{id}/classify` - Classify ticket with AI

### Analytics
- `GET /api/stats` - Get dashboard statistics

### Rate Limits
- Tickets: 60 requests/minute
- Classify: 10 requests/minute
- Stats: 30 requests/minute

## Docker Setup (For Beginners)

### Prerequisites
- **Docker** and **Docker Compose** installed
- **Git** for cloning the repository

### What's Included
The Docker setup includes:
- **PHP 8.2-FPM** with Xdebug for debugging
- **Nginx** web server
- **MySQL 8.0** database
- **MySQL Test** database for testing

### Step-by-Step Docker Setup

1. **Clone and navigate**
   ```bash
   git clone <repository-url>
   cd MyTasks/_entry/_docker
   ```

2. **Start all services**
   ```bash
   docker-compose up -d
   ```
   This will start:
   - PHP-FPM container (task_app)
   - Nginx web server (port 8000)
   - MySQL database (port 3306)
   - MySQL test database (port 3301)

3. **Enter the PHP container**
   ```bash
   docker exec -it task_app bash
   ```

4. **Install dependencies**
   ```bash
   # Inside the container
   composer install
   ```

5. **Configure environment**
   ```bash
   # Inside the container
   cp .env.example .env
   php artisan key:generate
   ```

6. **Setup database**
   ```bash
   # Inside the container
   php artisan migrate --seed
   ```

7. **Access the application**
   - **Main App**: http://localhost:8000
   - **API**: http://localhost:8000/api
   - **Database**: localhost:3306 (mytasks/secret)

### Docker Commands

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f

# Restart services
docker-compose restart

# Enter PHP container
docker exec -it task_app bash

# Enter MySQL container
docker exec -it mysql mysql -u mytasks -p

# Run tests inside container
docker exec -it task_app php artisan test

# Run queue worker
docker exec -it task_app php artisan queue:work
```

### Troubleshooting Docker

**Port conflicts:**
```bash
# Check what's using port 8000
lsof -i :8000

# Stop conflicting services
sudo lsof -ti:8000 | xargs kill -9
```

**Permission issues:**
```bash
# Fix file permissions
sudo chown -R $USER:$USER /path/to/MyTasks
```

**Database connection issues:**
```bash
# Check MySQL logs
docker-compose logs mysql

# Reset database
docker-compose down -v
docker-compose up -d
```

## Console Commands

```bash
# Bulk classify tickets
php artisan tickets:bulk-classify --unclassified
php artisan tickets:bulk-classify --all
php artisan tickets:bulk-classify --force

# Queue management
php artisan queue:work
php artisan queue:failed
```

## AI Classification

The system uses OpenAI's GPT models to automatically classify tickets into categories:
- **bug** - Software defects
- **feature** - Feature requests
- **question** - User inquiries
- **complaint** - User complaints
- **compliment** - Positive feedback
- **general** - General issues

### Configuration

Set `OPENAI_CLASSIFY_ENABLED=true` in `.env` to enable AI classification:
```env
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_CLASSIFY_ENABLED=true
```

When disabled, the system returns random categories for testing.

## Database Schema

### Tickets Table
- `id` (ULID) - Primary key
- `subject` (string) - Ticket title
- `body` (text) - Ticket description
- `status` (enum) - new, open, pending, closed
- `category` (string, nullable) - AI or manual classification
- `explanation` (text, nullable) - AI explanation
- `confidence` (decimal) - AI confidence (0.00-1.00)
- `note` (text, nullable) - Internal notes
- `category_is_manual` (boolean) - Manual override flag
- `category_changed_at` (timestamp, nullable) - Last category change
- `created_at`, `updated_at` - Timestamps

## Architecture

### Domain-Driven Design (DDD)

```
Ticket/                          # Domain Layer
├── Contracts/
│   ├── Entities/TicketInterface.php
│   └── Services/
├── Entities/Ticket.php
├── Services/
└── Filters/

Classification/                  # Domain Layer
├── Contracts/ClassifierInterface.php
├── Services/TicketClassifier.php
└── ValueObjects/ClassificationResult.php

UseCases/                       # Application Layer
├── Classification/ClassifyTicket.php
├── Ticket/Ticket.php
├── Ticket/Stats.php
└── DomainServiceFactory.php

_entry/app/                     # Infrastructure Layer
├── Http/Controllers/
├── Jobs/ClassifyTicket.php
├── Models/Ticket.php
└── Resources/
```

### Key Components

- **Entities**: Core business objects
- **Services**: Domain logic and business rules
- **Use Cases**: Application orchestration
- **Jobs**: Asynchronous processing
- **Controllers**: HTTP request handling
- **Resources**: API response formatting

## Testing

```bash
# Run all tests
php artisan test

# Run specific test suites
php artisan test --filter=StatsTest
php artisan test --filter=TicketClassifyTest

# Run with coverage
php artisan test --coverage
```

### Test Coverage
- **Feature Tests**: API endpoints, rate limiting, data validation
- **Unit Tests**: Domain logic, services, value objects
- **Integration Tests**: AI classification, queue processing

## Assumptions & Trade-offs

### Assumptions
1. **Single-tenant system** - No multi-tenant architecture
2. **English-only** - No internationalization
3. **OpenAI dependency** - Relies on external AI service
4. **Synchronous API** - Real-time responses expected
5. **MySQL database** - No database abstraction layer

### Trade-offs
1. **AI Classification** - Chose OpenAI over local ML for simplicity
2. **Queue Processing** - Database queue over Redis for setup simplicity
3. **Rate Limiting** - Laravel built-in over custom implementation
4. **Testing** - Feature tests over extensive unit tests for speed
5. **Error Handling** - Graceful degradation over strict validation

### What I'd do with more time

1. **Frontend Implementation**
   - Complete Vue 3 SPA with all features
   - Real-time updates with WebSockets
   - Advanced filtering and search UI
   - Dark/light theme toggle

2. **Enhanced AI Features**
   - Custom model training on ticket data
   - Confidence threshold configuration
   - Multi-language support
   - Sentiment analysis

3. **Performance Optimizations**
   - Redis caching layer
   - Database query optimization
   - CDN for static assets
   - API response compression

4. **Monitoring & Observability**
   - Application performance monitoring
   - Error tracking and alerting
   - Usage analytics
   - Health check endpoints

5. **Security Enhancements**
   - API authentication (JWT/Sanctum)
   - Role-based access control
   - Input sanitization
   - CSRF protection

6. **DevOps & Deployment**
   - Docker containerization
   - CI/CD pipeline
   - Environment-specific configs
   - Database migrations strategy

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

## License

This project is licensed under the MIT License.
