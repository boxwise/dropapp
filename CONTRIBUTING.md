# Contributing to Boxwise

First off: **thank you**. You will be helping build software that distributes clothes and food to thousands of refugees and other people in need. It gives comfort and dignity to people in a vulnerable situation. That can't be said for most open source contributions.

If you haven't done so already, [join the #dev channel in our Slack group](https://join.slack.com/t/boxwise/shared_invite/enQtMzE4NzExMjkxNTM2LTk0MzY2Mjg0MTY5ZmJjMjI1ODNmODZiNmJlNTAwM2Y4MmJkZDJjZWEyNzk0YTQyZGI0ZTYxMTc2NTgxNjk1ZTM). That is where all the people who build Boxwise are. It's a great place to ask questions.
If you are new, please introduce yourself in the #introductions channel.

A couple of quick things:

- **Friday and Monday are Boxwise Days!** We meet online each Friday at 10am CET to distribute tasks. This call usually takes about an hour. On Monday at 6pm we have a quick 15 min check-up to see where we are. Check out the [slack #dev channel]((https://join.slack.com/t/boxwise/shared_invite/esksnQtMzE4NzExMjkxNTM2LTk0MzY2Mjg0MTY5ZmJjMjI1ODNmODZiNmJlNTAwM2Y4MmJkZDJjZWEyNzk0YTQyZGI0ZTYxMTc2NTgxNjk1ZTM)) for more details.
- **Do you need help using Boxwise?** [Join our Slack group and ask in the #support channel.](https://join.slack.com/t/boxwise/shared_invite/enQtMzE4NzExMjkxNTM2LTk0MzY2Mjg0MTY5ZmJjMjI1ODNmODZiNmJlNTAwM2Y4MmJkZDJjZWEyNzk0YTQyZGI0ZTYxMTc2NTgxNjk1ZTM) Psst. We also wrote [some doc about how to use Boxwise](https://helpme.boxwise.co)
- **Have you found a bug, or got an idea for a feature?** [Write us!](mailto:hello@boxwise.co)

For everything else, please read on...

## Guidelines

- **Start small.** Contributing to a new project can be quite daunting.
- **Be bold!** If there is something that needs fixing, go for it.
- **Make sure your idea has support.** Before spending a lot of time on a feature, it's worth checking that it is wanted first. Ask around in Slack and our product owners.
- **Write tests, if you can.** We are a young project, so we move fast (and break things, unfortunately). But, if you have time, it is worth writing tests for your work. That way we can confirm that the code you are contributing does what you say it does, and we can ensure it doesn't break in the future.
- **Constructive criticism** If you do not like a contribution, feel free to air it. However, before doing so think first about how to improve and propose alternative solutions.

## Making a contribution

First, set up a development environment using the instructions in the readme.
Once you have made your change, submit a pull request with a clear description of what you have done and why. Reference any issues that are relevant. Please write [good commit messages](https://github.com/erlang/otp/wiki/writing-good-commit-messages), ideally as a series of logical commits by ["squashing" them](https://github.com/servo/servo/wiki/Beginner's-guide-to-rebasing-and-squashing). (Don't worry if you don't know how to squash commits - we can do that when we merge your code.)

When creating Pull Requests, please add a link to the card/issue/task you worked and link the PR vice-versa at the task. The PR description should include an explanation of *why* you ended up with the solution you did and the intended behavior. Be clear, especially if you had to make a ‘call’ diverging from the description.

If you haven't contributed to open source before, take a look at [this guide](https://opensource.guide/how-to-contribute/). If you haven't used Git or GitHub before, [this is a tutorial](http://makeapullrequest.com/) that will help. Don't be afraid to ask for help, if you need it. (Everyone is a beginner at some point!)

You can contribute in lots of ways that aren't code, too. For example:

- [Join our Slack group and help people in the #support channel.](https://join.slack.com/t/boxwise/shared_invite/enQtMzE4NzExMjkxNTM2LTk0MzY2Mjg0MTY5ZmJjMjI1ODNmODZiNmJlNTAwM2Y4MmJkZDJjZWEyNzk0YTQyZGI0ZTYxMTc2NTgxNjk1ZTM).
- Report bugs and feature suggestions in [our issue tracker](https://github.com/boxwise/boxwise/issues).
- Spread the word! We are always on the lookout for contributors. Blog, tweet, all those things. We are on [facebook](www.facebook.com/pg/boxwise.co) and [LinkedIn](https://www.linkedin.com/company/12997513).
- [Donate to our cause if you have some spare money lying around!](https://donate.boxwise.co)
- [Get in touch!](mailto:hans@boxwise.co) If you want to give us your opinion or just generally offer your time to help us, feel always welcome to contact us!

## Development principles

These are the principles that have guided our development so far. If you're making a major contribution, it may be worth keeping them in mind.

- **Use boring, well-established technology.** Boxwise needs to keep on working for a long time with minimal work. We don't want to have to rewrite it every year because a technology we are using has gone out of fashion.
- **Minimal maintenance.** We don't have an operations team, nor anyone who can commit to being on-call. The app should run with minimal server-poking.
- **Keep it simple.** 50 lines of straightforward, readable code is better than 10 lines of magic that nobody can understand. ([source](https://github.com/moby/moby/blob/master/project/PRINCIPLES.md))
- **Optimize for contributions.** The code needs to be approachable and easy to understand, particularly for junior developers. Consider whether that clever new technology is worth it if a junior developer will struggle to get their head around it.
- **Move fast and don't break things.** We should ship continuously, but use browser tests to avoid breaking things. These tests should check the basic tasks users need to do to get their jobs done.
