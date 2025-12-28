<?php
/**
 * Monthly_reports Model - Handles data operations for monthly report records
 * 
 * Demonstrates proper month input handling using ISO 8601 YYYY-MM format.
 * No conversion needed - form submits in same format as database storage.
 */
class Monthly_reports_model extends Model {
    
    /**
     * Fetch paginated monthly report records from database
     * 
     * Retrieves reports with proper limit and offset for pagination.
     * This is the primary method for listing reports in manage view.
     * 
     * @param int $limit Maximum number of records to return
     * @param int $offset Number of records to skip (for pagination)
     * @return array Array of report record objects
     */
    public function fetch_records(int $limit, int $offset): array {
        return $this->db->get('id', 'monthly_reports', $limit, $offset);
    }
    
    /**
     * Get form-ready data based on current context
     * 
     * Determines whether to return existing record data (for editing)
     * or POST data/default values (for new forms or validation errors).
     * This is the main method called by controller's create() method.
     * 
     * @param int $update_id Record ID to edit, or 0 for new records
     * @return array Form data ready for view display
     * @example get_form_data(5) returns report #5 data for editing
     * @example get_form_data(0) returns POST data or defaults for new report
     */
    public function get_form_data(int $update_id = 0): array {
        if ($update_id > 0 && REQUEST_TYPE === 'GET') {
            return $this->get_data_for_edit($update_id);
        } else {
            return $this->get_data_from_post_or_defaults();
        }
    }

    /**
     * Get existing record data for editing
     * 
     * Fetches a single record from database and prepares it for form display.
     * Month is already in YYYY-MM format from database, perfect for form_month().
     * 
     * @param int $update_id The record ID to fetch
     * @return array Record data ready for form
     * @throws No explicit throws, but returns empty array if record not found
     */
    public function get_data_for_edit(int $update_id): array {
        $record = $this->db->get_where($update_id, 'monthly_reports');
        
        if (empty($record)) {
            return [];
        }
        
        return (array) $record;
    }
    
    /**
     * Get form data from POST or use defaults
     * 
     * Used for new forms or when redisplaying form after validation errors.
     * Returns empty strings as defaults for a clean new form.
     * 
     * @return array Form data with proper types for view
     */
    private function get_data_from_post_or_defaults(): array {
        return [
            'employee_name' => post('employee_name', true) ?? '',
            'department' => post('department', true) ?? '',
            'report_month' => post('report_month', true) ?? '',
            'report_summary' => post('report_summary') ?? ''
        ];
    }
    
    /**
     * Prepare POST data for database storage
     * 
     * Converts form submission data to database-ready format.
     * Month comes from form_month() in YYYY-MM format, which is perfect for VARCHAR storage.
     * 
     * @return array Database-ready data with proper types
     */
    public function get_post_data_for_database(): array {
        return [
            'employee_name' => post('employee_name', true),
            'department' => post('department', true),
            'report_month' => post('report_month', true), // Already in YYYY-MM format
            'report_summary' => trim(post('report_summary'))
        ];
    }
    
    /**
     * Prepare raw database data for display in views
     * 
     * Adds formatted versions of fields while preserving raw data.
     * This is where you add display-friendly versions of data.
     * 
     * @param array $data Raw data from database
     * @return array Enhanced data with formatted fields
     * @example Converts report_month='2025-12' to report_month_formatted='December 2025'
     */
    public function prepare_for_display(array $data): array {
        // Format report month for display if present
        if (isset($data['report_month']) && $data['report_month'] !== null && $data['report_month'] !== '') {
            try {
                // Parse YYYY-MM format
                $parts = explode('-', $data['report_month']);
                if (count($parts) === 2) {
                    $year = $parts[0];
                    $month = $parts[1];
                    
                    // Create DateTime object (using first day of month for date functions)
                    $date = new DateTime($year . '-' . $month . '-01');
                    
                    // Full format: "December 2025"
                    $data['report_month_formatted'] = $date->format('F Y');
                    
                    // Short format: "Dec 2025"
                    $data['report_month_short'] = $date->format('M Y');
                    
                    // Numeric format: "12/2025"
                    $data['report_month_numeric'] = $date->format('m/Y');
                } else {
                    $data['report_month_formatted'] = 'Invalid Month';
                    $data['report_month_short'] = 'N/A';
                    $data['report_month_numeric'] = 'N/A';
                }
            } catch (Exception $e) {
                $data['report_month_formatted'] = 'Invalid Month';
                $data['report_month_short'] = 'N/A';
                $data['report_month_numeric'] = 'N/A';
            }
        } else {
            $data['report_month_formatted'] = 'Not specified';
            $data['report_month_short'] = 'N/A';
            $data['report_month_numeric'] = 'N/A';
        }
        
        // Truncate summary for list views
        if (isset($data['report_summary']) && strlen($data['report_summary']) > 100) {
            $data['report_summary_truncated'] = substr($data['report_summary'], 0, 100) . '...';
        } else {
            $data['report_summary_truncated'] = $data['report_summary'] ?? '';
        }
        
        return $data;
    }
    
    /**
     * Prepare multiple records for display in list views
     * 
     * Processes an array of records through prepare_for_display().
     * Maintains object structure for consistency with Trongate patterns.
     * 
     * @param array $rows Array of record objects from database
     * @return array Array of objects with formatted display fields
     */
    public function prepare_records_for_display(array $rows): array {
        $prepared = [];
        foreach ($rows as $row) {
            $row_array = (array) $row;
            $prepared[] = (object) $this->prepare_for_display($row_array);
        }
        return $prepared;
    }
}
