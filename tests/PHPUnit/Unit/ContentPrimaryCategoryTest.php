<?php

namespace David_Cramer\Content_Primary_Category;

use WP_Mock;

class ContentPrimaryCategoryTest extends AbstractUnitTestcase {

	public function testGetInstance() {

		$instance = Content_Primary_Category::get_instance();
		$is       = $instance instanceof Content_Primary_Category;
		static::assertTrue( $is, 1 );
	}

}
