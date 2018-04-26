<?php

/**
 * Plugin Name: Fade Slider
 * Description: This is simple and clean bootstrap slider. It have option to choose slider animation Slide (or) Fade and other admin controls. Now its also works not bootstrap themes
 * Version: 2.1
 * Author: Anandaraj Balu
 * Text Domain: fadeslider
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
*/

/*  Copyright 2014-2017 Anandaraj B (email: anandrajbalu00 at gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'admin_menu', 'remove_menu_items' );
function remove_menu_items() {
    if ( ! current_user_can( 'administrator' ) ) {
        remove_menu_page( 'edit.php?post_type=fade_slider' );
    }
}

include('admin/fadeslider_admin.php');
include('public/fadeslider_public.php');
