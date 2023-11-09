import { getLoginConfiguration } from '../../config';

describe('Redirects', () => {
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

    it('Navigate to staging.boxwise.co/mobile.php and end up at staging.boxtribute.org', () => {
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

    it('Navigate to mobile.php and end up at v2', () => {
      cy.request({
          url: 'https://staging.boxtribute.org/mobile.php',
          followRedirect: false, // turn off following redirects
        }).then((resp) => {
          expect(resp.status).to.eq(301)
          expect(resp.redirectedToUrl).to.include('v2-staging.boxtribute.org/qrreader');
        })
    });

    it('Navigate to mobile.php?barcode=test and end up at v2', () => {
      cy.request({
          url: 'https://staging.boxtribute.org/mobile.php?barcode=test',
          followRedirect: false, // turn off following redirects
        }).then((resp) => {
          expect(resp.status).to.eq(301)
          expect(resp.redirectedToUrl).to.include('v2-staging.boxtribute.org/qrreader/test');
        })
    });

    it('Navigate to mobile.php?boxid=test and end up at v2', () => {
      cy.request({
          url: 'https://staging.boxtribute.org/mobile.php?boxid=test',
          followRedirect: false, // turn off following redirects
        }).then((resp) => {
          expect(resp.status).to.eq(301)
          expect(resp.redirectedToUrl).to.include('v2-staging.boxtribute.org/boxes/test');
        })
    });

    it('Navigate to mobile.php?newbox=test and end up at v2', () => {
      cy.request({
          url: 'https://staging.boxtribute.org/mobile.php?newbox=test',
          followRedirect: false, // turn off following redirects
        }).then((resp) => {
          expect(resp.status).to.eq(301)
          expect(resp.redirectedToUrl).to.include('v2-staging.boxtribute.org/boxes/create/test');
        })
    });
});