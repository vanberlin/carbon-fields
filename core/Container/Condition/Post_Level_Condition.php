<?php

namespace Carbon_Fields\Container\Condition;

use Carbon_Fields\App;

/**
 * Check if a post is on a specific hierarchy level
 * 
 * Level 1 is considered the root level. Passed values have a forced minimum value of 1.
 */
class Post_Level_Condition extends Condition {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_comparers( array( 
			App::resolve( 'container_condition_comparer_type_equality' ),
			App::resolve( 'container_condition_comparer_type_contain' ),
			App::resolve( 'container_condition_comparer_type_scalar' ),
			App::resolve( 'container_condition_comparer_type_regex' ),
			App::resolve( 'container_condition_comparer_type_custom' ),
		) );
	}
	
	/**
	 * Check if the condition is fulfilled
	 * 
	 * @param  array $environment
	 * @return bool
	 */
	public function is_fulfilled( $environment ) {
		$post_id = $environment['post_id'];
		$post_level = count( get_post_ancestors( $post_id ) ) + 1;
		$value = max( 1, intval( $this->get_value() ) );

		return $this->first_supported_comparer_is_correct(
			$post_level,
			$this->get_comparison_operator(),
			$value
		);
	}
}