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

