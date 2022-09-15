import { getLoginConfiguration } from '../../config';

describe('Redirect domain', () => {
    let config = getLoginConfiguration();

    it('Navigate to staging.boxwise.co and end up at staging.boxtribute.org', () => {
        cy.request({
            url: 'https://staging.boxwise.co/?action=stock',
            followRedirect: false, // turn off following redirects
          }).then((resp) => {
            expect(resp.status).to.eq(301)
            expect(resp.redirectedToUrl).to.include('https://staging.boxtribute.org/?action=stock');
          })
    });

    it('Navigate to staging.boxwise.co and end up at staging.boxtribute.org', () => {
        cy.request({
            url: 'https://staging.boxwise.co/mobile.php?barcode=test',
            followRedirect: false, // turn off following redirects
          }).then((resp) => {
            expect(resp.status).to.eq(301)
            expect(resp.redirectedToUrl).to.include('https://staging.boxtribute.org/mobile.php?barcode=test');
          })
    });

    it('Navigate to market.drapenihavet.no and end up at app.boxtribute.org', () => {
        cy.request({
            url: 'https://market.drapenihavet.no/mobile.php?barcode=test',
            followRedirect: false, // turn off following redirects
          }).then((resp) => {
            expect(resp.status).to.eq(301)
            expect(resp.redirectedToUrl).to.include('app.boxtribute.org');
          })
    });
});