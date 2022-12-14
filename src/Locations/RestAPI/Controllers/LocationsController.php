<?php

/**
 * Controller which handles the (requested) pdc-item(s).
 */

namespace OWC\PDC\Locations\RestAPI\Controllers;

use OWC\PDC\Base\RestAPI\Controllers\BaseController;
use OWC\PDC\Locations\Models\Location;
use WP_Error;
use WP_REST_Request;

/**
 * Controller which handles the (requested) pdc-item(s).
 */
class LocationsController extends BaseController
{

    /**
     * Get a list of all items.
     *
     * @param WP_REST_Request $request
     *
     * @return array
     */
    public function getItems(WP_REST_Request $request)
    {
        $orderBy   = $request->get_param('orderby') ?? 'title';
        $order     = $request->get_param('order') ?? 'ASC';
        $locations = (new Location($this->plugin))->query([
            'order'   => $order,
            'orderby' => $orderBy,
        ]);
        $data  = $locations->all();
        $query = $locations->getQuery();

        return $this->addPaginator($data, $query);
    }

    /**
     * Get an individual post item.
     *
     * @param WP_REST_Request $request
     *
     * @return array|WP_Error
     */
    public function getItem(WP_REST_Request $request)
    {
        $id = (int) $request->get_param('id');

        $location = (new Location($this->plugin))
            ->find($id);

        if (!$location) {
            return new WP_Error('no_item_found', sprintf('Item with ID "%d" not found', $id), [
                'status' => 404,
            ]);
        }

        return $location;
    }
}
