/**
 * WordPress dependencies.
 */
import { addFilter } from '@wordpress/hooks';

/**
 * Internal dependencies.
 */
import PrimaryCategory from './components/primary_category';

/**
 * Original Component
 * @param OriginalComponent
 * @return {function(*)}
 * @constructor
 */
const TaxonomyPrimarySelector = ( OriginalComponent ) => {
	return ( props ) => {
		const { slug } = props;
		return (
			<>
				<OriginalComponent { ...props } />
				{ slug && 'category' === slug && (
					<PrimaryCategory { ...props } />
				) }
			</>
		);

	};
};

addFilter(
	'editor.PostTaxonomyType',
	'david-cramer/content-primary-category',
	TaxonomyPrimarySelector
);
