# Trongate v2 Monthly Reports Module

A complete **monthly_reports** module for **Trongate v2** that demonstrates a full-featured **CRUD** (Create, Read, Update, Delete) application for tracking employee monthly reports.

This repository provides a ready-to-use example of building a monthly reporting system using the Trongate PHP framework (version 2). It includes pagination, form validation, secure admin access, **native HTML5 month input handling**, and clean separation of concerns.

## Features

- ✅ Paginated report listing with selectable records per page (10, 20, 50, 100)
- ✅ Create new monthly report records with month/year selection
- ✅ View detailed report information with formatted month display
- ✅ Update existing report records (with form repopulation on validation errors)
- ✅ Safe delete with confirmation page
- ✅ **Native HTML5 month picker** for month/year selection (zero JavaScript required)
- ✅ **Simple month handling** using ISO 8601 YYYY-MM format
- ✅ **No conversion needed** - same format for form, database, and display
- ✅ **VARCHAR storage** for month values (human-readable in database)
- ✅ Beautiful month formatting for display (e.g., "December 2025")
- ✅ Form validation including month validation
- ✅ CSRF protection on all forms
- ✅ Admin security checks on all actions
- ✅ Responsive back navigation and flash messages
- ✅ Clean, well-documented code following Trongate v2 best practices

## Database Table

The `monthly_reports.sql` file creates a `monthly_reports` table with the following columns:
- `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
- `employee_name` (VARCHAR 50)
- `department` (VARCHAR 50)
- `report_month` (VARCHAR 7) - stores month in YYYY-MM format
- `report_summary` (TEXT)

## Prerequisites

- Trongate v2 framework (latest version recommended)
- PHP 8.0+
- MySQL/MariaDB database
- Web server with URL rewriting enabled

Visit the official site: [trongate.io](https://trongate.io)

## Installation

1. **Install Trongate v2** (if not already done):
   - Download or clone the official framework from GitHub: [https://github.com/trongate/trongate-framework](https://github.com/trongate/trongate-framework)
   - For full documentation and guides, visit: [trongate.io/documentation](https://trongate.io/documentation)

2. **Add the module**:
   - Copy the `monthly_reports` folder into your project's `modules` directory:
     ```
     modules/
       monthly_reports/
         Monthly_reports.php
         Monthly_reports_model.php
         views/
           create.php
           manage.php
           show.php
           delete_conf.php
           not_found.php
     ```

3. **Create the database table**:
   - Import `monthly_reports.sql` into your database (e.g., via phpMyAdmin or command line).

4. **Access the module**:
   - Log in to your Trongate admin panel.
   - Visit: `https://your-domain.com/monthly_reports` or `https://your-domain.com/monthly_reports/manage`

## URL Routes

- List reports: `/monthly_reports` or `/monthly_reports/manage` (with pagination: `/monthly_reports/manage/{page}`)
- Create report: `/monthly_reports/create`
- View report: `/monthly_reports/show/{id}`
- Edit report: `/monthly_reports/create/{id}`
- Delete confirmation: `/monthly_reports/delete_conf/{id}`
- Set records per page: `/monthly_reports/set_per_page/{option_index}`

## Module Structure

```
monthly_reports/
├── Monthly_reports.php         # Main controller with CRUD operations
├── Monthly_reports_model.php   # Data layer with month formatting methods
└── views/
    ├── create.php              # Create/Edit form
    ├── manage.php              # Paginated list view
    ├── show.php                # Detail view
    ├── delete_conf.php         # Delete confirmation
    └── not_found.php           # 404 error page
```

## Key Features Explained

### Native HTML5 Month Input

This module uses the **native HTML5 month picker** via Trongate's `form_month()` helper:

```php
echo form_month('report_month', $report_month);
```

**Benefits:**
- ✅ Zero JavaScript required
- ✅ Works on all modern browsers (Chrome, Firefox, Safari, Edge)
- ✅ Native mobile keyboards and pickers
- ✅ Accessible by default
- ✅ Always submits in ISO 8601 format (YYYY-MM)
- ✅ Browser displays in user's locale format automatically

