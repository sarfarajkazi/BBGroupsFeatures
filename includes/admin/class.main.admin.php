<?php

/**
 * BBGF Admin
 *
 * Manage Actions
 *
 * @since 1.0.0
 */
class BBGF_ADMIN
{

    /**
     * Initial class
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'bbgf_admin_scripts'));
    }

    /**
     * Declare scripts and styles for admin
     *
     * @return void
     * @since 1.0.0
     *
     */
    public function bbgf_admin_scripts()
    {

    }


}
