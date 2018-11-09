<?php

namespace Drupal\favoritelist_block\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Provides route response from favoritelist_block module.
 */

class FavoritesPage extends ControllerBase {

	/**
	 * Returns a page with a query of all series related to favorites series of current user.
	 *
	 * @return array
	 *	a simple renderable array.
	 */

	public static function PageController() {

		$connection = \Drupal::database();

		$user = User::load(\Drupal::currentUser()->id());

    	$uid = $user->get('uid')->value;

    /**
    	* Get the ID's of nodes.
    	*/

		$nids = [];

		$result = $connection->query("SELECT * FROM favorites_list WHERE uid = :uid" , array('uid' => $uid));

		while ($nodes = $result->fetchAssoc()) {
			$nids[] = $nodes['nid'];
			
		}

		//$node_type = 'node';
		$entities = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);

		foreach ($entities as $entity) {
			
			$remove = Url::fromUserInput('/user/favorites/'. $entity->id() . '/remove');

			$output[]=array(
					'title' => Link::fromTextAndUrl($entity->getTitle(), $entity->toUrl()),
					'link' => Link::fromTextAndUrl(' | Remove  ', $remove),
				);  
		}

		$header = [
			'title' => t('<h1>Your favourites</h1>'),
			'link' => t('<h1> |  Remove of the list  </h1> '),
		];

		return array(
			'#type' => 'table',
			'#header' => $header,
			'#rows' => $output,
			//'#cache' => ['max-age' = 0],
		);

	}

}
