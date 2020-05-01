
# Functionality
Server side implementation of the QueueIT queueing system. This will put customers into a queue before they can enter the site.

# Installation
How to install the module using different methods.

## Manual
Download the SDK source from the [Github sdk](https://github.com/queueit/KnownUser.V3.PHP) and place these files in `{magentoroot}/lib/Queueit/KnownUser`
Copy the files the src directory to the `{magentoroot}/`.

Clear the magento caches and your module should be ready for configuration.

## Modman

By default modman uses symlinks. Those are not supported anymore by magento (since 1.9.3.4). Thats why `--copy` is used. 
```
modman clone --copy {{repourl}}
```

Also make sure the  lib/Queueit/KnownUser is checkout out with its submodule. (needs the default KnownUser PHP code)

## Composer

Add this module to your package manager or add it directly by:

```
"repositories": [
    {
        "type": "vcs",
        "url": "{{repourl}}"
    }
]
```

Afterwards install it with
```
composer require Queueit/Knownuser
```

# Configuration
In the backend go to:

`System -> Configuration -> QueueIT -> KnownUser`

In here 

- **Enabled**: Enable/disable module execution. If enabled this will generally gives a redirect on every request. So its adviced to enable it only before an event is starting.
- **CustomerID**: This is usually your account name
- **Secret Key**: This is the secret key found in QueueIt: `Account -> Security -> Known User (tab) -> Default secret key` 
- **How are the configs updated**: The way changes on QueueIT's side are requested
  - Push: Configure url in QueueIT. Postback url is `{{store_url}}/knownuser/integrationinfo/update`
  - Pull: This will pull in changes every x minutes (as defined in the cron schedule)
  - Manual: To Update configuration manually or to see the current configuration: `CMS -> QueueIt KnownUser -> Admin Configuration`. You can see the current config at this page and also upload confugartion file for updating it. 

# FPC  (Protecting ajax calls on static pages)
If you have Full Page Cache enabled you should add queue-it javascript to your pages as below :
1) Make sure KnownUser code will not run on static pages (by ignoring those URLs in your integration configuration).
2) Add below JavaScript tags to all static pages : 
You can add this tag in the header files : `...app\design\frontend\rwd\default\template\page\html\header.phtml`

```
<script type="text/javascript" src="//static.queue-it.net/script/queueclient.min.js"></script>
<script
 data-queueit-intercept-domain="{YOUR_CURRENT_DOMAIN}"
   data-queueit-intercept="true"
  data-queueit-c="{YOUR_CUSTOMER_ID}"
  type="text/javascript"
  src="//static.queue-it.net/script/queueconfigloader.min.js">
</script>
```
3) Add some triggers for your dynamic ajax calls you want to queue users on.

# Tested on

## PHP

- 5.6, 
- 7.0
- Should work on 5.5

## Magento
Tested on Community Edition only

- 1.9.3.x
- Should work on 1.7+  

# Authors

- QueueIT
- SupportDesk B.V.
