<?php
require_once plugin_dir_path(__FILE__) . 'GetPets.php';
$getPets = new getPets();
get_header(); ?>

<div class="page-banner">
  <div class="page-banner__bg-image"  ></div>
  <!-- <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);"></div> -->
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title">Pet Adoption</h1>
    <div class="page-banner__intro">
      <p>Providing forever homes one search at a time.</p>
    </div>
  </div>  
</div>

<div class="container container--narrow page-section">
<?php 
    if (current_user_can('administrator')) { ?>
    <form action="<?= esc_url(admin_url('admin-post.php')) ?>" class="create-pet-form" method="POST">
      <h2>Register a pet</h2>
      <label for="pet_name">Enter just the name for a pet. Its species, weight and other details will be generated.</label>
      <p><br></p>
      <div>
        <input type="hidden" name="action" value="add_pet">
        <input type="text" name="pet_name" placeholder="Joe Bloggs">
        <button type="submit" value="" class="">Add pet</button>
      </div>
    </form>
    <br>
    <hr>
    <!-- The hidden input named "action" creates a hook for us to reference by the value eg add_pet.
      Within admin-post.php, it will recognise the action value and provide a hook for us to leverage when the form is submitted.
  -->
  <?php
    }
  ?>

  <!-- <pre>
  <?php 
  //var_dump($getPets);
  ?>
  </pre> -->
  <?php 
  if (isset($_GET['message'])) {
    echo "<p>{$_GET['message']}</p>";
   }
  ?>
  <p>This page took <strong><?php echo timer_stop();?></strong> seconds to prepare. Found <strong><?= number_format($getPets->count[0]->count, 2) ?></strong> results (showing the first <?= count($getPets->pets)?>).</p>
  <table class="pet-adoption-table">
    <tr>
      <th>Name</th>
      <th>Species</th>
      <th>Weight</th>
      <th>Birth Year</th>
      <th>Hobby</th>
      <th>Favorite Color</th>
      <th>Favorite Food</th>
      <?php if (current_user_can('administrator')) { ?>
      <th></th>
      <?php } ?>
    </tr>
    <?php foreach($getPets->pets as $pet):?>
    <tr>
      <td><?= $pet->petname ?></td>
      <td><?= $pet->species ?></td>
      <td><?= $pet->petweight ?></td>
      <td><?= $pet->birthyear ?></td>
      <td><?= $pet->favhobby ?></td>
      <td><?= $pet->favcolor ?></td>
      <td><?= $pet->favfood ?></td>
      <!-- ToDo -->
      <?php if (current_user_can('administrator')){ ?>
      <td>
        <a href="<?= site_url('/edit-pet/' . $pet->id) ?>">Edit pet</a>
        <form action="<?= esc_url(admin_url('admin-post.php')) ?>" method="POST">
          <input type="hidden" name="action" value="delete_pet">
          <input type="hidden" name="pet_id" value="<?= $pet->id ?>">
          <input type="hidden" name="pet_name" value="<?= $pet->petname ?>">
          <button type="submit">Delete pet</button>
        </form>
      </td>
      <?php } ?>
    </tr>
    <?php endforeach; ?>
  </table>
</div>

<?php get_footer(); ?>