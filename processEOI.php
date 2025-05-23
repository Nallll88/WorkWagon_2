<?php
// processEOI.php — handles submission, table creation, validation, insertion, confirmation

// 1) Prevent direct access via GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: apply.php');
    exit;
}

// 2) Include DB credentials and connect
include 'settings.php';
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// 3) Create EOI table if it doesn't exist
$createSQL = "
CREATE TABLE IF NOT EXISTS eoi (
  EOInumber      INT AUTO_INCREMENT PRIMARY KEY,
  job_ref        CHAR(5)          NOT NULL,
  first_name     VARCHAR(20)      NOT NULL,
  last_name      VARCHAR(20)      NOT NULL,
  birth          DATE             NOT NULL,
  gender         ENUM('male','female','other') NOT NULL,
  street_address VARCHAR(40)      NOT NULL,
  suburb         VARCHAR(40)      NOT NULL,
  state          ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  postcode       CHAR(4)          NOT NULL,
  email          VARCHAR(255)     NOT NULL,
  phone          VARCHAR(12)      NOT NULL,
  skill1         TINYINT UNSIGNED DEFAULT 0,
  skill2         TINYINT UNSIGNED DEFAULT 0,
  skill3         TINYINT UNSIGNED DEFAULT 0,
  other_skills   TEXT,
  status         ENUM('New','Current','Final') DEFAULT 'New',
  created        TIMESTAMP        DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
";
if (!$mysqli->query($createSQL)) {
    die("Table creation failed: " . $mysqli->error);
}

// 4) Sanitisation helper
function clean($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// 5) Collect & clean POST data
$job_ref      = clean($_POST['job_ref']);
$first_name   = clean($_POST['first_name']);
$last_name    = clean($_POST['last_name']);
$birth_raw    = clean($_POST['birth']);
$gender       = clean($_POST['gender']);
$street       = clean($_POST['street_address']);
$suburb       = clean($_POST['suburb']);
$state        = clean($_POST['state']);
$postcode     = clean($_POST['postcode']);
$email        = clean($_POST['email']);
$phone        = clean($_POST['phone']);
$skill1       = isset($_POST['skill1']) ? 1 : 0;
$skill2       = isset($_POST['skill2']) ? 1 : 0;
$skill3       = isset($_POST['skill3']) ? 1 : 0;
$other_skills = clean($_POST['other_skills']);

// 6) Validate and collect errors
$errors = [];
if (!preg_match('/^[A-Za-z0-9]{5}$/', $job_ref)) {
    $errors[] = "Job reference must be exactly 5 letters or digits.";
}
if (!preg_match('/^[A-Za-z]{1,20}$/', $first_name)) {
    $errors[] = "First name must be 1–20 alphabetic characters.";
}
if (!preg_match('/^[A-Za-z]{1,20}$/', $last_name)) {
    $errors[] = "Last name must be 1–20 alphabetic characters.";
}
$dob = DateTime::createFromFormat('Y-m-d', $birth_raw);
if (!$dob) {
    $errors[] = "Invalid date of birth format.";
} else {
    $age = (new DateTime())->diff($dob)->y;
    if ($age < 15 || $age > 80) {
        $errors[] = "Age must be between 15 and 80.";
    }
}
if (!in_array($gender, ['male','female','other'])) {
    $errors[] = "Please select a valid gender.";
}
if (strlen($street) > 40) {
    $errors[] = "Street address must be up to 40 characters.";
}
if (strlen($suburb) > 40) {
    $errors[] = "Suburb/Town must be up to 40 characters.";
}
if (!in_array($state, ['VIC','NSW','QLD','NT','WA','SA','TAS','ACT'])) {
    $errors[] = "Please select a valid state.";
}
if (!preg_match('/^\d{4}$/', $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}
if (!preg_match('/^[0-9 ]{8,12}$/', $phone)) {
    $errors[] = "Phone number must be 8–12 digits or spaces.";
}

// 7) Display errors if any
if ($errors) {
    include 'header.inc';
    echo "<h1>Form Errors</h1><ul>";
    foreach ($errors as $e) {
        echo "<li>" . htmlspecialchars($e) . "</li>";
    }
    echo "</ul><p><a href='apply.php'>Go back to the form</a></p>";
    include 'footer.inc';
    exit;
}

// 8) Insert validated record
$stmt = $mysqli->prepare(
    "INSERT INTO eoi
     (job_ref, first_name, last_name, birth, gender,
      street_address, suburb, state, postcode,
      email, phone, skill1, skill2, skill3, other_skills)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

// Notice 11 's' types (for strings) and 3 'i' (for skill flags), total 14 placeholders + 1
$stmt->bind_param(
    'sssssssssssiiis',
    $job_ref,
    $first_name,
    $last_name,
    $birth_raw,
    $gender,
    $street,
    $suburb,
    $state,
    $postcode,
    $email,
    $phone,
    $skill1,
    $skill2,
    $skill3,
    $other_skills
);

if (!$stmt->execute()) {
    die("Database insert failed: " . $stmt->error);
}

// 9) Fetch new EOInumber
$eoiNumber = $stmt->insert_id;
$stmt->close();
$mysqli->close();

// 10) Confirmation page
include 'header.inc';
?>
<main>
  <h1>Thank You</h1>
  <p>Your application has been submitted successfully.</p>
  <p><strong>Your EOI Number is: <?php echo $eoiNumber; ?></strong></p>
  <p><a href="index.php">Return Home</a></p>
</main>
<?php include 'footer.inc'; ?>