### Simple Month Handling

Unlike datetime-local inputs, month inputs require **no conversion**:

**Form Input:** `2025-12`
↓ (no conversion needed!)
**Database Storage:** `2025-12`
↓ (simple formatting)
**Display:** "December 2025"

### Month Storage

Months are stored in a `VARCHAR(7)` column in YYYY-MM format:
- Human-readable in database
- Easy to query by year or month
- No conversion between form and database
- Works with standard SQL string functions

### Month Display Formatting

The model includes a `prepare_for_display()` method that formats months for human-readable display:

```php
// Database: 2025-12
// Display: December 2025
// Short: Dec 2025
// Numeric: 12/2025
```

### Validation Rules

The module demonstrates proper validation including:
- Required fields
- String length limits
- **Month format validation** using `valid_month` rule

```php
$this->validation->set_rules('report_month', 'report month', 'required|valid_month');
```

## Development Patterns Demonstrated

### 1. The Three-Method Form Pattern
- `create()` - Display form
- `submit()` - Process submission
- `show()` - Display success/result

### 2. Create/Update Pattern (No Conversion Needed!)
- Single form for both creating and editing
- Month value works in both directions without conversion
- Database value = Form value = YYYY-MM format
- Proper segment type-casting: `segment(3, 'int')`

### 3. POST-Redirect-GET Pattern
- Prevents duplicate submissions on refresh
- Uses `set_flashdata()` for success messages
- Clean URL after form submission

### 4. Data Formatting (Model Methods)
- `prepare_for_display()` - Formats month for human-readable output
- Multiple format options (full, short, numeric)
- Clear separation between storage format and display format

### 5. Pagination Implementation
- Session-based per-page selection
- Proper offset calculation
- Clean pagination helper integration

## Code Examples

### The Month Input Field
```php
echo form_month('report_month', $report_month);
// Renders: <input type="month" name="report_month">
// Always submits in YYYY-MM format
```

### Month Validation
```php
$this->validation->set_rules('report_month', 'report month', 'required|valid_month');
// Validates ISO 8601 YYYY-MM format
```

### Storing Month Data (No Conversion!)
```php
// Get from form (YYYY-MM format)
$report_month = post('report_month', true); // "2025-12"

// Store directly - no conversion needed!
$data['report_month'] = $report_month;

// Save to database
$this->db->insert($data, 'monthly_reports');
```

### Loading Month Data for Editing (No Conversion!)
```php
// Get from database (already in YYYY-MM format)
$record = $this->db->get_where($update_id, 'monthly_reports');
// $record->report_month = "2025-12"

// Use directly in form - no conversion needed!
$data['report_month'] = $record->report_month;

// Pass to view
$this->view('report_form', $data);
```

### Month Display Formatting
```php
// Model method formats for display
$parts = explode('-', $data['report_month']); // ["2025", "12"]
$date = new DateTime($parts[0] . '-' . $parts[1] . '-01');
$data['report_month_formatted'] = $date->format('F Y');
// Result: "December 2025"
```

## Important Month Concepts

