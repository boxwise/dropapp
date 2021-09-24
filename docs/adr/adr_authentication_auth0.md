# Authentication with Auth0

Trello-card: https://trello.com/c/8KEgl3nv

## Status

accepted, implementation in progress

## Context or Problem Statement

Our handling of user data is far from bullet-proof. We have stumbled upon cases where SQL injection is possible and the current password encryption is only md5. Additionally, we are building a new mobile app in React and Flask and need to implement a way to handle authentication there.

## Decision Drivers

1. Security / Risk for us
2. Ease of Use
3. Cost

## Considered Options

- Building our own authentication solution
- Google Firebase
- Auth0

## Decision

### CIAM Providers

**Auth0 / Firebase / Okta / OneLogin / ...**

Comparison of Auth0 vs. Google Firebase

| | **Auth0** | **Firebase** |

| --- | --- | --- |

| **Free ($)** | Its free for 10K and gets expensive as number of users grow. Our current subscription is _Enterprise Agreement_ | Firebase Authentication is free on a Firebase Spark plan, for up to 10k successful verifications of app users each month.|

| **Reliability and Security** | According to recent [Garter](https://www.g2.com/categories/customer-identity-and-access-management-ciam#grid) reports, its one of leader in identity management | Backed by Google and have huge community |

| **Ease of Adaptation from Dev Perspective** | Full featured authentication and authorization solution. Capable of customized rules and flows.Good documentation and supportOne of the leaders in CIAM and can be used in different situation for simple and complex authentication and authorization | Simple authentication service that supports multi factor authentication with various sign-in provider as well as user/password, for complex situation google have another solution called Identity platform Check this:https://cloud.google.com/identity-platform/docs/product-comparison |

| **Ease of Integration to Legacy App** | Using management API and automatic migration and bulk import | Simple and easy but limited to authentication |

  

**We are going for Auth0 since:**

- We have prior experience with Auth0 in the team

- Auth0 offers a free plan for Open-source projects.

- Auth0 is one of the market leaders on the CAIM market according to Gartner*, and its adapted to many companies, which makes it a good choice for us, since we want a reliable authentication source of trust.


(*) https://www.casque.co.uk/wp-content/uploads/2019/11/KC-Identity-Platforms.pdf](https://www.casque.co.uk/wp-content/uploads/2019/11/KC-Identity-Platforms.pdf

We are not building our own authentication solution to reduce the security risks coming with handling g passwords.

## Consequences

### Easier:

We can almost drop a whole user flow.

### More difficult:

Switching between mobile and desktop application.