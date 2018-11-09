<?php

namespace Drupal\playlist_block\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Provides route response from playlist_block module.
 */

class FavouritesPage extends ControllerBase {

	/**
	 * Returns a page with a query of all series related to favourites series of current user.
	 *
	 * @return array
	 *	a simple renderable array.
	 */

	public function PageController() {

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

		$node_type = 'node';
		$entities = \Drupal::entityTypeManager()->getStorage($node_type)->loadMultiple($nids);

		$output = [];

		foreach ($entities as $entity) {
			
			$remove = Url::fromUserInput('/favourites/'. $entity->id() . '/remove');

			$output[]=array(
					'title' => Link::fromTextAndUrl($entity->getTitle(), $entity->toUrl()),
					'link' => Link::fromTextAndUrl('        |           Remove  ', $remove),
				);  
		}

		$header = [
			'title' => t('<h1>Your favourites                    </h1>'),
			'link' => t('<h1>     |         Remove of the list  </h1> '),
		];

		return array(
			'#type' => 'table',
			'#header' => $header,
			'#rows' => $output,
			//'#cache' => ['max-age' = 0],
		);

	}

}
