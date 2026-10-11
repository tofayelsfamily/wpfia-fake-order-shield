=== WPFIA Fake Order & Spam Checkout Shield ===
Contributors: tofayelamin
Tags: woocommerce, fake orders, spam shield, honeypot, checkout security
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lightweight, high-performance checkout fraud protection for WooCommerce. Stops card testing, bot spam, fake COD orders, disposable emails, and checkout floods.

== Description ==

Spam bot checkouts, card-testing fraud attacks, and fake Cash on Delivery (COD) orders waste merchant capital, trigger payment gateway chargeback fines, exhaust inventory stock, and distort sales analytics.

**WPFIA Fake Order & Spam Checkout Shield** delivers a 5-layer automated defense system directly onto your WooCommerce checkout—with zero performance impact and zero customer friction.

### 🛡️ 5 Layers of Automated Checkout Protection

1. **Invisible Honeypot Bot Trap:**
   Injects an invisible form trap. Headless automated bots automatically fill it out and are immediately neutralized.

2. **Submission Velocity Time-Gate:**
   Bots submit checkout forms in fractions of a second (< 800ms). Our HMAC-signed cryptographic time-gate ensures orders cannot be submitted instantly by headless scrapers.

3. **Offline Disposable Email Blocker:**
   Screens billing emails against an offline, zero-latency repository of throwaway burner email domains (Mailinator, GuerrillaMail, 10MinuteMail, Temp-Mail, etc.), plus allows custom store-specific blacklisted domains.

4. **Bogus Phone Number Detector (Fake COD Shield):**
   Prevents bogus delivery numbers (e.g., repeating digits like `0000000000`, `1111111111`, `1234567890`, or invalid lengths) to stop fraudulent Cash on Delivery shipments before dispatch.

5. **IP Order Velocity Rate Limiting:**
   Limits order frequency per IP address (default: 5 orders/hour) to defeat stolen card testing scripts and checkout denial-of-inventory floods.

### ⚡ 100% Lifetime Free & Privacy Focused
* **Zero External API Calls:** All checks run locally on your server.
* **No Customer Data Tracking:** Zero cookies, zero third-party telemetry.
* **HPOS Ready:** Fully compliant with WooCommerce High-Performance Order Storage (HPOS).
* **Zero Bloat / No Upsells:** 100% Free forever.

== Installation ==

1. Upload the `wpfia-fake-order-shield` folder to your `/wp-content/plugins/` directory, or install directly through the WordPress plugins screen.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to **WooCommerce > Fake Order Shield** to customize your security thresholds and monitor live threat counters.

== Frequently Asked Questions ==

= Does this slow down my checkout page? =
Not at all. All verification algorithms (Honeypot, HMAC Time-Gate, Phone Regex, O(1) Email Hash) execute in under 1 millisecond locally on your server.

= Will real customers be affected? =
No. Human customers take normal time to review their checkout fields and use valid phone numbers and real email addresses.

= Is this compatible with WooCommerce HPOS? =
Yes! The plugin fully declares compatibility with WooCommerce Custom Order Tables (HPOS) and block-based carts.

== Screenshots ==

1. Threat neutralization metrics overview.
2. Security defense modules and rate limiting configuration.

== Changelog ==

= 1.0.0 =
* Initial public release on WordPress.org.
* Invisible honeypot trap.
* HMAC cryptographic time-gate velocity check.
* Built-in offline disposable email repository.
* Bogus phone regex analyzer.
* IP checkout rate limiter and live threat metrics counters.
