import './session'
import './notifications'
import './select2-interaction-methods'

Cypress.on('uncaught:exception', (err, runnable) => {
    // returning false here prevents Cypress from
    // failing the test
    return false;
})
