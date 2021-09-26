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

We are going for Auth0 since 
- we have prior experience with Auth0 in the team, 
- a first test in an afternoon coding session were satisfying and
- Auth0 offers a free plan for Open-source projects.
- Auth0 is one of the product leaders in CIAM (Visit Gartner for detail at [https://www.g2.com/categories/customer-identity-and-access-management-ciam#grid] - Read recent Leadership Compass Identity API Platforms By KuppingerCole [https://static.carahsoft.com/concrete/files/6315/6840/5267/kuppingercole-leadership-compass-identity-api-platforms.pdf])
- Using Auth0 management API and automatic migration and bulk import it can be integrated to PHP app
- Auth0 offers full featured authentication and authorization solution and it's capable of customized rules and flows.
- Auth0 has good documentation and active community support (https://auth0.com/docs - https://community.auth0.com/)
- Auth0 and Google Firebase comparison in detail [https://docs.google.com/document/d/1sWmS_VDHDx3VDX7JFu78fpU8yMF9eUOHvp2PqJ9_dy0/edit?usp=sharing]

- We are not building our own authentication solution to reduce the security risks coming with handling of passwords.

## Consequences

### Easier:
- We can almost drop a whole user flow

### More difficult:

- Switching between mobile and desktop application

- Reaching the total users (10K) or API rate limits, and how they impact pricing 

- Changes to the current Auth0 enterprise agreement for open source project




