function AjaxAttack(userMail, userPassword) {
    cy.request({
        method: "POST",
        url: '/?action=cms_users_edit&origin=cms_users&id=100000002',
        body: {
            id: "100000001",
            cms_usergroups_id: "100000002",
            origin: "cms_users",
            email: "coordinator@coordinator.co",
            naam: "BrowserTestUser_Coordinator"
            
        },
        form: true
    }).then(response => {
        expect(response.status).to.eq(200);
        cy.log(response.message);
        //expect(response.message).to.be.empty;
        //expect(response.success).to.be.true;

    });
};
function AjaxAttack2(userMail, userPassword) {
    cy.request({
        method: "POST",
        url: '/?action=cms_users_edit&origin=cms_users&id=100000002',
        body: {
            id: "100000002",
            cms_usergroups_id: "100000002",
            origin: "cms_users",
            email: "user@user.co",
            naam: "BrowserTestUser_User"          
        },
        form: true
    }).then(response => {
        expect(response.status).to.eq(200);
        cy.log(response.message);
        //expect(response.message).to.be.empty;
        //expect(response.success).to.be.true;

    });
};

describe('AttackAjax', () => {

    beforeEach(() => {
        cy.setupAjaxActionHook();
        cy.loginAsCoordinator();
    });

it("Make self admin", ()=>{
    AjaxAttack();
    AjaxAttack2();
})});