import { getLoginConfiguration } from '../../config';

const SAME_ORG_BOX_QR_URL = "093f65e080a295f8076b1c5722a46aa2";

describe('QR Code Scanning and Box Management', () => {
    function createQrCode(){
        cy.typeNumberOfLabels(1);
        cy.uncheckBigLabelsCheckbox();
        cy.clickMakeLabelsButton();
    }

    function getQrCode(){
        return cy.get("div[data-testid='boxlabel-small'] img");
    }

    function getHash(){
        return cy.get("a[data-testid='qr-link']").then(($lnk) =>{
            return $lnk[0].href.split("=")[1];
        });
    }

    it('QR code that is not associated with a box is scanned with external QR Scanner ', () => {
        cy.interceptCallsToV2();
        cy.loginAsVolunteer();
        cy.visit('/?action=qr');
        createQrCode();
        getHash().then($hash => {
            cy.log('hash',$hash);
            getQrCode().then($qr => {
                cy.viewport('iphone-6');
                cy.log('qr',$qr);
                $qr.click();
                cy.waitforCallToV2('/qrreader/' + $hash);
            });
        });
    });

    it('QR code that is associated with a box is scanned with external QR Scanner', () => {
        cy.viewport('iphone-6');
        cy.request({
            url: '/mobile.php?barcode=' + SAME_ORG_BOX_QR_URL,
            followRedirect: false, // turn off following redirects
          }).then((resp) => {
            expect(resp.status).to.eq(301)
            expect(resp.redirectedToUrl).to.include('/qrreader/' + SAME_ORG_BOX_QR_URL);
          })
        
    });
});
