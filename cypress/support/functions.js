Cypress.Commands.add("getAdminUser", () => {
    return {
        testAdmin: Cypress.env("testAdmin"),
        testPwd: Cypress.env("testPwd")
    };
});

Cypress.Commands.add("getCoordinatorUser", () => {
    return {
        testCoordinator: Cypress.env("testCoordinator"),
        testPwd: Cypress.env("testPwd")
    };
});

Cypress.Commands.add("getVolunteerUser", () => {
    return {
        testUser: Cypress.env("testUser"),
        testPwd: Cypress.env("testPwd")
    };
});

Cypress.Commands.add("getLoginScenarioUsers", () => {
    return {
        testExpiredUser: Cypress.env("testExpiredUser"),
        testNotActivatedUser: Cypress.env("testNotActivatedUser"),
        testDeletedUser: Cypress.env("testDeletedUser"),
        testPwd: Cypress.env("testPwd"),
        testWrongPwd: Cypress.env("testWrongPwd")
    };
});

Cypress.Commands.add("getLoginNotifications", () => {
    return {
        incorrectLoginNotif: Cypress.env("incorrectLoginNotif"),
        genericErrLoginNotif: Cypress.env("genericErrLoginNotif"),
        successPwdChangeNotif: Cypress.env("successPwdChangeNotif")
    };
});

Cypress.Commands.add("getTestOrgName", () => {
    return { domain: Cypress.env("orgName") };
});

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
