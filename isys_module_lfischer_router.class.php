<?php

/**
 * Class isys_module_router
 */
class isys_module_lfischer_router extends isys_module
{
    // Define, if this module shall be displayed in the named menus.
    const DISPLAY_IN_MAIN_MENU   = false;
    const DISPLAY_IN_SYSTEM_MENU = false;
    const MAIN_MENU_REWRITE_LINK = false;

    /**
     * @var bool
     */
    protected static $m_licenced = true;

    /**
     * Initializes the module.
     *
     * @param isys_module_request $request
     */
    public function init(isys_module_request $request)
    {
    }
}
