/* Helper functions to wrap interactions with boxwise-custom select dropdown DOM element */
/* field_id is a unique identifier in a form this dropdown is used to select values for */

Cypress.Commands.add("selectOptionByText", (field_id, optionText) => {
    cy.get("div[id='s2id_field_" + field_id + "']").click();
    cy.get("ul[class='select2-results'] li").contains(optionText).click();
});

Cypress.Commands.add("getSelectedValueInDropDown", (field_id, text) => {
    cy.get("div[id='s2id_field_" + field_id + "']")
    .children()
    .find("span");
});

Cypress.Commands.add("CheckDropDownEmpty",(field_id) => {
    cy.get("div[id='s2id_field_" + field_id + "']")
    .children()
    .find("span").should("contain","Please select");
});

Cypress.Commands.add("selectForFieldExists", (field_id) => {
    cy.get("div[id='s2id_field_" + field_id + "']").should('exist');
});

Cypress.Commands.add("selectForFieldExists", (field_id) => {
    cy.get("div[id='s2id_field_" + field_id + "']").should('exist');
});

Cypress.Commands.add("checkOptionsCount", (field_id, count) => {
    cy.get("div[id='s2id_field_" + field_id + "']").next().within(()=>{
        cy.get("option").then($options => {
            //there's one empty option when nothing is selected
            expect($options.length).to.equal(count+1);
        })
    })
});