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
- Good docs & Supports
- Comparison Auth0 and Google Firebase 

## Decision

We are going for Auth0 since 
- we have prior experience with Auth0 in the team, 
- a first test in an afternoon coding session were satisfying and
- Auth0 offers a free plan for Open-source projects.
- Auth0 is one of the product leaders in CIAM (Visit Gartner for detail at [https://www.g2.com/categories/customer-identity-and-access-management-ciam#grid] - Read recent Leadership Compass Identity API Platforms By KuppingerCole [https://static.carahsoft.com/concrete/files/6315/6840/5267/kuppingercole-leadership-compass-identity-api-platforms.pdf])
- Using Auth0 management API and automatic migration and bulk import it can be integrated to PHP app
- Auth0 offers full featured authentication and authorization solution and it's capable of customized rules and flows.

- We are not building our own authentication solution to reduce the security risks coming with handling of passwords.

## Consequences

### Easier:
- We can almost drop a whole user flow

### More difficult:

- Switching between mobile and desktop application

- Becomes more expensive as either number of users grow more than 10K compare to [Firebase which free for 10K users verfications](https://firebase.google.com/pricing#blaze-calculator) or changes to the current enterprise agreement for open source project (Free) [pricing](https://auth0.com/pricing)

- Reaching to the current [API rate limits](https://auth0.com/docs/support/policies/rate-limit-policy/management-api-endpoint-rate-limits) (*2 requests per seconds with bursts up to 10 requests, GET Token: 50 per minute with bursts up to 500 requests*) cause to get 429 error message