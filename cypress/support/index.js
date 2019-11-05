import './session'
import './notifications'
import './menu-navigation'
import './select2-interaction-methods'
import './database'

Cypress.on('uncaught:exception', (err, runnable) => {
    // returning false here prevents Cypress from
    // failing the test
    return false;
})
