<?php

namespace Drupal\Tests\core_views_facets\Functional;

use Drupal\Core\Url;
use Drupal\views\Tests\ViewTestData;

/**
 * Tests the overall functionality of the Facets admin UI.
 *
 * @group core_views_facets
 */
class CoreViewsIntegrationTest extends WebTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['views_ui', 'entity_test'];

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = ['core_views_facets_basic_integration'];

  /**
   * {@inheritdoc}
   */
  public function setUp() : void {
    parent::setUp();

    $this->drupalLogin($this->adminUser);

    $this->setUpExampleStructure();
    $this->insertExampleContent();

    // Create test views.
    ViewTestData::createTestViews(static::class, [
      'core_views_facets_test_views',
    ]);

    // Make absolutely sure the ::$blocks variable doesn't pass information
    // along between tests.
    $this->blocks = NULL;
  }

  /**
   * Tests that an url alias works correctly.
   */
  public function testExposedFilterUrlAlias() {
    $facet_name = "Test Facet URL";
    $facet_id = 'test_facet_url';

    // Make sure we're logged in with a user that has sufficient permissions.
    $this->drupalLogin($this->adminUser);

    $this->createFacet($facet_name, $facet_id);

    $this->drupalGet($this->facetUrl);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertFacetLabel('item');
    $this->assertFacetLabel('article');

    $this->clickLink('item');
    $url = Url::fromUserInput('/' . $this->facetUrl . '/all/all', ['query' => ['type' => 'item']]);
    $this->assertSession()->addressEquals($url);
  }

  /**
   * Tests that an url alias works correctly.
   */
  public function testContextualFilterUrlAlias() {
    $facet_name = "Test Facet URL";
    $facet_id = 'test_facet_url';

    // Make sure we're logged in with a user that has sufficient permissions.
    $this->drupalLogin($this->adminUser);

    $this->createFacet($facet_name, $facet_id, 'type', 'page_1', 'core_views_facets_basic_integration', 'contextual');

    $this->drupalGet($this->facetUrl);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertFacetLabel('item');
    $this->assertFacetLabel('article');

    $this->clickLink('item');
    $url = Url::fromUserInput('/' . $this->facetUrl . '/all/item');
    $this->assertSession()->addressEquals($url);
  }

  /**
   * Test that a missing facet source field selection prevents facet creation.
   */
  public function testFacetFormValidate() {
    $id = 'southern_white_facet_owl';
    $name = 'Southern white-faced owl';
    $facet_add_page = Url::fromRoute('entity.facets_facet.add_form');
    $this->drupalGet($facet_add_page);
    $this->assertSession()->statusCodeEquals(200);

    $edit = [
      'name' => $name,
      'id' => $id,
      'facet_source_id' => $this->exposedFiltersFacetSourceId,
    ];
    $this->submitForm($edit, 'Save');
    $this->assertSession()->responseContains('Please select a valid field.');
  }

  /**
   * Tests various operations via the Facets' admin UI.
   */
  public function testFramework() {
    $facet_name = "Test Facet name";
    $facet_id = 'test_facet_name';

    // Check if the overview is empty.
    $this->checkEmptyOverview();

    // Add a new facet and edit it. Check adding a duplicate.
    $this->addFacet($facet_name);
    $this->editFacet($facet_name);

    // By default, the view should show all entities.
    $this->drupalGet($this->facetUrl);
    $this->assertSession()->pageTextContains('Displaying 5 search results');

    // Create and place a block for "Test Facet name" facet.
    $this->blocks[$facet_id] = $this->createBlock($facet_id);

    // Verify that the facet results are correct.
    $this->drupalGet($this->facetUrl);
    $this->assertSession()->pageTextContains('item');
    $this->assertSession()->pageTextContains('article');

    // Verify that facet blocks appear as expected.
    $this->assertFacetBlocksAppear();

    // Verify that the facet only shows when the facet source is visible, it
    // should not show up on the user page.
    $this->setOptionShowOnlyWhenFacetSourceVisible($facet_name);
    $this->drupalGet('user/2');
    $this->assertNoFacetBlocksAppear();

    $content_ids = \Drupal::entityQuery('entity_test')
      ->accessCheck()
      ->execute();
    $storage = \Drupal::entityTypeManager()->getStorage('entity_test');
    $entities = $storage->loadMultiple($content_ids);
    $storage->delete($entities);

    // Do not show the block on empty behaviors.
    $this->drupalGet($this->facetUrl);

    // Verify that no facet blocks appear. Empty behavior "None" is selected by
    // default.
    $this->assertNoFacetBlocksAppear();

    // Verify that the "empty_text" appears as expected.
    $this->setEmptyBehaviorFacetText($facet_name);
    $this->drupalGet($this->facetUrl);
    $this->assertSession()->responseContains('block-test-facet-name');
    $this->assertSession()->responseContains('No results found for this block!');
  }

  /**
   * Configures empty behavior option to show a text on empty results.
   *
   * @param string $facet_name
   *   The name of the facet.
   */
  protected function setEmptyBehaviorFacetText($facet_name) {
    $facet_id = $this->convertNameToMachineName($facet_name);

    $facet_display_page = Url::fromRoute('entity.facets_facet.edit_form', [
      'facets_facet' => $facet_id,
    ]);

    // Go to the facet edit page and make sure "edit facet %facet" is present.
    $this->drupalGet($facet_display_page);
    $this->assertSession()->statusCodeEquals(200);

    // Configure the text for empty results behavior.
    $edit = [
      'facet_settings[empty_behavior]' => 'text',
      'facet_settings[empty_behavior_container][empty_behavior_text][value]' => 'No results found for this block!',
    ];
    $this->submitForm($edit, 'Save');

  }

  /**
   * Configures a facet to only be visible when accessing to the facet source.
   *
   * @param string $facet_name
   *   The name of the facet.
   */
  protected function setOptionShowOnlyWhenFacetSourceVisible($facet_name) {
    $facet_id = $this->convertNameToMachineName($facet_name);

    $facet_edit_page = Url::fromRoute('entity.facets_facet.edit_form', [
      'facets_facet' => $facet_id,
    ]);
    $this->drupalGet($facet_edit_page);
    $this->assertSession()->statusCodeEquals(200);

    $edit = [
      'facet_settings[only_visible_when_facet_source_is_visible]' => TRUE,
      'widget' => 'links',
      'widget_config[show_numbers]' => '0',
    ];
    $this->submitForm($edit, 'Save');
  }

  /**
   * Get the facet overview page and make sure the overview is empty.
   */
  protected function checkEmptyOverview() {
    $this->drupalGet(Url::fromRoute('entity.facets_facet.collection'));
    $this->assertSession()->statusCodeEquals(200);

    // The list overview has Field: field_name as description. This tests on the
    // absence of that.
    $this->assertSession()->pageTextNotContains('Field:');
  }

  /**
   * Tests adding a facet trough the interface.
   *
   * @param string $facet_name
   *   The name of the facet.
   * @param string $source_type
   *   Either exposed or contextual.
   * @param string $facet_type
   *   Facet type.
   *
   * @throws \Exception
   */
  protected function addFacet($facet_name, $source_type = 'exposed', $facet_type = 'type') {
    $facet_id = $this->convertNameToMachineName($facet_name);

    $facet_source_id = match ($source_type) {
      'contextual' => $this->contextualFiltersFacetSourceId,
        default => $this->exposedFiltersFacetSourceId,
    };

    $facet_source_edit_page = Url::fromRoute('entity.facets_facet_source.edit_form', [
      'facets_facet_source' => $facet_source_id,
    ]);
    $this->drupalGet($facet_source_edit_page);
    $this->assertSession()->statusCodeEquals(200);

    $url_processor_form_values = [
      'url_processor' => 'core_views_url_processor',
    ];
    $this->submitForm($url_processor_form_values, 'Save');

    // Go to the Add facet page and make sure that returns a 200.
    $facet_add_page = Url::fromRoute('entity.facets_facet.add_form');
    $this->drupalGet($facet_add_page);
    $this->assertSession()->statusCodeEquals(200);

    $form_values = [
      'name' => '',
      'id' => $facet_id,
    ];

    // Try filling out the form, but without having filled in a name for the
    // facet to test for form errors.
    $this->submitForm($form_values, 'Save');
    $this->assertSession()->pageTextContains('Name field is required.');
    $this->assertSession()->pageTextContains('Facet source field is required.');

    // Make sure that when filling out the name, the form error disappears.
    $form_values['name'] = $facet_name;
    $this->submitForm($form_values, 'Save');
    $this->assertSession()->pageTextNotContains('Name field is required.');

    // Configure the facet source by selecting the test view.
    $this->drupalGet($facet_add_page);
    $this->submitForm(['facet_source_id' => $facet_source_id], 'Configure facet source');

    // The field is still required.
    $this->submitForm($form_values, 'Save');
    $this->assertSession()->pageTextContains('Facet field field is required.');

    // Fill in all fields and make sure the 'field is required' message is no
    // longer shown.
    $facet_source_form = [
      'facet_source_id' => $facet_source_id,
      'facet_source_configs[' . $facet_source_id . '][field_identifier]' => $facet_type,
    ];
    $this->submitForm($form_values + $facet_source_form, 'Save');
    $this->assertSession()->pageTextNotContains('field is required.');

    // Make sure that the redirection to the display page is correct.
    $this->assertSession()->pageTextContains('Facet ' . $facet_name . ' has been created.');
    $url = Url::fromRoute('entity.facets_facet.edit_form', [
      'facets_facet' => $facet_id,
    ]);
    $this->assertSession()->addressEquals($url);

    $this->drupalGet(Url::fromRoute('entity.facets_facet.collection'));
  }

  /**
   * Tests editing of a facet through the UI.
   *
   * @param string $facet_name
   *   The name of the facet.
   */
  protected function editFacet($facet_name) {
    $facet_id = $this->convertNameToMachineName($facet_name);

    $facet_edit_page = Url::fromRoute('entity.facets_facet.settings_form', [
      'facets_facet' => $facet_id,
    ]);

    // Go to the facet edit page and make sure "edit facet %facet" is present.
    $this->drupalGet($facet_edit_page);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->responseContains('Facet settings for ' . $facet_name . ' facet');

    // Check if it's possible to change the machine name.
    $elements = $this->xpath('//form[@id="facets-facet-settings-form"]/div[contains(@class, "form-item-id")]/input[@disabled]');
    $this->assertEquals(count($elements), 1, 'Machine name cannot be changed.');

    // Change the facet name to add in "-2" to test editing of a facet works.
    $form_values = ['name' => $facet_name . ' - 2'];
    $this->submitForm($form_values, 'Save');

    // Make sure that the redirection back to the overview was successful and
    // the edited facet is shown on the overview page.
    $this->assertSession()->pageTextContains('Facet ' . $facet_name . ' - 2 has been updated.');

    // Make sure the "-2" suffix is still on the facet when editing a facet.
    $this->drupalGet($facet_edit_page);
    $this->assertSession()->responseContains('Facet settings for ' . $facet_name . ' - 2 facet');

    // Edit the form and change the facet's name back to the initial name.
    $form_values = ['name' => $facet_name];
    $this->submitForm($form_values, 'Save');

    // Make sure that the redirection back to the overview was successful and
    // the edited facet is shown on the overview page.
    $this->assertSession()->pageTextContains('Facet ' . $facet_name . ' has been updated.');
  }

  /**
   * Convert facet name to machine name.
   *
   * @param string $facet_name
   *   The name of the facet.
   *
   * @return string
   *   The facet name changed to a machine name.
   */
  protected function convertNameToMachineName($facet_name) {
    return preg_replace('@[^a-zA-Z0-9_]+@', '_', strtolower($facet_name));
  }

  /**
   * Go to the Delete Facet Page using the facet name.
   *
   * @param string $facet_name
   *   The name of the facet.
   */
  protected function goToDeleteFacetPage($facet_name) {
    $facet_id = $this->convertNameToMachineName($facet_name);

    $facet_delete_page = Url::fromRoute('entity.facets_facet.delete_form', [
      'facets_facet' => $facet_id,
    ]);

    // Go to the facet delete page and make the warning is shown.
    $this->drupalGet($facet_delete_page);
    $this->assertSession()->statusCodeEquals(200);
  }

}
