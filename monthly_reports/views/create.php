<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Monthly Report Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        
        echo form_label('Employee Name');
        echo form_input('employee_name', $employee_name, ["placeholder" => "Enter Employee Name"]);
        
        echo form_label('Department');
        echo form_input('department', $department, ["placeholder" => "Enter Department"]);
        
        echo form_label('Report Month');
        echo form_month('report_month', $report_month);
        
        echo form_label('Report Summary');
        echo form_textarea('report_summary', $report_summary, ["placeholder" => "Enter Report Summary", "rows" => 6]);

        echo '<div class="text-center">';
        echo anchor($cancel_url, 'Cancel', ['class' => 'button alt']);
        echo form_submit('submit', 'Submit');
        echo form_close();
        echo '</div>';
        ?>
    </div>
</div>
