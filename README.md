# Content Primary Category

Content Primary Category is a simple plugin that allows publishers to specify a
Primary Category to a post or custom content type.

## Installation

### Install the plugin from within WordPress

1. Visit the Plugins page from your WordPress dashboard and click "Add New
   Plugin" at the top of the page.
2. Click "Upload Plugin" at the top of the page.
3. Click "Choose file", and select the plugin .zip package.
4. After it's installed, click "Activate" to activate the plugin on your site.

## Using the Plugin

This plugin is only compatible with the Block editor. Edit a post. On the right
hand settings panel, expand "Categories".
Select the necessary categories for the post. Just below, you'll see a "Primary
Category" select box. Listed will be all the selected categories. Select the
category to set as your primary, and save the post.

## Primary Category archive page

This plugin exposes a "Primary Category" archive page.
It can be accessed in by navigating to `/primary-{category_base}/{term_slug}/`
i.e `/primary-category/pressing/`

## Implementation and Custom query examples

Most instances, the primary category will be queried via custom code.
The primary category is set using post meta data. To query post's primary
category, will require a meta query, or `meta_key` parameter.

The plugin adds two meta entries when a primary category is set.

1. Meta key `primary_category` with the term ID as its value.
2. Meta key `_cpc_{term_slug}` with the term ID as its value.

It's much faster querying for the existance of a meta key, as the metta_key
column is indexed.

```php
// Meta Key example.
$args = [
	'post_type'      => 'post',
	'posts_per_page' => 10,
	'meta_key'       => '_cpc_{term_slug}',
];
$query = new WP_Query( $args );

// Meta query example.
$args = [
	'post_type'      => 'post',
	'posts_per_page' => 10,
	'meta_query'     => [
		[
			'key'     => '_cpc_{term_slug}',
			'compare' => 'EXISTS',
		],
	],
];
$query = new WP_Query( $args );
```

These two examples are if the term_slug is known. If the term ID is only known,
it would generally be better to use `get_term()` then use the slug. However,
it's possible, but not advised, to use a value meta query.

```php

// Meta Key example.
$args = [
	'post_type'      => 'post',
	'posts_per_page' => 10,
	'meta_key'       => 'primary_category',
	'meta_value'     => {term_id}
];
$query = new WP_Query( $args );

//Example with meta_query
$args = [
	'post_type'      => 'post',
	'posts_per_page' => 10,
	'meta_query'     => [
		[
			'key'     => 'primary_category',
			'value'   => {term_id}
		],
	],
];
```

### Unit tests

Due to time constraints, I did not include unit tests. I did however setup the
unit tests structure with a single passing test.
Tests have been setup to run in isolation.

The unit tests are included as part of the composer package.
In the root of the plugin, run `composer install`
Once installed, you'll be able to run the tests as such:

```shell
composer run test
```

### PHPCS

This plugin is written to
use [10up PHPCS Configuration](https://github.com/10up/phpcs-composer)
To run code linting, after `composer install`, you can run PHPCS wit the
following:

```shell
composer run lint
```

## Assets

This plugin comes with the built assets.
The source files are included for viewing or rebuilding.
