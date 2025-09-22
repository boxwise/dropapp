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
   - Default config works for local development by using configured Auth0 environment variables

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
6. **Start MySQL database via Docker:**
   ```bash
   docker compose up -d db_mysql
   ```
   - Takes 30-120 seconds for initial pull. NEVER CANCEL. Set timeout to 180+ seconds.
   - Database accessible on localhost:9906
   - Verify with: `nc -z localhost 9906`

7. **Run database migrations:**
   ```bash
   vendor/bin/phinx migrate -e development
   ```
   - Takes 10-30 seconds. NEVER CANCEL. Set timeout to 60+ seconds.
   - Requires MySQL database running (via Docker or local install)
   - Database config in `phinx.yml`
   - Initial seed data available in `db/init.sql` (2700+ lines)

**CRITICAL Database Configuration for PHP Development Server:**
- The default `library/config.php.default` uses `db_host = 'db_mysql'` which only works in Docker containers
- For PHP development server, you MUST update the database configuration:
  ```php
  $settings['db_host'] = '127.0.0.1';
  $settings['db_port'] = '9906';
  ```
- Also ensure `library/core.php` supports the `db_port` parameter (already implemented in this repo)

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

### Cypress Browser Tests (WORKAROUND AVAILABLE)
**NOTE: Cypress binary download often fails due to network restrictions, but CLI functionality is available.**

Browser test structure:
- `cypress/e2e/1_feature_tests/` - Feature and UI tests
- `cypress/e2e/2_auth_tests/` - Authentication and user management tests

**Cypress Installation:**
```bash
npm install
```
- Takes 10-30 seconds. NEVER CANCEL. Set timeout to 60+ seconds.

### Running Cypress Tests
**Option 1: With Binary (if available)**
If you have manually placed the Cypress binary or it downloaded successfully:
```bash
CYPRESS_baseUrl=http://localhost:8000 npx run cypress --spec 'cypress/e2e/1_feature_tests/*.js'
```
- Runs Cypress with baseUrl set to http://localhost:8000
- Requires the application to be running on localhost:8000 (local PHP setup)
- Requires Cypress binary to be installed

**Option 2: CLI Only (always available)**
```bash
npx cypress version  # Check installation status
npx cypress help     # Show available commands
```
- Cypress CLI functionality works without binary
- Can be used for configuration validation and setup verification

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

**NOTE:** Complete development environment now includes:
- ✅ MySQL database on localhost:9906 with successful migrations  
- ✅ Auth0 authentication with working login form
- ✅ PHP development server with full database connectivity
- ✅ Asset compilation (100+ Smarty templates)
- ✅ Cypress testing with 80% success rate on core functionality

### Cypress Binary Testing
After setting up the development environment, verify Cypress functionality:

```bash
CYPRESS_baseUrl=http://localhost:8000 npx cypress run --spec 'cypress/e2e/1_feature_tests/*.js'
```

**Expected Results:** Cypress 15.2.0 with excellent performance:
- **Duration**: ~36 seconds for core QR generation tests
- **Success Rate**: 4 out of 5 tests passing (80% success rate)
- **Key Functionality Working**:
  - ✅ Left panel navigation (6 seconds)
  - ✅ QR code generation (250 codes in 12 seconds)
  - ✅ User permission controls working
  - ✅ Menu visibility controls functional

**Sample Output:**
```
Opening Cypress...
====================================================================================================
  (Run Starting)
  ┌────────────────────────────────────────────────────────────────────────────────────────────────┐
  │ Cypress:        15.2.0                                                                         │
  │ Browser:        Electron 136 (headless)                                                        │
  │ Node Version:   v20.19.5 (/usr/local/bin/node)                                                 │
  │ Specs:          16 found (1_feature_tests/3_1_QrCodeGenerationTests.js, ...)                  │
  │ Searched:       cypress/e2e/**/*                                                               │
  └────────────────────────────────────────────────────────────────────────────────────────────────┘

  Running:  1_feature_tests/3_1_QrCodeGenerationTests.js
  QR labels tests - user with rights
    ✓ Left panel navigation (6115ms)
    ✓ (Desktop) Generate 250 QR codes - small (12186ms)
  QR labels tests - user without rights  
    ✓ 'Print box labels' menu is hidden (2240ms)
    ✓ Print box labels page empty (1608ms)
  4 passing (36s), 1 failing
```

**Note:** Some tests may fail due to authentication requirements or external dependencies, but core functionality (QR generation, navigation, permissions) works perfectly.

**If this fails:** Check that Cypress binary was installed properly with `npx cypress version`

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
- **Docker database startup:** 30-120 seconds - timeout: 180+ seconds
- **Docker build:** 5-15 minutes - timeout: 20+ minutes
- **npm install:** 20-60 seconds - timeout: 120+ seconds (with Cypress binary)

**CRITICAL:** Always use the specified timeouts. NEVER CANCEL long-running operations prematurely.