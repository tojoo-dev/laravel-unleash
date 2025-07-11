# Laravel Unleash - Comprehensive Test Suite

## Overview

This document describes the comprehensive test suite created for the Laravel Unleash package, designed to achieve 100% code coverage and ensure robust CI/CD practices.

## Test Structure

### Unit Tests (`tests/Unit/`)

1. **UnleashTest.php** - Tests the main Unleash wrapper class

   - Client instantiation and management
   - Feature flag checking with exception handling
   - Feature listing and filtering
   - Variant handling
   - Repository access via reflection

2. **Middleware/CheckFeatureTest.php** - Tests the feature flag middleware

   - Request allowing when feature is enabled
   - Request blocking (404) when feature is disabled

3. **Cache/CacheHandlerTest.php** - Tests the cache handler

   - Cache bridge instantiation

4. **Cache/CacheBridgeTest.php** - Tests the PSR-16 cache bridge

   - Get/set operations
   - Multiple operations
   - Cache clearing and deletion
   - Key existence checking

5. **Cache/CacheBridgeAdditionalTest.php** - Additional cache bridge tests

   - Edge cases and return values

6. **Facades/UnleashTest.php** - Tests the Laravel facade

   - Facade accessor resolution
   - Method delegation

7. **Providers/ServiceProviderTest.php** - Tests the service provider

   - Service registration
   - Configuration publishing
   - Blade directive registration

8. **Providers/UnleashStrategiesProviderTest.php** - Tests strategy provider

   - Strategy collection
   - Strategy validation

9. **Providers/UnleashContextProviderTest.php** - Tests context provider

   - Context creation
   - User authentication integration

10. **Interfaces/InterfacesTest.php** - Tests interface definitions

    - Interface method validation

11. **ServiceProviderEdgeCasesTest.php** - Edge case testing
    - Complex configuration scenarios
    - HTTP client overrides
    - API key handling

### Feature Tests (`tests/Feature/`)

1. **UnleashIntegrationTest.php** - Basic integration tests

   - Container resolution
   - Facade integration
   - Configuration validation

2. **FullIntegrationTest.php** - Comprehensive integration tests
   - Service instantiation from config
   - Middleware integration with routes
   - Full workflow testing

## Code Coverage

The test suite aims for 100% code coverage across:

- All source classes in `src/`
- Public and protected methods
- Exception handling paths
- Configuration scenarios
- Integration points

### Coverage Reports

- **HTML Report**: Generated in `coverage/` directory
- **Text Report**: Output to `coverage.txt`
- **CI Coverage**: Reported to Codecov

## CI/CD Workflows

### 1. Tests (`tests.yml`)

- **Matrix Testing**: PHP 8.1, 8.2, 8.3 × Laravel 10.x, 11.x, 12.x
- **Stability Testing**: prefer-lowest and prefer-stable
- **Coverage Requirement**: Minimum 90% coverage
- **Coverage Reporting**: Codecov integration

### 2. Static Analysis (`static-analysis.yml`)

- **PHPStan**: Level 5 static analysis
- **PHP-CS-Fixer**: Code style enforcement
- **Memory Optimization**: 2GB memory limit for analysis

### 3. Release (`release.yml`)

- **Automated Releases**: Triggered on version tags
- **Test Validation**: Runs full test suite before release
- **Packagist Integration**: Automatic package updates

### 4. Dependencies (`dependencies.yml`)

- **Scheduled Updates**: Weekly dependency updates
- **Automated PRs**: Creates pull requests for dependency updates
- **Test Validation**: Ensures updates don't break functionality

## Development Tools

### Configuration Files

1. **phpunit.xml** - PHPUnit/Pest configuration

   - Test suites definition
   - Coverage settings
   - Environment variables

2. **phpstan.neon** - PHPStan configuration

   - Analysis level 5
   - Error ignoring for Laravel specifics
   - Source path configuration

3. **php-cs-fixer.php** - Code style configuration
   - PSR-12 compliance
   - Custom rules for Laravel
   - Import ordering and formatting

### Scripts

1. **run-tests.sh** - Comprehensive test runner

   - Dependency installation
   - Test execution with coverage
   - Static analysis
   - Code style checking
   - Colored output for better UX

2. **Composer Scripts**
   - `composer test` - Run tests
   - `composer test-coverage` - Run with coverage
   - `composer test-coverage-html` - Generate HTML coverage

## Test Best Practices

### Mocking Strategy

- **External Dependencies**: Mock Unleash client and Laravel services
- **Isolation**: Each test is isolated with proper cleanup
- **Mockery**: Used for comprehensive mocking capabilities

### Test Organization

- **Descriptive Names**: Test names clearly describe what is being tested
- **Single Responsibility**: Each test focuses on one specific behavior
- **Setup/Cleanup**: Proper before/after hooks for test isolation

### Edge Cases

- **Exception Handling**: Tests cover all exception scenarios
- **Configuration Variations**: Tests multiple configuration combinations
- **Boundary Conditions**: Tests edge cases and limits

## Quality Assurance

### Continuous Integration

- **Multiple PHP Versions**: Ensures compatibility across PHP 8.1+
- **Multiple Laravel Versions**: Tests Laravel 10.x and 11.x
- **Dependency Variations**: Tests with different dependency versions

### Code Quality

- **Static Analysis**: PHPStan ensures type safety
- **Code Style**: PHP-CS-Fixer ensures consistent formatting
- **Coverage**: Minimum 90% coverage requirement

### Documentation

- **Inline Documentation**: All test methods have clear descriptions
- **README Updates**: Testing documentation included in README
- **Workflow Documentation**: GitHub Actions documented

## Usage

```bash
# Install dependencies
composer install

# Run all tests
./run-tests.sh

# Run specific test suites
vendor/bin/pest tests/Unit/
vendor/bin/pest tests/Feature/

# Generate coverage report
vendor/bin/pest --coverage-html coverage

# Run static analysis
vendor/bin/phpstan analyse

# Fix code style
vendor/bin/php-cs-fixer fix
```

## Maintenance

The test suite is designed for easy maintenance:

1. **Automated Updates**: Dependencies are automatically updated weekly
2. **Clear Structure**: Tests are organized by component and responsibility
3. **Comprehensive Coverage**: New code requires corresponding tests
4. **CI Validation**: All changes are validated through CI/CD pipeline

This comprehensive test suite ensures the Laravel Unleash package is robust, reliable, and ready for production use across various Laravel and PHP environments.
