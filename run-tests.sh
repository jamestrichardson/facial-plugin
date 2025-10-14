#!/bin/bash

# Facial Plugin Test Runner Script
# This script runs PHPUnit tests with various options

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}=== Facial Plugin Test Suite ===${NC}"
echo ""

# Check if PHPUnit is available
if ! command -v phpunit &> /dev/null; then
    echo -e "${YELLOW}PHPUnit not found. Installing via Composer...${NC}"

    # Check if Composer is available
    if ! command -v composer &> /dev/null; then
        echo -e "${RED}Composer not found. Please install Composer first.${NC}"
        echo "Visit: https://getcomposer.org/download/"
        exit 1
    fi

    composer install --dev
fi

# Create necessary directories
mkdir -p tests/coverage

echo -e "${GREEN}Running PHPUnit tests...${NC}"
echo ""

# Run tests with coverage
if phpunit --version &> /dev/null; then
    echo -e "${GREEN}Running tests with coverage report...${NC}"
    phpunit --configuration phpunit.xml --coverage-html tests/coverage --coverage-text

    # Check if tests passed
    if [ $? -eq 0 ]; then
        echo ""
        echo -e "${GREEN}✓ All tests passed!${NC}"
        echo -e "${GREEN}Coverage report generated in tests/coverage/${NC}"

        # Display coverage summary if coverage.txt exists
        if [ -f "tests/coverage.txt" ]; then
            echo ""
            echo -e "${YELLOW}Coverage Summary:${NC}"
            cat tests/coverage.txt | grep -E "(Classes:|Methods:|Lines:)" || true
        fi
    else
        echo ""
        echo -e "${RED}✗ Some tests failed!${NC}"
        exit 1
    fi
else
    echo -e "${RED}PHPUnit failed to run${NC}"
    exit 1
fi

echo ""
echo -e "${GREEN}Test run completed!${NC}"
