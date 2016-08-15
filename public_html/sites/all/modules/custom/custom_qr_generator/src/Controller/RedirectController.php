<?php

namespace Drupal\custom_qr_generator\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class RedirectController extends ControllerBase {
	public function custom_redirect(Request $request) {

		$incoming_url = $request->get('incoming_url');

    $query = \Drupal::entityQuery('node')
    ->condition('status', 1)
    ->condition('type', 'qr_node')
    ->condition('field_incoming_url.value', $incoming_url, '=');

    $result = $query->execute();
    $nid = array_values($result)[0];

		$node_url = $GLOBALS['base_url'] . '/node/' . $nid;
		$node = \Drupal\node\Entity\Node::load($nid);

		if (!$node) {
			return new RedirectResponse($node_url);
		}

		$node_arr = $node->toArray();

		if ($node_arr['type'][0]['target_id'] == 'qr_node') {
			$addr = $node_arr['field_outgoing_url'][0]['value'];

			$qr_stats_items = db_select('custom_qr_generator_stats', 'cqrs')
				->fields('cqrs')
				->condition('qrnid', $nid, '=')
				->range(0, 1)
				->execute()
				->fetchAll();

			if (count($qr_stats_items) > 0) {
				$this->_update_visits($nid, $qr_stats_items[0]->url_redirections);
			} else {
				$this->_add_new_visit($nid);
			}
		} else {
			$addr = $node_url;
		}

		return new TrustedRedirectResponse($addr);
	}


	private function _add_new_visit($nid) {
		db_insert('custom_qr_generator_stats')
			->fields(array('qrnid' => $nid, 'url_redirections' => '1'))
			->execute();
	}


	private function _update_visits($nid, $num) {
		$new_num = (int)$num + 1;

		db_update('custom_qr_generator_stats')
			->fields(array('url_redirections' => $new_num))
			->condition('qrnid', $nid, '=')
			->execute();
	}
}
