/**
 * WordPress Dependencies
 */
import { registerPlugin } from '@wordpress/plugins';

import HideTitleComponent from './component';

registerPlugin('generoi-hidetitle', {
  icon: 'hidden',
  render: HideTitleComponent,
});
