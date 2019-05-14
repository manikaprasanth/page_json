<?php
namespace Drupal\page_json\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Controller for export json.
 */
class ExportJsonController extends ControllerBase {
  /**
   * {@inheritdoc}
   */
  public function jsonExport($key = NULL, $node = NULL) {
    
    $api_key = \Drupal::config('system.site')->get('siteapikey');
    $json_array = array(
      'data' => array()
    );
    if ( ($api_key == $key) && is_numeric($node) ) {
      $node =  Node::load($node);
      if(!empty($node) && $node->get('type')->target_id == 'page') { 
        $json_array['data'][] = array(
          'type' => $node->get('type')->target_id,
          'id' => $node->get('nid')->value,
          'values' => array(
            'title' =>  $node->get('title')->value,
            'content' => $node->get('body')->value,
          ),
        );
        return new JsonResponse($json_array);
      }
      else {
        throw new AccessDeniedHttpException();
      }
    } 
    else {
      throw new AccessDeniedHttpException();
    }

  }
}
