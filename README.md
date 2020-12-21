# violinist-messages

[![Build Status](https://travis-ci.org/violinist-dev/violinist-messages.svg?branch=master)](https://travis-ci.org/violinist-dev/violinist-messages)
[![Coverage Status](https://coveralls.io/repos/github/violinist-dev/violinist-messages/badge.svg?branch=master)](https://coveralls.io/github/violinist-dev/violinist-messages?branch=master)

Template(s) for the pull requests that we send on updates.

## Templates

### Template for the pull request title

Currently it receives the following variables:

`name`: The name of the package to update.

`current_version`: The currently installed version of a package.

`new_version`: The version of the package the pull request is updating to.

`security_prefix`: Either the string `[SECURITY]` or an empty string, depending on whether the update was a security update or not.

### Template for the pull request body

Currently it receives the following variables:

`title`: The title rendered from the title template.

`changelog`: A markdown formatted changelog list, if available.

`custom_message`: A custom message, if configured.

`updated_list`: A list of the changes packages in the update.
