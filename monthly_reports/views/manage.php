<h1>Manage Monthly Reports</h1>
<?php
flashdata();
echo '<p>'.anchor('monthly_reports/create', 'Create New Monthly Report Record', array('class' => 'button alt')).'</p>';
if (empty($rows)) {
    echo '<p>There are currently no records to display.</p>';
    return;
}
echo Modules::run('pagination/display', $pagination_data);
?>

<table class="records-table">
    <thead>
        <tr>
            <th colspan="6">
                <div>
                    <div>&nbsp;</div>
                    <div>Records Per Page: <?php
                    $dropdown_attr['onchange'] = 'setPerPage()';
                    echo form_dropdown('per_page', $per_page_options, $selected_per_page, $dropdown_attr); 
                    ?></div>

                </div>                    
            </th>
        </tr>
        <tr>
            <th>Employee Name</th>
            <th>Department</th>
            <th>Report Month</th>
            <th>Summary</th>
            <th style="width: 20px;">Action</th>            
        </tr>
    </thead>
    <tbody>
        <?php 
        $attr['class'] = 'button alt';
        foreach($rows as $row) { ?>
        <tr>
            <td><?= out($row->employee_name) ?></td>
            <td><?= out($row->department) ?></td>
            <td><?= out($row->report_month_formatted) ?></td>
            <td><?= out($row->report_summary_truncated) ?></td>
            <td><?= anchor('monthly_reports/show/'.$row->id, 'View', $attr) ?></td>        
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<?php 
if(count($rows)>9) {
    unset($pagination_data['include_showing_statement']);
    echo Modules::run('pagination/display', $pagination_data);
}
?>

<script>
function setPerPage() {
    const selectedIndex = document.querySelector('select[name="per_page"]').value;
    window.location.href = '<?= BASE_URL ?>monthly_reports/set_per_page/' + selectedIndex;
}
</script>
