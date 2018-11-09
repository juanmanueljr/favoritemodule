<?php
namespace Drupal\favoritelist_block\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
use Drupal\user\Entity\User;
/**
 * Class RemoveForm.
 *
 * @package Drupal\favoritelist_block\Form
 */

class RemoveForm extends ConfirmFormBase {

/**
   * {@inheritdoc}
   */

  public function getFormId() {
   
    return 'remove_form';
  }
  public $nid;

  public function getQuestion() { 

   	//$current_uri = \Drupal::request()->getRequestUri();
   	$entity = \Drupal::entityTypeManager()->getStorage('node')->load($this->id);

    return t('Do you want to remove @nid?', ['@nid' => $entity->getTitle()]);
  }

 public function getCancelUrl() {
   
    return new Url('favoritelist_block.favorites_page');
}

public function getDescription() {
    
    return t('<h2 class="text-warning"> Only do this if you are sure!</h2>');
  }

  /**
   * {@inheritdoc}
   */

  public function getConfirmText() {
    
    return t('Removed it!');
  }

  /**
   * {@inheritdoc}
   */

  public function getCancelText() {
    
    return t('Cancel!');
  }

  /**
   * {@inheritdoc}
   */

  public function buildForm(array $form, FormStateInterface $form_state, $nid = NULL) {
     
    $this->id = $nid;
    //$form['#title']= 'hola mundo';
    return parent::buildForm($form, $form_state);
  }

  /**
    * {@inheritdoc}
    */

  public function validateForm(array &$form, FormStateInterface $form_state) {
   
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */

  public function submitForm(array &$form, FormStateInterface $form_state) {

  		 $user = User::load(\Drupal::currentUser()->id());

   		$uid = $user->get('uid')->value;

       $query = \Drupal::database();

       $query->delete('favorites_list')
                  ->condition('nid',$this->id)
                  ->condition('uid', $uid)
                  ->execute();
             drupal_set_message("successfully removed");

          $form_state->setRedirect('favoritelist_block.favorites_page');
  }

}
