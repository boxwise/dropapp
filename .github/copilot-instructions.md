# Dropapp - Boxtribute v1 Web Application

Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.

Dropapp is a PHP web application for managing donated goods distribution to refugees and people in need. It uses PHP 8.2+, MySQL, Smarty templates, Auth0 authentication, Docker for local development, and Cypress for browser testing.

## General Instructions

**Think first and make a plan before you start implementing.** Always analyze the problem, understand the codebase, and create a clear implementation plan before making any changes.

**CRITICAL**: NEVER modify `.circleci/config.yml` or trigger any deployment manually. If changes to CI configuration are absolutely necessary, request approval in a PR comment.

**Always report issues with building/testing in the PR.** If you encounter build failures, test failures, or other issues during development, document them clearly in your progress reports so stakeholders are aware of any blockers or limitations.

## Working Effectively

### Bootstrap and Install Dependencies
1. **Install PHP dependencies:**
   ```bash
   composer install --no-dev --prefer-dist --no-interaction
   ```
   - Takes 30-60 seconds. NEVER CANCEL. Set timeout to 90+ seconds.
   - Production dependencies install reliably
   - For development dependencies: `composer install` (may require GitHub token)
   - If prompted for GitHub token, either provide one or use `--no-interaction` flag
   - If you get vendor directory errors, run: `rm -rf vendor/ && composer install`

2. **Install development dependencies (for linting):**
   ```bash
   composer install --prefer-dist --no-interaction
   ```
   - Takes 60-120 seconds. NEVER CANCEL. Set timeout to 180+ seconds.
   - May fail on some packages due to network restrictions
   - Continue with available packages if some fail

3. **Build static assets and templates:**
   ```bash
   php build.php
   ```
   - Takes ~1 second. NEVER CANCEL. Set timeout to 30+ seconds.
   - Compiles Smarty templates, minifies CSS/JS
   - May show PHP warnings - these are normal

### Setup Configuration
4. **Copy default configuration:**
   ```bash
   cp library/config.php.default library/config.php
   ```
   - Edit `library/config.php` to add Auth0 credentials if needed
   - Default config works for local development

5. **Configure Auth0 credentials (if available):**
   Copy the values from the repository environment variables/secrets to the respective places in `library/config.php`:
   - `AUTH0_API_AUDIENCE` → `$settings['auth0_api_audience']`
   - `AUTH0_CLIENT_ID` → `$settings['auth0_client_id']`
   - `AUTH0_CLIENT_SECRET` → `$settings['auth0_client_secret']`
   - `AUTH0_COOKIE_SECRET` → `$settings['auth0_cookie_secret']`
   - `AUTH0_DB_CONNECTION_ID` → `$settings['auth0_db_connection_id']`

### Running the Application

#### Option 1: PHP Development Server (RECOMMENDED)
```bash
php -S localhost:8000 gcloud-entry.php
```
- Application accessible at http://localhost:8000/
- Lightweight, reliable for development
- ALWAYS use this method when Docker fails

#### Option 2: Docker (MAY FAIL)
```bash
docker compose up --build
```
- Takes 5-15 minutes for initial build. NEVER CANCEL. Set timeout to 20+ minutes.
- Application accessible at http://localhost:8100/
- Docker build may fail on xdebug installation - this is a known issue
- Use PHP dev server if Docker fails

### Database Setup
6. **Run database migrations:**
   ```bash
   vendor/bin/phinx migrate -e development
   ```
   - Takes 10-30 seconds. NEVER CANCEL. Set timeout to 60+ seconds.
   - Requires MySQL database running (via Docker or local install)
   - Database config in `phinx.yml`
   - Initial seed data available in `db/init.sql` (2700+ lines)

## Linting and Code Quality

### PHP Syntax Checking
```bash
vendor/bin/parallel-lint --exclude vendor .
```
- Takes ~1.4 seconds. NEVER CANCEL. Set timeout to 30+ seconds.
- Checks all PHP files for syntax errors

### Code Formatting
```bash
php vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix . --dry-run --verbose --rules @PhpCsFixer
```
- Takes ~14 seconds. NEVER CANCEL. Set timeout to 60+ seconds.
- Shows formatting issues without fixing them
- Remove `--dry-run` to actually fix issues
- Generated Smarty templates in `templates/templates_c/` will show formatting issues - this is normal

