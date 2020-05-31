# LinkedIn API integration in Laravel

## Install

Homestead make things easier, it's preferable to have homestead installed first.

But you can always use any web server as desired.

Under homestead folder or your web server document folder, clone this project code into it.

```
git clone git@github.com:lyonsun/linkedin-jobs.git
```

## LinkedIn app credentials

Copy `.env.example` file as `.env` into the same folder in this project code.

Add the following at the end of this file:

```
LINKEDIN_CLIENT_ID={your-linkedin-app-client-id}
LINKEDIN_CLIENT_SECRET={your-linkedin-app-client-secret}
LINKEDIN_CSRF={some-random-text-for-csrf-verification}
```

Replace text in curly brackets including the curly brackets themselves with your own app credentials.

## See in action

If using homestead here, go to Homestead installation folder and run:

```
vagrant halt && vagrant up --provision
```

Add the following into /etc/hosts file:

```
{ip-defined-in-your-homestead-yaml-file} linkedin-jobs.test
```

Remember to replace `{ip-defined-in-your-homestead-yaml-file}` with actual value.

Open browser, and visit http://linkedin-jobs.test
