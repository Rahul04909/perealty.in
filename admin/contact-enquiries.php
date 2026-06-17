<?php
/**
 * Admin General Contact Enquiries Management Portal
 * Prime Edge Realty
 */

// Include Admin Header (handles authentication)
include './header.php';

// Generate CSRF Token for actions
if (empty($_SESSION['contact_enquiries_csrf_token'])) {
    $_SESSION['contact_enquiries_csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$success = '';

// Handle POST Action (Status update / Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['contact_enquiries_csrf_token'], $_POST['csrf_token'])) {
        $error = 'Invalid security token validation.';
    } else {
        $action = $_POST['action'] ?? '';
        $id = (int)($_POST['id'] ?? 0);
        
        try {
            $db = db();
            if ($action === 'delete') {
                $stmt = $db->prepare("DELETE FROM `contact_enquiries` WHERE `id` = ?");
                $stmt->execute([$id]);
                $success = 'Contact enquiry deleted successfully.';
            } elseif ($action === 'update_status') {
                $status = $_POST['status'] ?? 'New';
                $allowedStatuses = ['New', 'Contacted', 'Closed'];
                if (in_array($status, $allowedStatuses)) {
                    $stmt = $db->prepare("UPDATE `contact_enquiries` SET `status` = ? WHERE `id` = ?");
                    $stmt->execute([$status, $id]);
                    $success = 'Contact enquiry status updated successfully.';
                } else {
                    $error = 'Invalid status parameter.';
                }
            }
        } catch (\Exception $e) {
            $error = 'Error occurred: ' . $e->getMessage();
        }
    }
}

// Fetch all contact enquiries
try {
    $db = db();
    $stmt = $db->query("SELECT * FROM `contact_enquiries` ORDER BY `id` DESC");
    $enquiries = $stmt->fetchAll();
} catch (\Exception $e) {
    $enquiries = [];
    $error = 'Failed to load contact enquiries: ' . $e->getMessage();
}

$page_title = "Contact Enquiries";
?>

<div class="container-fluid text-dark">
    <!-- Feedback Alerts -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2 px-3 mb-4 d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2" style="margin-right: 10px;"></i>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success py-2 px-3 mb-4 d-flex align-items-center">
            <i class="fas fa-check-circle me-2" style="margin-right: 10px;"></i>
            <span><?php echo htmlspecialchars($success); ?></span>
        </div>
    <?php endif; ?>

    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 text-dark font-weight-bold">Contact Enquiries</h1>
            <p class="text-muted">Manage general website submissions and investment inquiries submitted from the Contact Us page.</p>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card card-outline card-success" style="border-top: 3px solid var(--primary-green); background-color: #ffffff !important; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div class="card-body p-0 text-dark">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle" style="font-size: 0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th style="padding: 12px 15px; width: 150px;">Received Date</th>
                            <th style="padding: 12px 15px; width: 220px;">Client Info</th>
                            <th style="padding: 12px 15px; width: 200px;">Property Interest</th>
                            <th style="padding: 12px 15px;">Message</th>
                            <th style="padding: 12px 15px; width: 140px; text-align: center;">Status</th>
                            <th style="padding: 12px 15px; width: 150px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($enquiries)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No contact enquiries registered yet. Submissions from the Contact page will show up here.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($enquiries as $enq): ?>
                                <?php 
                                // Set badge class
                                $badgeClass = 'bg-info';
                                if ($enq['status'] === 'Contacted') {
                                    $badgeClass = 'bg-warning text-dark';
                                } elseif ($enq['status'] === 'Closed') {
                                    $badgeClass = 'bg-success';
                                }
                                ?>
                                <tr>
                                    <td style="padding: 12px 15px; font-size: 0.8rem; color: #555;">
                                        <i class="far fa-clock me-1"></i> <?php echo date('M d, Y h:i A', strtotime($enq['created_at'])); ?>
                                    </td>
                                    <td style="padding: 12px 15px; line-height: 1.5;">
                                        <strong class="text-dark d-block"><?php echo htmlspecialchars($enq['name']); ?></strong>
                                        <span class="text-muted d-block" style="font-size: 0.8rem;"><i class="far fa-envelope me-1"></i> <a href="mailto:<?php echo htmlspecialchars($enq['email']); ?>"><?php echo htmlspecialchars($enq['email']); ?></a></span>
                                        <span class="text-muted d-block" style="font-size: 0.8rem;"><i class="fas fa-phone me-1"></i> <a href="tel:<?php echo htmlspecialchars($enq['phone']); ?>"><?php echo htmlspecialchars($enq['phone']); ?></a></span>
                                    </td>
                                    <td style="padding: 12px 15px;">
                                        <span class="font-weight-bold text-dark d-block"><?php echo htmlspecialchars($enq['property_interest']); ?></span>
                                    </td>
                                    <td style="padding: 12px 15px; font-size: 0.85rem; max-width: 300px; word-wrap: break-word; white-space: normal; color: #444;">
                                        <?php echo nl2br(htmlspecialchars($enq['message'])); ?>
                                    </td>
                                    <td style="padding: 12px 15px; text-align: center;">
                                        <span class="badge <?php echo $badgeClass; ?> px-2 py-1" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                                            <?php echo htmlspecialchars($enq['status']); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px 15px; text-align: center;">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <!-- Status Dropdown -->
                                            <div class="dropdown me-1">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Change Status">
                                                    <i class="fas fa-tasks"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="updateStatus(<?php echo $enq['id']; ?>, 'New')">Set New</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="updateStatus(<?php echo $enq['id']; ?>, 'Contacted')">Set Contacted</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="updateStatus(<?php echo $enq['id']; ?>, 'Closed')">Set Closed</a></li>
                                                </ul>
                                            </div>
                                            <!-- Delete Button -->
                                            <button type="button" class="btn btn-sm btn-danger" title="Delete" onclick="confirmDelete(<?php echo $enq['id']; ?>, '<?php echo htmlspecialchars(addslashes($enq['name'])); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- CSRF-safe hidden forms -->
<form id="action-form" action="contact-enquiries.php" method="post" style="display:none;">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['contact_enquiries_csrf_token'] ?? ''); ?>">
    <input type="hidden" name="action" id="action-type" value="">
    <input type="hidden" name="id" id="action-id" value="">
    <input type="hidden" name="status" id="action-status" value="">
</form>

<script>
function updateStatus(id, newStatus) {
    document.getElementById('action-type').value = 'update_status';
    document.getElementById('action-id').value = id;
    document.getElementById('action-status').value = newStatus;
    document.getElementById('action-form').submit();
}

function confirmDelete(id, clientName) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to permanently delete ' + clientName + '\'s contact enquiry?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('action-type').value = 'delete';
            document.getElementById('action-id').value = id;
            document.getElementById('action-form').submit();
        }
    });
}
</script>

<?php 
// Include Admin Footer
include './footer.php'; 
?>
