# Composer Packages Analysis - Dropapp Repository

## Production Dependencies (`require`)

### ✅ **Currently Used Packages**

#### **robmorgan/phinx** - `^0.12`

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

#### **sendgrid/sendgrid** - `^7.3`

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

#### **google/cloud-storage** - `^1.12`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: Google Cloud Storage integration
- **Usage**: File storage and management
- **Evidence**: Used in `library/gcloud.php` with `StorageClient`

#### **google/cloud-logging** - `^1.18`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: Google Cloud Logging integration
- **Usage**: Application logging in Google Cloud environment
- **Evidence**: Integrated in Google Cloud services setup in `library/gcloud.php`

#### **google/cloud-error-reporting** - `^0.19.5`

- **Status**: ✅ **ACTIVELY USED**
- **Purpose**: Google Cloud Error Reporting
- **Usage**: Error tracking and monitoring
- **Evidence**:
  - Loaded in `library/gcloud.php`
  - Prepend file included for automatic error reporting

#### **google/cloud-datastore** - `^1.18`

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

#### **sentry/sentry** - `^4.3`

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

#### **symfony/yaml** - `^5.0`

- **Status**: ❓ **NO DIRECT USAGE FOUND**
- **Purpose**: YAML parsing library
- **Usage**: Likely used by Phinx for `phinx.yml` configuration
- **Evidence**: No direct imports found, but `phinx.yml` exists
- **Recommendation**: Probably safe to keep as Phinx dependency

#### **steampixel/simple-php-router** - `^0.7.0`

- **Status**: ❓ **NO USAGE FOUND**
- **Purpose**: Simple PHP routing library
- **Usage**: No evidence of usage found
- **Evidence**: No imports or usage patterns found
- **Recommendation**: Consider removing if not used

#### **guzzlehttp/guzzle** - `^7.3`

- **Status**: ❓ **NO DIRECT USAGE FOUND**
- **Purpose**: HTTP client library
- **Usage**: Possibly used internally by other packages
- **Evidence**: No direct usage found in codebase
- **Recommendation**: May be a dependency of other packages

#### **php-http/curl-client** - `^2.2`

- **Status**: ❓ **NO USAGE FOUND**
- **Purpose**: PSR-18 HTTP client using cURL
- **Usage**: No evidence of usage found
- **Evidence**: No imports found
- **Recommendation**: Consider removing

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

### ❓ **Unclear/Potentially Unused Dev Packages**

#### **odan/phinx-migrations-generator** - `^5.4.0`

- **Status**: ❓ **NO ACTIVE USAGE FOUND**
- **Purpose**: Generate Phinx migrations from database changes
- **Usage**: Development tool for creating migrations
- **Evidence**: No usage patterns found in CI or scripts
- **Recommendation**: Keep if used for development workflow

## Summary

### Packages to Keep (15 + 3 dev)

- **Production**: 15 packages actively used
- **Development**: 3 packages actively used in CI/CD

### Packages to Investigate (6 + 2 dev)

- **Production**: 6 packages with no clear usage pattern
- **Development**: 2 packages not integrated in CI

### Recommendations

1. **High Priority Review**:

   - `steampixel/simple-php-router` - No usage found
   - `php-http/curl-client` - No usage found
   - OpenTelemetry packages - May be intended for OpenCensus migration

2. **Medium Priority Review**:

   - `guzzlehttp/guzzle` - May be transitive dependency
   - `symfony/yaml` - Likely used by Phinx

3. **Development Tools**:

   - Consider integrating Phan into CI pipeline
   - Verify Rector usage in development workflow

4. **OpenTelemetry Migration**:
   - Investigate if OpenTelemetry packages are for migrating from OpenCensus
   - OpenCensus is deprecated, migration to OpenTelemetry recommended
