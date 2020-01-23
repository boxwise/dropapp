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
