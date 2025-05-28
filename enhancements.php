
<?php
// enhancements.php
$page_title = 'Enhancements';
include("header.inc");
include("menu.inc");
?>

    <div class ="enchancement#1">
      <h1>Enhancements</h1>
      <p>List of website improvements goes here.</p><br>

      <h2>Enhancement #1: CSS3 Animations (Hero Fade-In & Pulsing CTA)</h2>
      <p>
        I implemented <strong>CSS3 keyframes</strong> to make the hero image fade in and 
        to give the “Apply” CTA button a pulsing effect. This goes beyond a simple 
        color change on hover and demonstrates more advanced use of 
        <code>@keyframes</code> and <code>animation</code> properties.
      </p>
      <p>
        <strong>Code Example:</strong> 
        <pre>
        @keyframes heroFadeIn {
          0%   { opacity: 0; transform: translateY(-20px); }
          100% { opacity: 1; transform: translateY(0); }
        }
        </pre>
      </p>
      <p>Reference:
        <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/@keyframes" target="_blank">
          MDN: CSS @keyframes
        </a>
      </p>
      <p>
        <a href="index.html#hero-section">View the Hero Animation in Action</a>
      </p>
    </div><br>
    <section class="enhancement">
      <h2>Enhancement #2: Rainbow Text Effect</h2>
      <p>
        On the homepage, a rainbow text animation was created using
        <code>linear-gradient</code> and <code>-webkit-background-clip: text</code> to apply the gradient
        to the text itself. The gradient is animated using keyframes to cycle smoothly.
      </p>
      <pre><code>
background: linear-gradient(90deg, #ffeb3b, #00e676, #40c4ff, #7c4dff);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
      </code></pre>
      <p>
        <strong>Reference:</strong>
        <a href="https://www.youtube.com/watch?v=Hvrl93x-P3s" target="_blank">YouTube - Rainbow Animated Text</a>
      </p><br>
    </section>

    <!-- Enhancement 3 -->
    <section class="enhancement">
      <h2>Enhancement #3: Image Hover Zoom on Success Stories</h2>
      <p>
        A smooth zoom effect was applied to images in the success story section using transform scaling:
      </p>
      <pre><code>
transform: scale(1.2);
transition: transform 0.3s ease;
      </code></pre>
      <p>
        This adds interactivity and highlights the story cards when hovered over.
      </p>
      <p>
        <strong>Reference:</strong>
        <a href="https://www.w3schools.com/howto/howto_css_zoom_hover.asp" target="_blank">
          W3Schools - Zoom on Hover
        </a>
      </p>
    </section>
    <!--Enchancement 4-->
    <section class="enhancement">
      <p>Implemented design in the job.php page to insert new available jobs in the page dynamically without hardcoding it.</p>
      <h2>Created a table to insert jobs dynamically to job.php</h2>
      <p>
          CREATE TABLE job_description (
            JobRefNumber CHAR(5) PRIMARY KEY,
            JobTitle VARCHAR(100),
            JobSummary TEXT,
            KeyResponsibilities TEXT,
            RequiredSkills TEXT
          );
      </p>  
      <h2>Command to insert available jobs to the job_description table</h2>       
      <p>
        INSERT INTO job_description 
        (JobRefNumber, JobTitle, JobSummary, KeyResponsibilities, RequiredSkills)
        VALUES
        ('J1234', 'Web Developer', 'Create websites', 'Design pages', 'HTML, CSS, JS');
      </p> 
      <h2>Displaying all the added jobs listed</h2>
      <pre>
      <?php
      echo htmlspecialchars('
      $conn = mysqli_connect($host, $user, $pwd, $sql_db);
      $query = "SELECT * FROM job_description";
      $result = mysqli_query($conn, $query);

      if ($result && mysqli_num_rows($result) != 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<section class=\'job\'>";
          echo "<h2>" . htmlspecialchars($row["JobTitle"]) . " (" . htmlspecialchars($row["JobRefNumber"]) . ")</h2>";
          echo "<p><strong>Summary:</strong> " . htmlspecialchars($row["JobSummary"]) . "</p>";
          echo "<p><strong>Responsibilities:</strong> " . htmlspecialchars($row["KeyResponsibilities"]) . "</p>";
          echo "<p><strong>Skills Required:</strong> " . htmlspecialchars($row["RequiredSkills"]) . "</p>";
          echo "<a href=\'apply.php?jobref=" . urlencode($row["JobRefNumber"]) . "\'>Apply Now</a>";
          echo "</section>";
        }
      }
      mysqli_close($conn);
      ');
      ?>
      </pre>


    </section>
    
<?php include ("footer.inc"); ?>


