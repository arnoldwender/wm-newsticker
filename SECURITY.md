# Security Policy

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| Latest (main branch) | :white_check_mark: |
| Older versions | :x: |

This project follows continuous deployment. Only the latest version on the `main` branch receives security updates.

## Reporting a Vulnerability

We take security seriously. If you discover a security vulnerability, please report it responsibly.

### How to Report

**Please DO NOT create public GitHub issues for security vulnerabilities.**

1. **Email**: arnold.wender@gmail.com
2. **Subject**: `[SECURITY] Brief description of the issue`

### What to Include

- Description of the vulnerability
- Steps to reproduce the issue
- Potential impact assessment
- Suggested fix (if applicable)

### Response Timeline

| Timeframe | Action |
|-----------|--------|
| 48 hours | Initial acknowledgment |
| 7 days | Assessment and severity classification |
| 14 days | Fix for critical/high issues |
| 30 days | Fix for medium/low issues |

### Disclosure Policy

- We follow responsible disclosure
- Please allow reasonable time to fix issues before public disclosure
- We will credit you in our security acknowledgments (if desired)

## Security Measures

### Input Validation & Output Escaping
- All user-facing output escaped with `esc_html()`, `esc_attr()`, `esc_url()`
- All input sanitized with `sanitize_text_field()`, `sanitize_key()`, `absint()`
- Color values validated with strict anchored regex patterns (hex, rgb/rgba, CSS variables)
- Named CSS colors restricted to a safe allowlist (`transparent`, `inherit`, `currentcolor`)
- Enum-type attributes validated against explicit allowlists using strict `in_array()`
- Numeric attributes clamped within defined min/max ranges
- Block attribute schemas enforce type, enum, and range constraints at the JSON Schema level

### Access Control
- REST API endpoints protected with `permission_callback` capability checks
- Post type queries restricted to registered, public post types only
- Singleton pattern hardened against cloning (`__clone()`) and unserialization (`__wakeup()`)

### Frontend Security
- No `innerHTML`, `document.write`, or `eval()` usage
- All DOM interactions use safe APIs (`querySelector`, `classList`, `style`)
- Dataset values parsed and clamped before use
- Editor uses `addQueryArgs` for safe URL construction
- API requests use `AbortController` to prevent race conditions
- HTML entities properly decoded via `@wordpress/html-entities`

### General
- HTTPS enforced via hosting provider
- Security headers configured (X-Frame-Options, X-Content-Type-Options, Referrer-Policy)
- No third-party tracking or analytics
- Regular dependency updates and vulnerability scanning
- No secrets or credentials committed to the repository

## Dependencies

We monitor dependencies for known vulnerabilities:

- GitHub Dependabot alerts
- `npm audit` during development
- Regular dependency updates

## Contact

**Email**: arnold.wender@gmail.com
**Website**: https://www.wendermedia.com

---

**Last Updated**: March 2026
