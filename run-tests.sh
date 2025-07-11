#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}🧪 Laravel Unleash Test Suite${NC}"
echo "=================================="

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo -e "${YELLOW}Installing dependencies...${NC}"
    composer install
fi

echo -e "${YELLOW}Running PHPUnit tests with Pest...${NC}"
vendor/bin/pest --coverage --min=90

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ All tests passed!${NC}"
else
    echo -e "${RED}❌ Some tests failed!${NC}"
    exit 1
fi

echo -e "${YELLOW}Running PHPStan static analysis...${NC}"
vendor/bin/phpstan analyse --memory-limit=2G

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Static analysis passed!${NC}"
else
    echo -e "${YELLOW}⚠️  Static analysis found issues (continuing...)${NC}"
fi

echo -e "${YELLOW}Running PHP-CS-Fixer...${NC}"
vendor/bin/php-cs-fixer fix --dry-run --diff

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Code style check passed!${NC}"
else
    echo -e "${YELLOW}⚠️  Code style issues found. Run 'vendor/bin/php-cs-fixer fix' to fix them.${NC}"
fi

echo -e "${GREEN}🎉 Test suite completed!${NC}"
