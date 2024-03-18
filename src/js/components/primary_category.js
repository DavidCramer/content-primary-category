/**
 * WordPress dependencies.
 */
import { withSelect, withDispatch } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { store as editorStore } from '@wordpress/editor';
import { SelectControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

class PrimaryCategory extends Component {

	render() {

		const { terms, editPost, primaryCat, deselected } = this.props;

		const setPrimary = ( value ) => {
			return editPost(
				{
					meta: {
						_content_primary_category: parseInt( value )
					}
				}
			);
		};

		// Be sure to set the primary to 0, if the primary has been removed
		// from the selected terms.
		if ( deselected ) {
			setPrimary( 0 );
		}

		return (
			<>
				<hr/>
				<SelectControl
					label={ __(
						'Primary Category',
						'content-primary-category'
					) }
					help={ __(
						'Select a primary category.',
						'content-primary-category'
					) }
					options={ [
						{
							label: __(
								'None',
								'content-primary-category'
							),
							value: '',
						},
						...terms,
					] }
					value={ primaryCat }
					onChange={ setPrimary }
				/>
			</>
		);
	}
}

export default compose(
	[
		withSelect( ( select, props ) => {

			const { slug } = props;
			const { getTaxonomy, getEntityRecords } = select( coreStore );
			const { getEditedPostAttribute } = select( editorStore );

			const taxonomy = getTaxonomy( slug );
			const terms = getEditedPostAttribute( taxonomy.rest_base );
			const postMeta = getEditedPostAttribute( 'meta' );
			const primaryCat = postMeta ? postMeta._content_primary_category : 0;

			const queryArgs = {
				_fields: [
					'id',
					'name'
				]
			};
			const allTerms = getEntityRecords(
				'taxonomy',
				slug,
				queryArgs
			);
			const selectedTerms = allTerms ? [ ...allTerms ].filter( ( term ) => {
				return -1 !== terms.indexOf( term.id );
			} ) : [];

			return {
				terms: [ ...selectedTerms ].map( ( term ) => {
					return {
						label: term.name,
						value: term.id
					};
				} ),
				primaryCat,
				deselected: 0 < primaryCat && -1 === terms.indexOf( primaryCat )
			};
		} ),
		withDispatch( ( dispatch ) => {
			return dispatch( editorStore );
		} )
	]
)( PrimaryCategory );
