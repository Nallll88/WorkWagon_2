<?php
  // apply.php — job application form page
  $page_title = 'Apply';
include("header.inc");
include("menu.inc");
?>

<main class="ContainerNum1">
  <h1 class="applyTitle">Job Application</h1>
  <p class="secondParagraph">Please complete the form below to apply.</p>

  <div class="formContainer">
    <form action="processEOI.php" method="post" novalidate>
      <div class="formtext">
        <label for="job_ref">Job reference number</label>
        <input
          type="text" name="job_ref" id="job_ref"
          maxlength="5" minlength="5" pattern="[A-Za-z0-9]{5}"
          required
        >

        <label for="first_name">First name</label>
        <input
          type="text" name="first_name" id="first_name"
          pattern="^[A-Za-z]{1,20}$" required
        >

        <label for="last_name">Last name</label>
        <input
          type="text" name="last_name" id="last_name"
          pattern="^[A-Za-z]{1,20}$" required
        >

        <label for="birth">Date of birth</label>
        <input type="date" name="birth" id="birth" required>
      </div>

      <fieldset class="checks">
        <legend>Gender</legend>
        <input type="radio" id="male"   name="gender" value="male" required>
        <label for="male">Male</label>
        <input type="radio" id="female" name="gender" value="female">
        <label for="female">Female</label>
        <input type="radio" id="other"  name="gender" value="other">
        <label for="other">Other</label>
      </fieldset>

      <fieldset class="location">
        <legend>Location</legend>
        <label for="street_address">Street Address</label>
        <input
          type="text" name="street_address" id="street_address"
          maxlength="40" required
        >

        <label for="suburb">Suburb/Town</label>
        <input
          type="text" name="suburb" id="suburb"
          maxlength="40" required
        >

        <label for="state">State</label>
        <select name="state" id="state" required>
          <option value="">-- Please choose --</option>
          <option value="VIC">VIC</option>
          <option value="NSW">NSW</option>
          <option value="QLD">QLD</option>
          <option value="NT">NT</option>
          <option value="WA">WA</option>
          <option value="SA">SA</option>
          <option value="TAS">TAS</option>
          <option value="ACT">ACT</option>
        </select>

        <label for="postcode">Postcode</label>
        <input
          type="text" name="postcode" id="postcode"
          pattern="\d{4}" maxlength="4" required
        >
      </fieldset>

      <fieldset class="contact">
        <legend>Contact</legend>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="phone">Phone number</label>
        <input
          type="tel" name="phone" id="phone"
          pattern="^\d{8,12}$" required
        >
      </fieldset>

      <fieldset class="skills">
        <legend>Please select your skills</legend>
        <label><input type="checkbox" name="skill1" value="communication"> Communication</label>
        <label><input type="checkbox" name="skill2" value="teamwork"> Teamwork</label>
        <label><input type="checkbox" name="skill3" value="problem_solving"> Problem Solving</label>

        <label for="other_skills">Other skills</label>
        <textarea id="other_skills" name="other_skills" rows="4" cols="33"
                  placeholder="Describe any additional skills"></textarea>
      </fieldset>

      <button type="submit" class="submit">Submit Application</button>
    </form>
  </div>
</main>

<?php include ("footer.inc"); ?>
