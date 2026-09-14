<?php
// Included by each report page. Expects $report_type (matches export.php "type" param)
// and $date_from / $date_to already resolved.
?>
<div class="card shadow-sm mb-3 no-print">
  <div class="card-body">
    <form method="GET" class="row g-2 align-items-end">
      <input type="hidden" name="range" id="rangeField" value="<?php echo e($_GET['range'] ?? 'custom'); ?>">
      <div class="col-md-3">
        <label class="form-label small mb-1">From</label>
        <input type="date" name="date_from" class="form-control" value="<?php echo e($date_from); ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label small mb-1">To</label>
        <input type="date" name="date_to" class="form-control" value="<?php echo e($date_to); ?>">
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-outline-danger w-100">Apply Range</button>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <a href="?date_from=<?php echo date('Y-m-d'); ?>&date_to=<?php echo date('Y-m-d'); ?>" class="btn btn-sm btn-outline-secondary">Daily</a>
        <a href="?date_from=<?php echo date('Y-m-01'); ?>&date_to=<?php echo date('Y-m-t'); ?>" class="btn btn-sm btn-outline-secondary">Monthly</a>
        <a href="?date_from=<?php echo date('Y-01-01'); ?>&date_to=<?php echo date('Y-12-31'); ?>" class="btn btn-sm btn-outline-secondary">Yearly</a>
      </div>
    </form>
    <div class="mt-3 d-flex gap-2">
      <a href="export_excel.php?type=<?php echo $report_type; ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>" class="btn btn-sm btn-success"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
      <a href="export_pdf.php?type=<?php echo $report_type; ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>" class="btn btn-sm btn-danger"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
      <button onclick="window.print()" class="btn btn-sm btn-outline-dark"><i class="bi bi-printer"></i> Print</button>
    </div>
  </div>
</div>
