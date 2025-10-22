# Git Best Practices for Tests/

## ✅ What to Commit

### Test Source Code (YES)
- `tests/*.php` - All PHPUnit test files
- `tests/bootstrap.php` - Test initialization
- `tests/README.md` - Test documentation
- `phpunit.xml` - PHPUnit configuration
- `.phpcs.xml.dist` - Code style configuration

**Why?** These are source code - they define your tests and need version control.

## ❌ What NOT to Commit

### Generated Test Outputs (NO)
- `tests/coverage/` - HTML coverage reports
- `tests/coverage.xml` - Clover XML coverage
- `tests/coverage.txt` - Text coverage output
- `tests/results.xml` - JUnit test results
- `.phpunit.result.cache` - PHPUnit cache

**Why?** These are generated on every test run:
1. **Large files** - Coverage HTML can be megabytes
2. **Constant changes** - Every test run changes them
3. **CI/CD generates them** - GitHub Actions creates fresh ones
4. **Merge conflicts** - Binary/generated files cause conflicts
5. **No value** - They're derived from source code

## Current .gitignore
```
contrib/
.phpunit.result.cache

# Test coverage and results (generated files)
tests/coverage/
tests/coverage.xml
tests/coverage.txt
tests/results.xml
```

## Repository State
✅ Test source files tracked
✅ Generated outputs ignored
✅ `tests/results.xml` removed from tracking

## GitHub Actions Behavior
GitHub Actions will:
1. Check out your source code (including test files)
2. Run `composer install`
3. Run `composer run-script test-coverage`
4. Generate fresh coverage.xml
5. Upload to SonarQube/Codecov
6. Discard generated files after run

## Summary
**Commit test code, ignore test outputs.** This is standard practice for all projects.
