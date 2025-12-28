# Monthly Reports Module - Quick Installation Guide

## What You're Getting

A complete Trongate v2 CRUD module for tracking employee monthly reports with:
- Native HTML5 month picker (zero JavaScript!)
- **No conversion needed** - YYYY-MM format throughout
- Full create, read, update, delete operations
- Pagination with configurable records per page
- Beautiful month formatting
- Clean, well-documented code

## Installation Steps

### 1. Import the Database Table
```sql
-- Run monthly_reports.sql in your MySQL database
-- This creates the 'monthly_reports' table with VARCHAR(7) for report_month
```

### 2. Copy the Module
```bash
# Copy the 'monthly_reports' folder into your Trongate modules directory
your-project/
  modules/
    monthly_reports/          ← Copy this entire folder here
      Monthly_reports.php
      Monthly_reports_model.php
      views/
```

### 3. Access the Module
```
https://your-domain.com/monthly_reports
```

## File Structure Delivered

```
monthly_reports/
├── Monthly_reports.php         # Controller with full CRUD operations
├── Monthly_reports_model.php   # Model with month formatting methods
└── views/
    ├── create.php              # Create/Edit form
    ├── manage.php              # List view with pagination
    ├── show.php                # Detail view
    ├── delete_conf.php         # Delete confirmation
    └── not_found.php           # 404 page

monthly_reports.sql              # Database table schema
README_monthly.md                # Full documentation
```

## Database Schema

```sql
CREATE TABLE `monthly_reports` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `employee_name` VARCHAR(50),
  `department` VARCHAR(50),
  `report_month` VARCHAR(7),    ← Stores month in YYYY-MM format
  `report_summary` TEXT,
  PRIMARY KEY (`id`),
  KEY `idx_report_month` (`report_month`)
);
```

## Key Features

✅ **Native HTML5 Month Input** - Uses `form_month()` helper
✅ **ISO 8601 YYYY-MM Format** - Simple and consistent
✅ **No Conversion Needed** - Same format everywhere!
✅ **VARCHAR Storage** - Human-readable in database
✅ **Beautiful Display** - Formats as "December 2025"
✅ **Full Validation** - Including month validation
✅ **Pagination** - 10, 20, 50, or 100 records per page
✅ **Security** - CSRF protection, admin authentication
✅ **Form Repopulation** - Shows entered data on validation errors

## URL Routes

- `/monthly_reports` or `/monthly_reports/manage` - List all reports
- `/monthly_reports/create` - Create new report
- `/monthly_reports/show/{id}` - View report details
- `/monthly_reports/create/{id}` - Edit existing report
- `/monthly_reports/delete_conf/{id}` - Delete confirmation

## Code Highlights

### The Month Input Field
```php
echo form_month('report_month', $report_month);
// Renders: <input type="month" name="report_month">
// Always submits in YYYY-MM format
```

### Month Validation
```php
$this->validation->set_rules('report_month', 'report month', 'required|valid_month');
// Validates ISO 8601 format (YYYY-MM)
```

### Storing Month Data (No Conversion!)
```php
// Form submits: "2025-12"
$report_month = post('report_month', true);

// Store directly - no conversion needed!
$data['report_month'] = $report_month;

// Save to database
$this->db->insert($data, 'monthly_reports');
```

### Loading Month Data (No Conversion!)
```php
// Database has: "2025-12"
$record = $this->db->get_where($update_id, 'monthly_reports');

// Use directly - no conversion needed!
$data['report_month'] = $record->report_month;

// Pass to form
$this->view('report_form', $data);
```

### Month Display Formatting
```php
// Model method formats for display
$parts = explode('-', $data['report_month']);
$date = new DateTime($parts[0] . '-' . $parts[1] . '-01');
$data['report_month_formatted'] = $date->format('F Y');
// Result: "December 2025"
```

## The Simplicity of Month Inputs

Unlike datetime-local inputs, month inputs are **beautifully simple**:

**No conversion needed anywhere:**
```
Form Input:    2025-12
      ↓
Database:      2025-12  (stored as VARCHAR)
      ↓
Form Edit:     2025-12  (loaded directly)
      ↓
Display:       "December 2025"  (just format!)
```

**This is the key advantage:**
- Form submits YYYY-MM
- Database stores YYYY-MM
- Form loads YYYY-MM
- Only display formatting is needed!

## Month Format: YYYY-MM

- Always use zero-padded months
- January = `01`, December = `12`
- Year comes first: `2025-12`, not `12-2025`
- Separator is always a hyphen: `2025-12`, not `2025/12`

## Database Storage: VARCHAR(7)

**Why VARCHAR(7)?**
- Stores exactly what the form submits
- Human-readable in database
- No conversion needed
- Easy string queries
- No arbitrary day component

**Example queries:**
```sql
-- Get all reports for December 2025
WHERE report_month = '2025-12'

-- Get all reports for 2025
WHERE report_month LIKE '2025-%'

-- Order by month (most recent first)
ORDER BY report_month DESC
```

## Important Module Structure Note

**Trongate v2 eliminates the `controllers/` and `models/` subdirectories!**

✅ **Correct structure:**
```
monthly_reports/
  Monthly_reports.php        ← Controller in module root
  Monthly_reports_model.php  ← Model in module root
  views/
```

❌ **Old v1 structure (DO NOT USE):**
```
monthly_reports/
  controllers/
    Monthly_reports.php
  models/
    Monthly_reports_model.php
  views/
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
- Ensure month is zero-padded (01-12)

**Database errors?**
- Verify report_month column is VARCHAR(7)
- Check that no conversion code was added
- Ensure months are zero-padded

## Setting Current Month as Default

To default the month input to the current month:

```php
// Controller
if ($data['report_month'] === '') {
    $data['report_month'] = date('Y-m'); // Current month
}
```

## Need Help?

- Full documentation in README_monthly.md
- Visit [trongate.io/documentation](https://trongate.io/documentation)
- All code follows Trongate v2 best practices
- See also: [Events Module](https://github.com/trongate/Trongate-v2-Events-Module) and [Friends Module](https://github.com/trongate/Trongate-v2-Friends-Module)

Enjoy tracking monthly reports with Trongate! 📊📅
