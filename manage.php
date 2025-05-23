<?php
// manage.php — HR manager interface for querying and updating EOIs
$page_title = 'Manage EOIs';
include 'header.inc';
include 'settings.php';

// Connect to database
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$message = '';

// Handle deletion by job reference
if (isset($_POST['delete_job_ref'])) {
    $delRef = $mysqli->real_escape_string(trim($_POST['delete_job_ref']));
    $mysqli->query("DELETE FROM eoi WHERE job_ref='{$delRef}'")
        or die("Deletion failed: " . $mysqli->error);
    $message = "Deleted all EOIs for job reference '{$delRef}'.";
}

// Handle status update for a specific EOI
if (isset($_POST['update_status'])) {
    $eoiId    = (int) $_POST['eoi_id'];
    $newStat  = $mysqli->real_escape_string($_POST['new_status']);
    $mysqli->query("UPDATE eoi SET status='{$newStat}' WHERE EOInumber={$eoiId}")
        or die("Status update failed: " . $mysqli->error);
    $message = "Updated EOI #{$eoiId} to status '{$newStat}'.";
}

// Build filter conditions
$where = [];
if (!empty($_POST['job_ref_filter'])) {
    $where[] = "job_ref='" . $mysqli->real_escape_string($_POST['job_ref_filter']) . "'";
}
if (!empty($_POST['first_name_filter'])) {
    $where[] = "first_name LIKE '%" . $mysqli->real_escape_string($_POST['first_name_filter']) . "%'";
}
if (!empty($_POST['last_name_filter'])) {
    $where[] = "last_name LIKE '%" . $mysqli->real_escape_string($_POST['last_name_filter']) . "%'";
}
// Query EOIs
$sql = "SELECT * FROM eoi" . (!empty($where) ? ' WHERE ' . implode(' AND ', $where) : '');
$result = $mysqli->query($sql) or die("Query failed: " . $mysqli->error);
?>
<main>
  <h1>Manage Applications</h1>
  <?php if ($message): ?>
    <p class="notice"><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>

  <!-- Filter Form -->
  <section>
    <h2>Search EOIs</h2>
    <form method="post">
      <label>Job Ref: <input type="text" name="job_ref_filter"></label>
      <label>First Name: <input type="text" name="first_name_filter"></label>
      <label>Last Name: <input type="text" name="last_name_filter"></label>
      <button type="submit">Search</button>
    </form>
  </section>

  <!-- Delete Form -->
  <section>
    <h2>Delete EOIs by Job Ref</h2>
    <form method="post">
      <label>Job Ref: <input type="text" name="delete_job_ref"></label>
      <button type="submit">Delete</button>
    </form>
  </section>

  <!-- Results Table -->
  <section>
    <h2>Results</h2>
    <?php if ($result->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Job Ref</th>
            <th>Name</th>
            <th>Status</th>
            <th>Change Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $row['EOInumber']; ?></td>
              <td><?php echo htmlspecialchars($row['job_ref']); ?></td>
              <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
              <td><?php echo $row['status']; ?></td>
              <td>
                <form method="post">
                  <input type="hidden" name="eoi_id" value="<?php echo $row['EOInumber']; ?>">
                  <select name="new_status">
                    <option<?php if ($row['status']=='New')      echo ' selected'; ?>>New</option>
                    <option<?php if ($row['status']=='Current')  echo ' selected'; ?>>Current</option>
                    <option<?php if ($row['status']=='Final')    echo ' selected'; ?>>Final</option>
                  </select>
                  <button type="submit" name="update_status">Update</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No matching EOIs found.</p>
    <?php endif; ?>
  </section>
</main>
<?php include 'footer.inc'; ?>
