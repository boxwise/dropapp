Cypress.Commands.add("notificationWithTextIsVisible", notificationText => {
    cy.get("ul[id='noty_topCenter_layout_container']").should(
        "contain",
        notificationText
    );
});

Cypress.Commands.add(
    "mobileNotificationWithTextIsVisible",
    notificationText => {
        cy.get("div[class='message warning']").should(
            "contain",
            notificationText
        );
    }
);