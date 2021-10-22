<?php

namespace idoit\Module\Lfischerrouter\Controller;

use Exception;
use idoit\Component\Helper\Ip;
use idoit\Controller\Base;
use isys_application;
use isys_component_dao_result;
use isys_register;

/**
 * Class Route
 *
 * @package idoit\Module\Lfischerrouter\Controller
 */
class Route extends Base
{
    /**
     * Method to redirect to a object, using it's name.
     *
     * @param isys_register $request
     *
     * @throws \isys_exception_database
     */
    public function openObject(isys_register $request)
    {
        $dao = $this->getDi()->get('cmdb_dao');
        $objectTitle = $request->get('name');

        $statusNormal = $dao->convert_sql_int(C__RECORD_STATUS__NORMAL);
        $values = [
            $dao->convert_sql_text($objectTitle),
            $dao->convert_sql_text($objectTitle . '%'),
            $dao->convert_sql_text('%' . $objectTitle . '%')
        ];

        foreach ($values as $value) {
            $sql = "SELECT isys_obj__id AS id 
                FROM isys_obj 
                WHERE isys_obj__title LIKE {$value}
                AND isys_obj__status = {$statusNormal}
                LIMIT 1;";

            $objectId = $dao->retrieve($sql)
                ->get_row_value('id');

            if ($objectId) {
                header('Location: ' . isys_application::instance()->www_path . 'index.php?' . C__CMDB__GET__OBJECT . '=' . $objectId);
                die;
            }
        }

        header('Location: ' . isys_application::instance()->www_path);
        die;
    }

    /**
     * @param isys_register $request
     *
     * @throws \isys_exception_database
     */
    public function openObjectByIp(isys_register $request)
    {
        $ipAddress = trim($request->get('ip'));

        if (Ip::validate_ipv6($ipAddress)) {
            $result = $this->findByIpv6($ipAddress);
        } else {
            $result = $this->findByIpv4($ipAddress);
        }

        $objectId = $result->get_row_value('id');

        if ($objectId) {
            header('Location: ' . isys_application::instance()->www_path . 'index.php?' . C__CMDB__GET__OBJECT . '=' . $objectId);
            die;
        }

        header('Location: ' . isys_application::instance()->www_path);
        die;
    }

    /**
     * @param string $ipAddress
     *
     * @return isys_component_dao_result
     * @throws \isys_exception_database
     */
    private function findByIpv4(string $ipAddress): isys_component_dao_result
    {
        $dao = $this->getDi()->get('cmdb_dao');
        $type = $dao->convert_sql_id(C__CATS_NET_TYPE__IPV4);
        $ipAddress = $dao->convert_sql_text($ipAddress);
        $statusNormal = $dao->convert_sql_int(C__RECORD_STATUS__NORMAL);

        $sql = "SELECT isys_obj__id AS id 
            FROM isys_catg_ip_list 
            INNER JOIN isys_obj ON isys_obj__id = isys_catg_ip_list__isys_obj__id
            INNER JOIN isys_cats_net_ip_addresses_list ON isys_cats_net_ip_addresses_list__id = isys_catg_ip_list__isys_cats_net_ip_addresses_list__id
            WHERE isys_cats_net_ip_addresses_list__title = {$ipAddress}
            AND isys_catg_ip_list__isys_net_type__id = {$type}
            AND isys_catg_ip_list__primary = 1
            AND isys_cats_net_ip_addresses_list__status = {$statusNormal} 
            AND isys_catg_ip_list__status = {$statusNormal} 
            AND isys_obj__status = {$statusNormal} 
            LIMIT 1;";

        return $dao->retrieve($sql);
    }

    /**
     * @param string $ipAddress
     *
     * @return isys_component_dao_result
     * @throws \isys_exception_database
     */
    private function findByIpv6(string $ipAddress): isys_component_dao_result
    {
        $dao = $this->getDi()->get('cmdb_dao');
        $type = $dao->convert_sql_id(C__CATS_NET_TYPE__IPV6);
        $ipAddressShort = $dao->convert_sql_text(Ip::validate_ipv6($ipAddress, true));
        $ipAddressLong = $dao->convert_sql_text(Ip::validate_ipv6($ipAddress, false));
        $statusNormal = $dao->convert_sql_int(C__RECORD_STATUS__NORMAL);

        $sql = "SELECT isys_obj__id AS id 
            FROM isys_catg_ip_list 
            INNER JOIN isys_obj ON isys_obj__id = isys_catg_ip_list__isys_obj__id
            INNER JOIN isys_cats_net_ip_addresses_list ON isys_cats_net_ip_addresses_list__id = isys_catg_ip_list__isys_cats_net_ip_addresses_list__id
            WHERE (isys_cats_net_ip_addresses_list__title = {$ipAddressShort} OR isys_cats_net_ip_addresses_list__title = {$ipAddressLong})
            AND isys_catg_ip_list__isys_net_type__id = {$type}
            AND isys_catg_ip_list__primary = 1
            AND isys_cats_net_ip_addresses_list__status = {$statusNormal} 
            AND isys_catg_ip_list__status = {$statusNormal} 
            AND isys_obj__status = {$statusNormal} 
            LIMIT 1;";

        return $dao->retrieve($sql);
    }

    /**
     * Method to redirect to a object, using it's inventory number.
     *
     * @param isys_register $request
     *
     * @throws \isys_exception_database
     */
    public function openObjectByInventory(isys_register $request)
    {
        $dao = $this->getDi()->get('cmdb_dao');
        $inventoryNumber = $request->get('inventory');

        $statusNormal = $dao->convert_sql_int(C__RECORD_STATUS__NORMAL);
        $values = [
            $dao->convert_sql_text($inventoryNumber),
            $dao->convert_sql_text($inventoryNumber . '%'),
            $dao->convert_sql_text('%' . $inventoryNumber . '%')
        ];

        foreach ($values as $value) {
            $sql = "SELECT isys_catg_accounting_list__isys_obj__id AS id 
                FROM isys_catg_accounting_list
                INNER JOIN isys_obj ON isys_obj__id = isys_catg_accounting_list__id
                WHERE isys_catg_accounting_list__inventory_no LIKE {$value} 
                AND isys_catg_accounting_list__status = {$statusNormal}
                AND isys_obj__status = {$statusNormal}
                LIMIT 1;";

            $objectId = $dao->retrieve($sql)
                ->get_row_value('id');

            if ($objectId) {
                header('Location: ' . isys_application::instance()->www_path . 'index.php?' . C__CMDB__GET__OBJECT . '=' . $objectId);
                die;
            }
        }

        header('Location: ' . isys_application::instance()->www_path);
        die;
    }

    /**
     * Method to change the tenant.
     *
     * @param isys_register $request
     */
    public function changeTenant(isys_register $request)
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
