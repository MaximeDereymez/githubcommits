# PHP Technical Test

PHP technical test listing commits in a specific GitHub repository.

## Approach

I have chosen to use only native PHP in this project because it is simple and using frameworks would bring me no advantages in development. Likewise, I used no Javascript to avoid client-side operations when the basic project does not need any to function.

I tried to keep the application modular by making use of the GitHub API, and not hardcode any information specific to the linux repository to make the application as generic as possible.

## How to use

### Prerequisites

This application only needs PHP.

### Launch the application

On Windows, launch the `launch.bat` script, which will normally open the main page on your default browser.
Otherwise, host the application through `php -S localhost:80` command, and access the application by going to `localhost` on your browser.

### Use the application

The main page contains the list of commits for the linux repository. By clicking on the commit message, you can access a page with all the commit's details.

By clicking a user's avatar, you can access its profile page on GitHub.

To see the list of commits for another repository, just fill the form at the top of the page with repository's author name and the repository name, and click OK..

