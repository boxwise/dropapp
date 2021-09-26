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
- [Comparison Auth0 and Google Firebase](https://docs.google.com/document/d/1sWmS_VDHDx3VDX7JFu78fpU8yMF9eUOHvp2PqJ9_dy0/edit?usp=sharing)

## Decision

We are going for Auth0 since

- We have prior experience with Auth0 in the team

- a first test in an afternoon coding session were satisfying

- Auth0 offers a free plan for Open-source projects

- Auth0 is one of the product leaders in CIAM (Visit Gartner for detail at [https://www.g2.com/categories/customer-identity-and-access-management-ciam#grid] - Read recent Leadership Compass Identity API Platforms By KuppingerCole [https://static.carahsoft.com/concrete/files/6315/6840/5267/kuppingercole-leadership-compass-identity-api-platforms.pdf])

- Using Auth0 management API and automatic migration and bulk import it can be integrated to PHP app

- Auth0 offers full featured authentication and authorization solution and it's capable of customized rules and flows.

- We are not building our own authentication solution to reduce the security risks coming with handling of passwords.

## Consequences

### Easier:

- We can almost drop a whole user flow

### More difficult:

- Switching between mobile and desktop application

- Becomes more expensive either when the number of users surpasses 10K as compared to [Firebase, which is free for 10K users verifications](https://firebase.google.com/pricing#blaze-calculator), or when the Auth0 enterprise agreement for open source project [pricing](https://auth0.com/pricing) (which is currently free) is altered.

- If the request rate exceeds the current [API rate limits](https://auth0.com/docs/support/policies/rate-limit-policy/management-api-endpoint-rate-limits) (2 requests per second with bursts of up to 10 requests, GET Token: 50 per minute with bursts of up to 500 requests) result in the 429 error message