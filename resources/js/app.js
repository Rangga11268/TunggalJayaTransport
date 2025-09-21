import './bootstrap';

import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';

window.Alpine = Alpine;

// Register the collapse plugin
Alpine.plugin(Collapse);

Alpine.start();
