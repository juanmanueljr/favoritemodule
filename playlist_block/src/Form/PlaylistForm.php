<?php

/**
 * @file
 * contains \Drupal\playlist_block\Form;
 */

namespace Drupal\playlist_block\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use \Drupal\user\Entity\User;

class PlaylistForm extends FormBase {

  /**
	 * {@inheritdoc}
	 */
	public function getFormId() {
    return 'playlist_form';
  }

  /**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {

		$form['nodes_series'] = [
			'#type' => 'fieldset',
			'#title' => $this->t('Favourties'),
		];

    $form['nodes_series']['field_nodes'] = [
       '#type' => 'entity_autocomplete',
       '#target_type' => 'node',
       '#selection_settings' => [
           'target_bundles' => array('Series', 'movie'),
       ],
       '#placeholder' => ('Write the content that you want to add..'),
     ];

		 $form['nodes_series']['actions'] = [
 			'#type' => 'submit',
 			'#value' => $this->t('Save'),
 		];

    /**
     * @RenderElement("link");
     */

    $form['link_favourites'] = [
      '#type' => 'link',
      '#title' => $this->t('Go to Favorites list'),
      '#url' => \Drupal\Core\Url::fromRoute('playlist_block.favourites_page'),
    ];


		return $form;
  }

   /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $connection = \Drupal::database();

    /**
     * Load the current User.
     * Use \Drupal\user\Entity\User::load.
     */

    $user = User::load(\Drupal::currentUser()->id());

    $uid = $user->get('uid')->value;
    $nid = $form_state->getValue('field_nodes');


    /**
     * Query to verify if the serie is save in the list yet.
     */

    $query = $connection->query('SELECT * FROM favorites_list WHERE nid = :nid AND uid = :uid' ,
    ['nid' => $nid , 'uid' => $uid]);

    $verifyQ = $query->fetchAssoc();

    if ($verifyQ['nid'] == $nid && $verifyQ['uid'] == $uid) {

      drupal_set_message(t('The serie is in the list yet!.'));

    } else {

      $result = $connection->insert('favorites_list')->fields([
      'id' => NULL,
      'uid' => $uid,
      'nid' => $nid,

      ])->execute();

      if ($result) {
        drupal_set_message(t('Serie added to the list!'));
      } else {
        drupal_set_message(t('Error, Please try it again later'));
      }
    }

	}
}
