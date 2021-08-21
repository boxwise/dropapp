Cypress.Commands.add("testdbdelete", (table, ids, emails = null) => {
    cy.request({
        method: "POST",
        url: "/ajax.php?file=testdbdelete",
        body: {
            table: table,
            ids: ids,
            emails: emails
        },
        form: true
    }).then(response => {
        expect(response.status).to.eq(200);
        expect(response.body).to.contain("true");
    });
});

Cypress.Commands.add("testauth0user", (email) => {
    cy.request({
        method: "POST",
        url: "/ajax.php?file=testauth0user",
        body: {
            email: email
        },
        form: true
    }).then(response => {
        expect(response.status).to.eq(200);
        expect(response.body).to.contain("true");
    });
});
