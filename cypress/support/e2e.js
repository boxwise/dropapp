import './session'
import './notifications'
import './menu-navigation'
import './select2-interaction-methods'
import './dom-elements-interaction'
import './user-helpers'
import './database'
import './box-helper-functions'
import './box-or-beneficiary-creation'
import './ajax'

Cypress.on('uncaught:exception', (err, runnable) => {
    // returning false here prevents Cypress from
    // failing the test
    return false;
});

