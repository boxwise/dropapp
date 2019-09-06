Cypress.Commands.add("NotificationWithTextIsVisible", notificationText => {
    cy.get("ul[id='noty_topCenter_layout_container']").should(
        "contain",
        notificationText
    );
});

Cypress.Commands.add(
    "MobileNotificationWithTextIsVisible",
    notificationText => {
        cy.get("div[class='message warning']").should(
            "contain",
            notificationText
        );
    }
);