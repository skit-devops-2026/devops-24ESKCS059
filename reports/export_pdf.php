<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$type = clean($conn, $_GET['type'] ?? '');
$date_from = clean($conn, $_GET['date_from'] ?? date('Y-m-01'));
$date_to = clean($conn, $_GET['date_to'] ?? date('Y-m-d'));

// Map report type -> [SQL, headers, row-field list, title]
$map = [
    'donor' => [
        "SELECT donor_id, name, gender, age, blood_group, mobile, email, city, availability_status FROM donors WHERE created_at BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59' ORDER BY name",
        ['ID','Name','Gender','Age','Blood Group','Mobile','Email','City','Availability'],
        ['donor_id','name','gender','age','blood_group','mobile','email','city','availability_status'],
        'Donor Report'
    ],
    'stock' => [
        "SELECT blood_group, units_available, low_stock_threshold, last_updated FROM blood_stock ORDER BY blood_group",
        ['Blood Group','Units Available','Low Stock Threshold','Last Updated'],
        ['blood_group','units_available','low_stock_threshold','last_updated'],
        'Blood Stock Report'
    ],
    'request' => [
        "SELECT br.request_id, br.patient_name, br.blood_group, br.required_units, h.hospital_name, br.emergency_level, br.request_date, br.status
         FROM blood_requests br LEFT JOIN hospitals h ON h.hospital_id = br.hospital_id
         WHERE br.request_date BETWEEN '$date_from' AND '$date_to' ORDER BY br.request_date DESC",
        ['ID','Patient','Blood Group','Units','Hospital','Emergency','Date','Status'],
        ['request_id','patient_name','blood_group','required_units','hospital_name','emergency_level','request_date','status'],
        'Blood Request Report'
    ],
    'hospital' => [
        "SELECT hospital_id, hospital_name, registration_number, contact_person, mobile, city FROM hospitals WHERE created_at BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59' ORDER BY hospital_name",
        ['ID','Name','Reg. No.','Contact Person','Mobile','City'],
        ['hospital_id','hospital_name','registration_number','contact_person','mobile','city'],
        'Hospital Report'
    ],
    'collection' => [
        "SELECT bc.collection_id, d.name, bc.blood_group, bc.units_collected, bc.collection_date, bc.expiry_date, bc.staff_name
         FROM blood_collection bc JOIN donors d ON d.donor_id = bc.donor_id
         WHERE bc.collection_date BETWEEN '$date_from' AND '$date_to' ORDER BY bc.collection_date DESC",
        ['ID','Donor','Blood Group','Units','Collection Date','Expiry','Staff'],
        ['collection_id','name','blood_group','units_collected','collection_date','expiry_date','staff_name'],
        'Blood Collection Report'
    ],
    'issue' => [
        "SELECT bi.issue_id, bi.patient_name, bi.blood_group, bi.units_issued, h.hospital_name, bi.issue_date, bi.approved_by
         FROM blood_issue bi LEFT JOIN hospitals h ON h.hospital_id = bi.hospital_id
         WHERE bi.issue_date BETWEEN '$date_from' AND '$date_to' ORDER BY bi.issue_date DESC",
        ['ID','Patient','Blood Group','Units','Hospital','Date','Approved By'],
        ['issue_id','patient_name','blood_group','units_issued','hospital_name','issue_date','approved_by'],
        'Blood Issue Report'
    ],
];

if (!isset($map[$type])) { die('Unknown report type.'); }

[$sql, $headers, $fields, $title] = $map[$type];
$result = mysqli_query($conn, $sql);

// Log this export in the reports audit table
mysqli_query($conn, "INSERT INTO reports (report_type, generated_by, date_from, date_to) VALUES ('$type', '" . clean($conn, $_SESSION['admin_name']) . "', '$date_from', '$date_to')");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo e($title); ?></title>
<style>
    body { font-family: Arial, Helvetica, sans-serif; color: #222; margin: 30px; }
    .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #dc3545; padding-bottom: 10px; margin-bottom: 20px; }
    .header h1 { color: #dc3545; font-size: 22px; margin: 0; }
    .header .meta { text-align: right; font-size: 12px; color: #666; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 6px 8px; font-size: 12px; text-align: left; }
    th { background: #f5f5f5; }
    .footer { margin-top: 20px; font-size: 11px; color: #888; text-align: center; }
    .print-bar { text-align: center; margin-bottom: 20px; }
    .print-bar button { padding: 8px 20px; background: #dc3545; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
    @media print { .print-bar { display: none; } }
</style>
</head>
<body>

<div class="print-bar">
  <button onclick="window.print()">Save as PDF / Print</button>
  <p style="font-size:12px;color:#666;">Use your browser's "Print" dialog and choose "Save as PDF" as the destination.</p>
</div>

<div class="header">
  <h1><i>&#128167;</i> Blood Bank Management System<br><span style="font-size:14px;color:#333;"><?php echo e($title); ?></span></h1>
  <div class="meta">
    Generated: <?php echo date('Y-m-d H:i'); ?><br>
    Range: <?php echo e($date_from); ?> to <?php echo e($date_to); ?><br>
    By: <?php echo e($_SESSION['admin_name']); ?>
  </div>
</div>

<table>
  <thead>
    <tr><?php foreach ($headers as $h) echo '<th>' . e($h) . '</th>'; ?></tr>
  </thead>
  <tbody>
    <?php $count = 0; while ($row = mysqli_fetch_assoc($result)): $count++; ?>
      <tr>
        <?php foreach ($fields as $f): ?>
          <td><?php echo e($row[$f] ?? '—'); ?></td>
        <?php endforeach; ?>
      </tr>
    <?php endwhile; ?>
    <?php if ($count === 0): ?>
      <tr><td colspan="<?php echo count($headers); ?>" style="text-align:center;color:#888;">No records found for this range.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<div class="footer">
  Blood Bank Management System &mdash; <?php echo $count; ?> record(s) &mdash; Confidential
</div>

</body>
</html>
<?php
// NOTE: This produces a print-ready HTML page (File > Print > Save as PDF works in every
// browser with zero extra dependencies, ideal for a XAMPP/local final-year setup).
// For a server-generated .pdf file instead, install TCPDF or mPDF via Composer:
//   composer require tecnickcom/tcpdf
// then replace everything below the header() calls with TCPDF's Write/Cell calls
// and swap the header 'Content-Type: text/html' pattern for 'application/pdf'.
