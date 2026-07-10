# PropertySpot Development Guide

## Project Structure
- Main application in `/laravel` directory (Laravel 8.x framework)
- Docker-based deployment with `Dockerfile` and `Dockerfile-dev`
- Database migrations in `/laravel/database/migrations`
- Test suite in `/laravel/tests`

## Key Commands
- **Run tests**: `cd laravel && php artisan test`
- **Run specific test**: `cd laravel && php artisan test --filter=TestName`
- **Start development server**: `cd laravel && php artisan serve`
- **Migrate database**: `cd laravel && php artisan migrate`
- **Reset migrations**: `cd laravel && php artisan migrate:reset`

## Important Architecture Notes
- Authentication uses Laravel's built-in system with custom middleware (`CheckIfUserCanAccessListing`)
- Users can be either 'user' or 'admin' roles
- Password reset tokens are valid for 30 minutes
- Stripe payment integration is implemented
- Email verification workflow exists
- Middleware ensures users can only access their own listings (`check-user` middleware)
- Admin routes are protected by `can:accessAdmin` policy

## Environment Setup
- Default environment uses MySQL database configured in `.env`
- Required environment variables include DB credentials, Stripe keys, and email configuration
- Email is sent via SMTP (Sendinblue) with default logging in local env
- Bugsnag error reporting is configured

## Authentication Flow
1. Users register via `/signup` endpoint
2. Login via `/signin` endpoint using email/password
3. Password reset token generation and validation
4. Admin access requires 'admin' role
5. Session management through Laravel's built-in auth system

## Testing Notes
- Tests use PHPUnit with Laravel's testing helpers
- Includes both Unit and Feature test suites
- Database tests run in-memory SQLite by default for speed
- Email verification and authentication flows are tested

## API Endpoints
- User profile photo upload endpoint: `/users/profile-photo` (POST)
- Stripe payment hooks: `/stripe/payment-hook`
- Form submissions: `/post-form`
- Listing website display: `/{slug}` (GET)

## Deployment
- Uses Docker containers with nginx + php-fpm
- CI/CD pipeline via Jenkinsfile
- Environment-specific configuration via `.env` files