### Auto-fix Code Style
```bash
php vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix . --rules @PhpCsFixer
```
- Takes ~15 seconds. NEVER CANCEL. Set timeout to 60+ seconds.
- Required before committing to pass CI

## Testing

### Cypress Browser Tests (LIMITED AVAILABILITY)
**NOTE: Cypress installation often fails due to network restrictions.**

Browser test structure:
- `cypress/e2e/1_feature_tests/` - Feature and UI tests
- `cypress/e2e/2_auth_tests/` - Authentication and user management tests

If Cypress is available:
```bash
npm install  # May fail due to network restrictions
```
- Takes 60-300 seconds. NEVER CANCEL. Set timeout to 600+ seconds.
- Installation frequently fails due to inability to download Cypress binary
- If installation fails, skip browser testing

Test user credentials (when Auth0 is configured):
- admin@admin.co / Browser_tests
- coordinator@coordinator.co / Browser_tests  
- user@user.co / Browser_tests

## Validation

### Manual Application Testing
After making changes, ALWAYS test the following scenarios:

1. **Start the application** using PHP dev server: `php -S localhost:8000 gcloud-entry.php`
2. **Access the homepage** at http://localhost:8000/
3. **Verify basic page loading** - should see Boxtribute interface or database connection error
4. **Test error handling** - visit non-existent page to check error display
5. **Check console/logs** for PHP errors or warnings

**NOTE:** Application requires database connection to fully function. Without MySQL running, you'll see database connection errors, which is expected.

### Pre-commit Validation
ALWAYS run these commands before committing:
```bash
vendor/bin/parallel-lint --exclude vendor .
php vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix . --rules @PhpCsFixer
php build.php
```

## Common Issues and Workarounds

### Composer GitHub Authentication
- If prompted for GitHub token, provide one or use `--no-interaction` flag
- Production dependencies usually install without token

### Composer State Issues
- If you get "uncommitted changes" errors, run: `rm -rf vendor/ && composer install`
- This clears any problematic vendor directory state

### Docker Build Failures
- Xdebug installation often fails in Docker environment
- Use PHP development server instead: `php -S localhost:8000 gcloud-entry.php`

### Cypress Installation Failures
- Network restrictions prevent Cypress binary download
- Skip browser testing if installation fails
- Manual testing is sufficient for most development

### Generated Template Formatting
- Smarty compiled templates in `templates/templates_c/` show CS Fixer violations
- These are auto-generated files - formatting issues are normal and expected
- Do not manually edit these files

## Key Project Structure

```
dropapp/
├── README.md              # Main documentation
├── CONTRIBUTING.md        # Contribution guidelines  
├── composer.json          # PHP dependencies
├── package.json           # Node.js dependencies
├── docker-compose.yml     # Docker configuration
├── build.php              # Build script for assets/templates
├── phinx.yml              # Database migration config
├── library/               # Core PHP application code
│   ├── config.php.default # Configuration template
│   ├── functions.php      # Utility functions
│   └── core.php           # Application core
├── db/                    # Database files
│   ├── init.sql           # Initial database seed
│   └── migrations/        # Phinx migration files
├── templates/             # Smarty template files
├── assets/                # CSS, JS, images
├── cypress/               # Browser test files (if available)
└── .circleci/             # CI/CD configuration
```

## Time Estimates and Timeouts

- **Composer install (production):** 30-60 seconds - timeout: 90+ seconds
- **Composer install (dev):** 60-120 seconds - timeout: 180+ seconds  
- **Build process:** 1 second - timeout: 30+ seconds
- **PHP linting:** 1.4 seconds - timeout: 30+ seconds
- **Code formatting:** 14 seconds - timeout: 60+ seconds
- **Database migration:** 10-30 seconds - timeout: 60+ seconds
- **Docker build:** 5-15 minutes - timeout: 20+ minutes
- **npm install:** 60-300 seconds - timeout: 600+ seconds

**CRITICAL:** Always use the specified timeouts. NEVER CANCEL long-running operations prematurely.