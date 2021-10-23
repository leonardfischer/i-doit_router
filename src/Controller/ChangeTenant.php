<?php

namespace idoit\Module\Lfischerrouter\Controller;

use Exception;
use idoit\Controller\Base;
use isys_application;
use isys_register;

/**
 * Class Route
 *
 * @package idoit\Module\Lfischerrouter\Controller
 */
class ChangeTenant extends Base
{
    /**
     * Method to change the tenant.
     *
     * @param isys_register $request
     */
    public function byTenantId(isys_register $request)
    {
        $tenantId = $request->get('id');
        $parameters = (array)$request->get('GET');

        try {
            $session = isys_application::instance()->container->get('session');
        } catch (Exception $e) {
            global $g_comp_session;

            $session = $g_comp_session;
        }

        if ($tenantId && $session->is_logged_in()) {
            try {
                $location = '';
                $session->change_mandator($tenantId);

                // If the "open-object" parameter is supplied, redirect to the "open-object/xyz" page.
                if (isset($parameters['open-object']) && !empty($parameters['open-object'])) {
                    $location = 'open-object/' . $parameters['open-object'];
                }

                // If the "open-object" parameter is supplied, redirect to the "open-object/xyz" page.
                if (isset($parameters['open-object-id']) && is_numeric($parameters['open-object-id'])) {
                    $location = 'index.php?' . C__CMDB__GET__OBJECT . '=' . $parameters['open-object-id'];
                }

                header('Location: ' . isys_application::instance()->www_path . $location);
                die;
            } catch (Exception $e) {
                // Nothing to do here.
            }
        }

        header('Location: ' . isys_application::instance()->www_path);
        die;
    }
}
