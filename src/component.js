/**
 * WordPress Dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { compose } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';
import { PluginPostStatusInfo } from '@wordpress/editPost';
import { ToggleControl } from '@wordpress/components';

function HideTitleComponent({ hideTitle, onUpdate }) {
  return (
    <Fragment>
      <PluginPostStatusInfo className="edit-post-hidetitle">
        <ToggleControl
          key="togglecontrol"
          label={ __('Hide title', 'wp-gutenberg-hidetitle') }
          checked={ !!hideTitle }
          onChange={ () => onUpdate(!hideTitle) }
        />
        { !!hideTitle && (
          <p><em><small>{ __('Remember to add a H1 manually to rank well in search engines.', 'wp-gutenberg-hidetitle') }</small></em></p>
        ) }
      </PluginPostStatusInfo>
    </Fragment>
  );
}

export default compose([
  withSelect((select) => {
    return {
      hideTitle: select('core/editor').getEditedPostAttribute('meta').hide_title,
    };
  }),
  withDispatch((dispatch) => ({
    onUpdate(hideTitle) {
      dispatch('core/editor').editPost({ meta: { hide_title: hideTitle } } );
    },
  })),
])(HideTitleComponent);
