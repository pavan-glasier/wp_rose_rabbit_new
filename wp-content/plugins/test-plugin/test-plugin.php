<?php
/**
* Plugin Name: Gutenberg Notice Block
* Author: Shaiful
* Description: A notice box with a few predefined styles that accepts arbitrary text input.
* Version: 1.0
*/
// Load assets for wp-admin when editor is active

function supporthost_block_01_register_block() {
  register_block_type( __DIR__ . '/block.json' );
}

add_action( 'init', 'supporthost_block_01_register_block' );