### ISO 8601 YYYY-MM Format
- This is what HTML5 month inputs use
- Example: `2025-12`
- Zero-padded months: `01` through `12`
- No day component (that's the whole point!)

### Database Storage
- Use `VARCHAR(7)` column type
- Stores exactly as submitted: `2025-12`
- Human-readable in database queries
- Easy to filter by year or month

### No Conversion Needed!
Unlike datetime-local inputs, month inputs are refreshingly simple:
```php
// Form submits: "2025-12"
// Store in DB: "2025-12" (no conversion!)
// Load from DB: "2025-12" (no conversion!)
// Display: "December 2025" (just format!)
```

## Customization

### Changing Month Display Format

Edit the `prepare_for_display()` method in `Monthly_reports_model.php`:

```php
// Current format: "December 2025"
$data['report_month_formatted'] = $date->format('F Y');

// Short format: "Dec 2025"
$data['report_month_formatted'] = $date->format('M Y');

// Numeric format: "12/2025"
$data['report_month_formatted'] = $date->format('m/Y');

// Year only: "2025"
$data['report_month_formatted'] = $date->format('Y');
```

### Setting Current Month as Default

Add this to your controller's `create()` method:

```php
if ($data['report_month'] === '') {
    $data['report_month'] = date('Y-m'); // Current month
}
```

### Adding Month Range Constraints

Restrict month selection to specific range:

```php
// Controller
$data['min_month'] = '2024-01'; // Can't select before January 2024
$data['max_month'] = date('Y-m'); // Can't select future months

// View
$attrs = ['min' => $min_month, 'max' => $max_month];
echo form_month('report_month', $report_month, $attrs);
```

### Making Report Month Optional

Change the validation rule in `Monthly_reports.php`:

```php
// From:
$this->validation->set_rules('report_month', 'report month', 'required|valid_month');

// To:
$this->validation->set_rules('report_month', 'report month', 'valid_month');
```

## Querying by Month in Database

The VARCHAR storage makes querying straightforward:

```php
// Get all reports for a specific month
$sql = "SELECT * FROM monthly_reports WHERE report_month = '2025-12'";

// Get all reports for a specific year
$sql = "SELECT * FROM monthly_reports WHERE report_month LIKE '2025-%'";

// Get reports between two months
$sql = "SELECT * FROM monthly_reports 
        WHERE report_month >= '2025-01' 
        AND report_month <= '2025-12'";

// Order by month (descending - most recent first)
$sql = "SELECT * FROM monthly_reports ORDER BY report_month DESC";
```

## Troubleshooting

**Module not showing?**
- Ensure the `monthly_reports` folder is in `modules/` directory
- Verify Monthly_reports.php and Monthly_reports_model.php are in monthly_reports/ root
- Check folder permissions (755 for directories, 644 for files)
- Verify you're logged into the admin panel

**Month picker not appearing?**
- HTML5 month inputs work in all modern browsers
- Very old browsers fall back to text input
- Users can type YYYY-MM format manually

**Validation errors?**
- Check that all required fields are filled
- Report month must be in YYYY-MM format
- Ensure month is zero-padded (01-12, not 1-12)

**Database errors?**
- Verify report_month column is VARCHAR(7)
- Confirm no conversion code is interfering
- Check that months are properly zero-padded

## Browser Compatibility

The native HTML5 month input is supported by:
- ✅ Chrome (all versions)
- ✅ Firefox (all versions)
- ✅ Safari 14.1+
- ✅ Edge (all versions)
- ✅ Mobile browsers (iOS Safari, Android Chrome)

**Note:** Very old browsers (IE 11 and earlier) will render month inputs as text fields. Users can still type months manually in YYYY-MM format, and validation will ensure correctness.

## Security Features

- ✅ CSRF token validation on all forms
- ✅ Admin authentication checks on all methods
- ✅ SQL injection prevention via prepared statements
- ✅ XSS prevention via `out()` function in views
- ✅ Month format validation
- ✅ Delete confirmation to prevent accidental deletion

## Why VARCHAR for Month Storage?

Some developers might wonder why we use VARCHAR(7) instead of DATE. Here's why:

**VARCHAR(7) Advantages:**
- ✅ Stores exactly what the form submits (YYYY-MM)
- ✅ No conversion needed in either direction
- ✅ Human-readable in database queries
- ✅ Simple to filter by year or month
- ✅ No "day" component to worry about
- ✅ Direct string comparison works perfectly

**DATE Column Issues:**
- ❌ Requires arbitrary day (usually 01)
- ❌ More complex queries (extract year/month)
- ❌ Confusing when day is always 01
- ❌ Conversion needed for form display

## Contributing

Issues, suggestions, and pull requests are welcome! Feel free to fork and improve this example module.

## License

Released under the same open-source license as the Trongate framework (MIT-style - permissive and free to use).

## Learn More

- [Trongate Framework](https://trongate.io)
- [Trongate Documentation](https://trongate.io/documentation)

Happy monthly reporting with Trongate! 📊📅
