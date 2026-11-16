---
updatedAt: 2026-04-28T11:42:29.000Z
agentTools:
  projectIndex: https://docs.midtrans.com/llms.txt
---

# Payment Settings

Configure finish redirect URL, notifications URL and BI SNAP URL for payments transactions here.

Set up your callbacks and notifications URL for your payment transactions here.

<br />

<br />

![](https://files.readme.io/d4ceb3bd3f6a35d070ae1ddd7f15068e4702911bf210c3e1adfabc3cbc4e3437-image.png)

<br />

# Finish Redirect URL

***

<br />

Set up a webpage URL that will be seen by customers after completing payment. If you specify your redirection URL as part of the charge request to Midtrans, by default system will use the URL specified in the charge request instead of the URL specified here in the dashboard.

**For Snap Checkout users**, specify your redirection URL in the [Snap Preference settings](https://dashboard.midtrans.com/settings/snap_preference) page\*\* instead in System Settings > Redirection Settings. If you pass the redirection URL as part of the create token request, Midtrans will use that URL instead.

<br />

> 🚧 Exception for some payment methods
>
> Some payment methods like GoPay will only take the callback notification URL specified in the API request instead of dashboard. Pay attention to each payment method's behavior specified in each payment method's guides.

<br />

# Notification URL

***

<br />

## Payment notification URL

<br />

Set up URL where Midtrans will send HTTP notifications for successful, failed and more payment statuses. You can specify multiple URLs by separating them using comma.

You can see your overall HTTP notification history here via the **View Notification History** button. Alternatively, you can also see the notification history for each transaction in the Transaction page by clicking on the specific transaction, then scroll down to the Notification Log section within the opened drawer.

<br />

## Recurring payment notification URL

<br />

If you're using our [Subscription API](https://docs.midtrans.com/reference/api-methods-1), then we will send the HTTP notifications for successful/failed recurring payment to this URL. You can only specify 1 URL here.

<br />

## Account linking notification URL

<br />

If you're using our GoPay Tokenization feature, set up URL where Midtrans will send HTTP notifications for successful/failed GoPay account linking. You can only specify 1 URL here.

<br />

# BI SNAP Notification URL

***

<br />

If you're using our BI SNAP integration, you can set up your Notification URL here. Note that this menu will only be available for you to update if you have completed the production setup.  See more details [here](https://docs.midtrans.com/reference/setting-up-notification-url).
