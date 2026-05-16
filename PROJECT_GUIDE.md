# Rymo Watches Final Project Guide

## 15 input screens covered

1. `login.php` - customer login
2. `signup.php` - customer registration
3. `adminlogin.php` - admin login
4. `staff_login.php` - staff login
5. `buy.php` - order checkout form
6. `contact.php` - contact form
7. `profile.php` - profile update form
8. `feedback.php` - customer feedback form
9. `staff_register.php` - staff registration form
10. `supplier_form.php` - supplier registration form
11. `product_form.php` - product master form
12. `inventory_form.php` - inventory entry form
13. `return_request.php` - return request form
14. `service_request.php` - service request form
15. `newsletter_form.php` - newsletter subscription form
16. `staff_leave.php` - leave request form
17. `vendor_registration.php` - vendor registration form
18. `complaint_form.php` - customer complaint form

## Major management screens

- `admin_dashboard.php` - main admin overview dashboard
- `management_hub.php` - central module dashboard
- `product_list.php` - manage catalog products and website visibility
- `website_products.php` - public catalog for admin-added products
- `admin.php` - order management with filters and status update
- `advanced_report.php` - monthly, yearly, and quarterly reports
- `admin_feedback.php` - feedback analytics
- `staff_directory.php` - registered staff table
- `admin_contact.php` - contact messages

## File placement

- Main PHP files remain in project root.
- Shared helper file: `portal_helpers.php`
- Shared new CSS: `css/portal.css`
- Existing images remain in `images/`
- SQL setup is in `rymowatch_updated.sql`

## Database steps

1. Open `phpMyAdmin`.
2. Select database `rymowatch`.
3. Open SQL tab.
4. Run the full contents of `rymowatch_updated.sql`.

This now creates the additional tables for:
- `feedback`
- `staff_accounts`
- `suppliers`
- `catalog_products`
- `inventory_entries`
- `return_requests`
- `service_requests`
- `newsletter_subscribers`
- `staff_leave_requests`
- `vendor_partners`
- `customer_complaints`

## Suggested viva explanation

You can now present this as a complete e-commerce and management application containing:
- customer module
- admin module
- staff module
- website-driven product publishing
- supplier management
- product and inventory management
- service and return management
- complaint and feedback management
- newsletter and vendor management
- real-time analytics and reports

## Validation coverage

The new screens include:
- required-field validation
- email validation
- phone validation
- numeric range validation
- password length validation
- date range validation
- duplicate staff email/code checks

## Next local step after code changes

Run `rymowatch_updated.sql` first, then test each form one by one from `management_hub.php`.
