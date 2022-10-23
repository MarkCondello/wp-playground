<?php
require_once plugin_dir_path(__FILE__) . 'Pet.php';
$Pet = new Pet();
//  $petId = get_query_var('pet');
$petId = end(explode('/', $_SERVER['REQUEST_URI']));
$pet = $Pet->get($petId)[0];
get_header();
echo '<pre>';
var_dump($pet);
echo '</pre>';
?>

<div class="page-banner">
  <div class="page-banner__bg-image"></div>
   <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title"><?= $pet->petname ?></h1>
    <div class="page-banner__intro">
      <p>Edit Pet #<?= $pet->id ?></p>
    </div>
  </div>
</div>
<div class="container container--narrow page-section">
  <form action="<?= esc_url(admin_url('admin-post.php')) ?>" class="create-pet-form" method="POST">
  <input type="hidden" name="action" value="edit_pet">
  <input type="hidden" name="petid" value="<?= $pet->id ?>">
  <div>
    <label for="pet_name">Name:</label>
    <input type="text" name="petname" placeholder="Joe Bloggs" value="<?= $pet->petname ?>">
  </div>
  <div>
    <label for="species">Species:</label>
    <input type="text" name="species" placeholder="Dog" value="<?= $pet->species ?>">
  </div>
  <div>
    <label for="birthyear">Birthyear:</label>
    <input type="number" name="birthyear" value="<?= $pet->birthyear ?>" min="1980">
  </div>
  <div>
    <label for="petweight">Weight:</label>
    <input type="number" name="petweight" value="<?= $pet->petweight ?>" min="1">
  </div>
  <div>
    <label for="favfood">Favourite Food:</label>
    <input type="string" name="favfood" value="<?= $pet->favfood ?>"  placeholder="Pasta">
  </div>
  <div>
    <label for="favhobby">Favourite Hobby:</label>
    <input type="string" name="favhobby" value="<?= $pet->favhobby ?>" placeholder="Sleeping">
  </div>
  <div>
    <label for="favcolor">Favourite Colour:</label>
    <input type="string" name="favcolor" value="<?= $pet->favcolor ?>" placeholder="Brown">
  </div>
  <button type="submit"  class="btn btn--orange">Edit pet</button>
  </form>
</div>

<?php get_footer(); ?>