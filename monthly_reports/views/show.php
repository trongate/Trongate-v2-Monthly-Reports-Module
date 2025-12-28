<h1><?= $headline ?></h1>
<?= flashdata() ?>
<div class="card">
    <div class="card-heading">
        Monthly Report Details
    </div>
    <div class="card-body">
        <div class="text-right mb-3">
            <?= anchor($back_url, 'Back', array('class' => 'button alt')) ?>
            <?= anchor(BASE_URL.'monthly_reports/create/'.$update_id, 'Edit', array('class' => 'button')) ?>
            <?= anchor('monthly_reports/delete_conf/'.$update_id, 'Delete',  array('class' => 'button danger')) ?>
        </div>
        
        <div class="detail-grid">
            <div class="detail-row">
                <div class="detail-label">Employee Name</div>
                <div class="detail-value"><?= out($employee_name) ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Department</div>
                <div class="detail-value"><?= out($department) ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Report Month</div>
                <div class="detail-value"><?= out($report_month_formatted) ?></div>
            </div>
            <div class="detail-block">
                <div class="detail-label">Report Summary</div>
                <div class="detail-content"><?= nl2br(out($report_summary)) ?></div>
            </div>
        </div>
    </div>
</div>
