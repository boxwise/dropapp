import { getLoginConfiguration } from '../config';

const PRODUCT1 = "Diapers";

const LOCATION3 = "TestDonated";

const SAME_ORG_BOX_QR_URL = "093f65e080a295f8076b1c5722a46aa2";
const SAME_ORG_BOX_CONTENT = "Shampoo";
const SAME_ORG_BOX_SIZE = "One size";
const SAME_ORG_BOX_LOCATION = LOCATION3;
const SAME_ORG_BOX_COUNT = "50";

const SAME_ORG_QR_URL_WITHOUT_BOX = "b1cf83ae73adfce0d14dbe81b53cb96b";

const DIFFERENT_ORG_QR_URL = "44f683a84163b3523afe57c2e008bc8c";
const DIFFERENT_ORG_QR_URL_WITHOUT_BOX = "4b382363fa161c111fa9ad2b335ceacd";

const NON_EXISTENT_QR_ULR = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";

function checkBoxContent(product, size, location, count){
    cy.get("div[data-testid='box-info']").should('be.visible');
    cy.get("div[data-testid='box-info-product']").should('contain', count + 'x ' + product);
    cy.get("div[data-testid='box-info-size']").should('contain', size);
    cy.get("a[class='btn disabled']").should('contain', location);
    cy.get("div[data-testid='moveBoxDiv']").should('contain', 'Move this box from ' + location);   
}

function createBoxFormIsVisible(){
    cy.selectForFieldExists("product_id");
    cy.get("select[data-testid='size_id']").should('be.visible');
    cy.get("select[data-testid='location_id']").should('be.visible');
    cy.get("input[data-testid='items_count']").should('be.visible');
    cy.get("input[data-testid='submit_new_box']").should('be.visible');
}

describe('Mobile box creation using QR scanning (logged-in user)', () => {
    beforeEach(() => {
      cy.loginAsVolunteer();
      cy.visit('/?action=qr');
      createQrCode();
    });

    function typeNumberOfLabels(number){
        cy.get("input[id='field_count']").clear().type(number);
    }

    function uncheckBigLabelsCheckbox(){
        cy.get("input[data-testid='field_fulllabel']").uncheck();
    }

    function clickMakeLabelsButton(){
        cy.get("button").contains("Make labels").click();
    }

    function createQrCode(){
        typeNumberOfLabels(1);
        uncheckBigLabelsCheckbox();
        clickMakeLabelsButton();
    }

    function getQrCode(){
        return cy.get("div[data-testid='boxlabel-small'] img");
    }

    function selectProduct(product){
        cy.selectOptionByText("product_id", product);
    }

    function selectSize(size){
        cy.get("select[data-testid='size_id']").select(size);
    }

    function selectLocation(location){
        cy.get("select[data-testid='location_id']").select(location);
    }

    function defineItemsCount(count){
        cy.get("input[data-testid='items_count']").clear().type(count);
    }

    function clickNewBoxButton(){
        cy.get("input[data-testid='submit_new_box']").click();
    }


    function checkRequiredFieldsErrors(){
        cy.get("label[id='field_product_id-error']").should('be.visible');
        cy.get("label[id='field_size_id-error']").should('be.visible');
        cy.get("label[id='location_id-error']").should('be.visible');
        cy.get("label[id='items-error']").should('be.visible');
        cy.get("label[class='error']").then($errors => {
            expect($errors.length).to.equal(4);
        })
    }

    it('Create box with data', () => {
        getQrCode().then($qr => {
            cy.viewport('iphone-6');
            let qrUrl = $qr[0].src.split('data=https://')[1];
            cy.visit(qrUrl);
            createBoxFormIsVisible();
            // filling out new box form
            let itemsCount = 100;
            selectProduct(PRODUCT1);
            selectSize("7-12 months");
            selectLocation(LOCATION3);
            defineItemsCount(itemsCount);
            clickNewBoxButton();
            // assertions
            cy.mobileNotificationWithTextIsVisible('contains ' + itemsCount + ' ' + PRODUCT1);
            cy.mobileNotificationWithTextIsVisible('located in ' + LOCATION3);
            checkBoxContent(PRODUCT1, "7-12 months", LOCATION3, itemsCount);
        });
    });

    it('Prevent box creation without data', () => {
        getQrCode().then($qr => {
            cy.viewport('iphone-6');
            let qrUrl = $qr[0].src.split('data=https://')[1];
            cy.visit(qrUrl);
            clickNewBoxButton();
            checkRequiredFieldsErrors()
        })
    });
});

describe('Mobile box creation using QR scanning (logged-out user)', () => {
    let config = getLoginConfiguration();

    function fillLoginForm(){
        cy.get("input[data-testid='email']").type(config.testVolunteer);
        cy.get("input[data-testid='password']").type(config.testPwd);
        cy.get("input[data-testid='signInButton']").click();
    }

    it('Scan QR code with associated box (same organisation)', () => {
        cy.visit('/mobile.php?barcode=' + SAME_ORG_BOX_QR_URL);
        fillLoginForm(); 
        checkBoxContent(SAME_ORG_BOX_CONTENT, SAME_ORG_BOX_SIZE, SAME_ORG_BOX_LOCATION, SAME_ORG_BOX_COUNT) ;
    });

    it('Scan QR code without associated box (same organisation)', () => {
        cy.visit('/mobile.php?barcode=' + SAME_ORG_QR_URL_WITHOUT_BOX);
        fillLoginForm(); 
        createBoxFormIsVisible();
    });

    it('Scan QR code with associated box (diff organisation)', () => {
        cy.visit('/mobile.php?barcode=' + DIFFERENT_ORG_QR_URL);
        fillLoginForm(); 
        cy.mobileWarningNotificationWithTextIsVisible("Oops!! This box is registered in");
    });

    // TEST IMPLEMENTED BUT THE APP BEHAVIOR DOESN'T MIRROR IT
    // it('Scan QR code without associated box (diff organisation)', () => {
    //     cy.visit('/mobile.php?barcode=' + DIFFERENT_ORG_QR_URL_WITHOUT_BOX);
    //     fillLoginForm(); 
    //     cy.mobileWarningNotificationWithTextIsVisible("This is not a valid QR-code for " + config.orgName);
    // });

    it('Scan non-existent QR code (diff organisation)', () => {
        cy.visit('/mobile.php?barcode=' + NON_EXISTENT_QR_ULR);
        fillLoginForm(); 
        cy.mobileWarningNotificationWithTextIsVisible("This is not a valid QR-code for " + config.orgName);
    });    
});