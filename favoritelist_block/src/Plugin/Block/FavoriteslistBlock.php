<?php

namespace Drupal\favoritelist_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a 'Favorites Block' Block.
 *
 * @Block(
 * 	 id = "favoritelist_block",
 * 	 admin_label = @Translation("Favorites Block"),
 * 	 category = @Translation("Custom Block"),
 *  )
 */

class FavoriteslistBlock extends BlockBase {

	/*
	 *  {@inheritdoc}
	 */
	public function build(){

		$form = \Drupal::formBuilder()->getForm('Drupal\favoritelist_block\Form\FavoriteslistForm');
		return $form;

	}
}
