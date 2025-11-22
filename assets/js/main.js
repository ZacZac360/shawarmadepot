// main.js (ES module entry point)

// Global UI (hero slider, reels, back-to-top, reveal-on-scroll)
import './app-init.js';

// Shared cart + sidebar cart
import './cart.js';

// Delivery / pickup details on menu (and any page that has that box)
import './order-details.js';

// Variant modal for menu (if page has .js-open-variants buttons)
import './variant-modal.js';

// Checkout logic (OTP + summary). On non-checkout pages it just finds nothing and does nothing.
import './checkout.js';
