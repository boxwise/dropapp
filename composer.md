# Composer Packages Analysis - Dropapp Repository

## Production Dependencies (`require`)

### ✅ **Currently Used Packages**

#### **robmorgan/phinx** - `^0.14`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: Database migration tool
- **Usage**: Extensive usage in `db/migrations/` directory with 20+ migration files
- **Evidence**: All migration files extend `Phinx\Migration\AbstractMigration`
- **Configuration**: `phinx.yml` configuration file present

#### **smarty/smarty** - `^4.5.3`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: Template engine for rendering PHP templates
- **Usage**: Core templating system for the application
- **Evidence**:
  - Custom `Zmarty` class extends `Smarty` in `library/lib/smarty.php`
  - Used in `include/stock_edit.php` and other files
  - Compiled templates in `templates/templates_c/` directory
  - Referenced in build process

#### **sendgrid/sendgrid** - `^8.1`

- **Status**: ✅ **PARTIALLY USED**
- **Purpose**: Email sending service
- **Usage**: Email functionality (currently limited)
- **Evidence**:
  - Implemented in `library/lib/mail.php`
  - Configuration key in `library/config.php`
  - Comment indicates most emails are handled by Auth0+SendGrid integration
- **Note**: Not heavily used directly, mostly through Auth0 integration

#### **auth0/auth0-php** - `^8.0.5`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: Authentication and authorization via Auth0
- **Usage**: Core authentication system
- **Evidence**:
  - Comprehensive implementation in `library/lib/auth0.php`
  - Used in `cypress-session.php` for testing
  - Configuration in `library/config.php`
  - Multiple Auth0-related settings and functions

#### **google/cloud-storage** - `^1.48`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: Google Cloud Storage integration
- **Usage**: File storage and management
- **Evidence**: Used in `library/gcloud.php` with `StorageClient`

#### **google/cloud-logging** - `1.28.*`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: Google Cloud Logging integration
- **Usage**: Application logging in Google Cloud environment
- **Evidence**: Integrated in Google Cloud services setup in `library/gcloud.php`

needed to lock down this version. Otherwise, this error happens:

> PHP message: PHP Fatal error: Declaration of Google\Cloud\Logging\PsrLogger::emergency(Stringable|string $message, array $context = []): void must be compatible with Psr\Log\LoggerInterface::emergency($message, array $context = []) in /workspace/vendor/google/cloud-logging/src/PsrLogger.php on line 172

#### **google/cloud-error-reporting** - `^0.22`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: Google Cloud Error Reporting
- **Usage**: Error tracking and monitoring
- **Evidence**:
  - Loaded in `library/gcloud.php`
  - Prepend file included for automatic error reporting

#### **google/cloud-datastore** - `^1.32`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: Google Cloud Datastore (NoSQL database)
- **Usage**: Session storage and data persistence
- **Evidence**:
  - Used in `library/gcloud.php` and `cron/dailyroutine.php`
  - `DatastoreClient` and `DatastoreSessionHandler` implementations

#### **opencensus/opencensus** - `^0.7.0`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: Distributed tracing and monitoring
- **Usage**: Performance monitoring and tracing across the application
- **Evidence**:
  - Imported in multiple core files (`index.php`, `gcloud-entry.php`, etc.)
  - `Tracer` class used throughout the codebase

#### **opencensus/opencensus-exporter-stackdriver** - `^0.1.0`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: Export OpenCensus traces to Google Cloud (Stackdriver)
- **Usage**: Monitoring integration with Google Cloud
- **Evidence**: Used in `library/gcloud.php` with `StackdriverExporter`

#### **endroid/qr-code** - `^4.4`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: QR code generation
- **Usage**: Generate QR codes for boxes/items
- **Evidence**:
  - Comprehensive usage in `library/functions.php`
  - Multiple QR code classes imported and used
  - QR code generation function for mobile app integration

#### **sentry/sentry** - `^4.15`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: Error tracking and monitoring
- **Usage**: Application error reporting and monitoring
- **Evidence**:
  - Configuration in `library/config.php` and `library/error-reporting.php`
  - Used in `library/lib/errorhandling.php`
  - Sentry DSN configured

#### **kriswallsmith/buzz** - `^1.2`

- **Status**: ✅ **USED FOR AUTH0**
- **Purpose**: HTTP client library
- **Usage**: HTTP client for Auth0 SDK
- **Evidence**: Used in `library/lib/auth0.php` as `MultiCurl` HTTP client

#### **nyholm/psr7** - `^1.4`

- **Status**: ✅ **USED FOR AUTH0**
- **Purpose**: PSR-7 HTTP message implementation
- **Usage**: HTTP message factory for Auth0 SDK
- **Evidence**: Used in `library/lib/auth0.php` as `Psr17Factory`

### ❓ **Unclear/Potentially Unused Packages**

#### **open-telemetry/exporter-otlp** - `^1.0`

- **Status**: ❓ **NO USAGE FOUND**
- **Purpose**: OpenTelemetry OTLP exporter
- **Usage**: No evidence of usage found
- **Evidence**: No imports found
- **Recommendation**: Consider removing or investigate migration from OpenCensus

#### **open-telemetry/sdk** - `^1.0`

- **Status**: ❓ **NO USAGE FOUND**
- **Purpose**: OpenTelemetry SDK
- **Usage**: No evidence of usage found
- **Evidence**: No imports found
- **Recommendation**: Consider removing or investigate migration from OpenCensus

## Development Dependencies (`require-dev`)

### ✅ **Currently Used Packages**

#### **friendsofphp/php-cs-fixer** - `^3.5`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: PHP code style fixer
- **Usage**: Code formatting and style enforcement
- **Evidence**: Used in CircleCI pipeline (`.circleci/config.yml`)
- **Command**: `php vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix`

#### **php-parallel-lint/php-parallel-lint** - `^1.3`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: PHP syntax checking tool
- **Usage**: PHP syntax validation in CI/CD
- **Evidence**: Used in CircleCI pipeline
- **Command**: `vendor/bin/parallel-lint --exclude vendor .`

#### **rector/rector** - `^2.1`

- **Status**: ✅ **CONFIGURED**
- **Purpose**: PHP automated refactoring tool
- **Usage**: Code modernization and refactoring
- **Evidence**: Configuration file `rector.php` exists with rules configured
- **Note**: Configured but usage in CI not confirmed
